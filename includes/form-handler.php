<?php
/**
 * Manejador del formulario
 */

if (!defined('ABSPATH')) {
    exit;
}

function dcf_process_form() {
    // Validar campos requeridos
    $required_fields = array('firstname', 'lastname', 'email', 'phone', 'message');
    $errors = array();
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = sprintf(__('El campo %s es requerido', 'dolibarr-contact-form'), $field);
        }
    }
    
    // Validar email
    if (!empty($_POST['email']) && !is_email($_POST['email'])) {
        $errors[] = __('El email no es válido', 'dolibarr-contact-form');
    }
    
    if (!empty($errors)) {
        wp_send_json_error(array(
            'message' => implode('<br>', $errors)
        ));
    }
    
    // Preparar datos
    $form_data = array(
        'firstname' => sanitize_text_field($_POST['firstname']),
        'lastname' => sanitize_text_field($_POST['lastname']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'message' => sanitize_textarea_field($_POST['message']),
        'company_name' => !empty($_POST['company_name']) ? sanitize_text_field($_POST['company_name']) : ''
    );
    
    // Inicializar API de Dolibarr
    $api = new Dolibarr_API();
    
    // Validar que la API esté configurada
    if (!$api->is_configured()) {
        $url = get_option('dcf_dolibarr_url', '');
        $key = get_option('dcf_dolibarr_api_key', '');
        
        $debug_info = '';
        if (current_user_can('manage_options')) {
            $debug_info = '<br><small>';
            $debug_info .= 'URL configurada: ' . (!empty($url) ? '✓' : '✗') . '<br>';
            $debug_info .= 'API Key configurada: ' . (!empty($key) ? '✓' : '✗');
            $debug_info .= '</small>';
        }
        
        wp_send_json_error(array(
            'message' => __('El plugin no está configurado correctamente. Por favor, configura la URL de Dolibarr y la API Key en Ajustes → Dolibarr Form.', 'dolibarr-contact-form') . $debug_info
        ));
    }
    
    $thirdparty_id = null;
    
    // Si se proporcionó nombre de empresa, crear tercero
    if (!empty($form_data['company_name'])) {
        $result = $api->create_thirdparty($form_data);
        
        if (!$result['success']) {
            error_log('DCF Error creating thirdparty: ' . print_r($result, true));
            wp_send_json_error(array(
                'message' => get_option('dcf_error_message') . ' (Empresa: ' . $result['error'] . ')'
            ));
        }
        
        // Dolibarr devuelve el ID directamente
        $thirdparty_id = isset($result['id']) ? $result['id'] : null;
    }
    
    // Crear contacto
    $contact_result = $api->create_contact($form_data, $thirdparty_id);
    
    if (!$contact_result['success']) {
        error_log('DCF Error creating contact: ' . print_r($contact_result, true));
        wp_send_json_error(array(
            'message' => get_option('dcf_error_message') . ' (Contacto: ' . $contact_result['error'] . ')'
        ));
    }
    
    // Opcionalmente crear ticket con el mensaje
    $contact_id = isset($contact_result['id']) ? $contact_result['id'] : null;
    $ticket_result = $api->create_ticket($form_data, $contact_id);
    
    // Log de ticket si falla (pero no detener el proceso)
    if (isset($ticket_result['success']) && !$ticket_result['success']) {
        error_log('DCF Error creating ticket: ' . print_r($ticket_result, true));
    }
    
    // Log exitoso (opcional)
    do_action('dcf_form_submitted', $form_data, $contact_result);
    
    wp_send_json_success(array(
        'message' => get_option('dcf_success_message')
    ));
}
