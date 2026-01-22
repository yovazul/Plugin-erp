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
        $raw_url = get_option('dcf_dolibarr_url', '');
        $raw_key = get_option('dcf_dolibarr_api_key', '');
        
        // Limpiar espacios en blanco
        $this->api_url = rtrim(trim($raw_url), '/');
        $this->api_key = trim($raw_key);
    }
    
    /**
     * Validar configuración de la API
     */
    public function is_configured() {
        $is_valid = !empty($this->api_url) && !empty($this->api_key);
        
        // Log de debugging
        if (!$is_valid) {
            error_log('DCF Configuration Error:');
            error_log('  URL: ' . ($this->api_url ?: '[vacío]'));
            error_log('  API Key: ' . ($this->api_key ? '[configurada]' : '[vacía]'));
        }
        
        return $is_valid;
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
            'client' => 2, // 2 = cliente potencial (prospect)
            'note_public' => sanitize_textarea_field($data['message']),
            'array_options' => array(
                'options_origen' => 1 // Campo personalizado "origen" con valor 1
            )
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
        // Validar URL
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return array(
                'success' => false,
                'error' => __('No se ha facilitado una URL válida.', 'dolibarr-contact-form') . ' URL: ' . $url
            );
        }
        
        // Log para debugging
        error_log('DCF API Request: ' . $method . ' ' . $url);
        if (!empty($body)) {
            error_log('DCF API Body: ' . json_encode($body));
        }
        
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
            // Dolibarr devuelve el ID directamente como número cuando crea un recurso
            return array(
                'success' => true,
                'data' => $data,
                'id' => is_numeric($data) ? (int)$data : (isset($data['id']) ? $data['id'] : null)
            );
        } else {
            $error_message = __('Error desconocido', 'dolibarr-contact-form');
            
            if (isset($data['error']['message'])) {
                $error_message = $data['error']['message'];
            } elseif (isset($data['error'])) {
                $error_message = is_string($data['error']) ? $data['error'] : json_encode($data['error']);
            } elseif (!empty($body)) {
                $error_message = $body;
            }
            
            // Log del error para debugging
            error_log('Dolibarr API Error: ' . $error_message . ' (Status: ' . $status_code . ')');
            
            return array(
                'success' => false,
                'error' => $error_message,
                'status_code' => $status_code,
                'raw_response' => $body
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
