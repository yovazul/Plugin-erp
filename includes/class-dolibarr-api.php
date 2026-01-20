<?php
/**
 * Clase para manejar la comunicación con Dolibarr API
 */

if (!defined('ABSPATH')) {
    exit;
}

class Dolibarr_API {
    
    private $api_url;
    private $api_key;
    
    public function __construct() {
        $this->api_url = rtrim(get_option('dcf_dolibarr_url'), '/');
        $this->api_key = get_option('dcf_dolibarr_api_key');
    }
    
    /**
     * Crear un tercero (third party) en Dolibarr
     */
    public function create_thirdparty($data) {
        $endpoint = $this->api_url . '/api/index.php/thirdparties';
        
        $body = array(
            'name' => sanitize_text_field($data['company_name']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field($data['phone']),
            'client' => 1, // 1 = cliente
            'note_public' => sanitize_textarea_field($data['message'])
        );
        
        return $this->make_request('POST', $endpoint, $body);
    }
    
    /**
     * Crear un contacto en Dolibarr
     */
    public function create_contact($data, $thirdparty_id = null) {
        $endpoint = $this->api_url . '/api/index.php/contacts';
        
        $body = array(
            'firstname' => sanitize_text_field($data['firstname']),
            'lastname' => sanitize_text_field($data['lastname']),
            'email' => sanitize_email($data['email']),
            'phone_pro' => sanitize_text_field($data['phone']),
            'note_public' => sanitize_textarea_field($data['message'])
        );
        
        // Si hay un tercero, asociar el contacto
        if ($thirdparty_id) {
            $body['socid'] = $thirdparty_id;
        }
        
        return $this->make_request('POST', $endpoint, $body);
    }
    
    /**
     * Crear ticket de soporte con el mensaje
     */
    public function create_ticket($data, $contact_id = null) {
        $endpoint = $this->api_url . '/api/index.php/tickets';
        
        $subject = sprintf(
            __('Contacto desde web: %s %s', 'dolibarr-contact-form'),
            $data['firstname'],
            $data['lastname']
        );
        
        $body = array(
            'subject' => $subject,
            'message' => sanitize_textarea_field($data['message']),
            'email' => sanitize_email($data['email']),
            'type_code' => 'COM', // Tipo comercial
            'category_code' => 'OTHER'
        );
        
        if ($contact_id) {
            $body['fk_soc'] = $contact_id;
        }
        
        return $this->make_request('POST', $endpoint, $body);
    }
    
    /**
     * Realizar petición HTTP a la API de Dolibarr
     */
    private function make_request($method, $url, $body = array()) {
        $args = array(
            'method' => $method,
            'headers' => array(
                'DOLAPIKEY' => $this->api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ),
            'timeout' => 30,
            'sslverify' => true
        );
        
        if (!empty($body)) {
            $args['body'] = json_encode($body);
        }
        
        $response = wp_remote_request($url, $args);
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'error' => $response->get_error_message()
            );
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if ($status_code >= 200 && $status_code < 300) {
            return array(
                'success' => true,
                'data' => $data
            );
        } else {
            return array(
                'success' => false,
                'error' => isset($data['error']) ? $data['error']['message'] : __('Error desconocido', 'dolibarr-contact-form'),
                'status_code' => $status_code
            );
        }
    }
    
    /**
     * Verificar conexión con la API
     */
    public function test_connection() {
        $endpoint = $this->api_url . '/api/index.php/status';
        return $this->make_request('GET', $endpoint);
    }
}
