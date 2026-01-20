<?php
/**
 * Clase para integración con API de Dolibarr
 */

if (!defined('ABSPATH')) {
    exit;
}

class DCF_Dolibarr_API {
    
    /**
     * URL base de la API de Dolibarr
     */
    private $api_base_url = 'https://intetron.co/plataforma/api/index.php';
    
    /**
     * API Key de Dolibarr
     */
    private $api_key = '5P3cw77r825RIXwE8eGuZIj4dmcPF0kK';
    
    /**
     * Constructor
     */
    public function __construct() {
        // La configuración está definida directamente en las propiedades
    }
    
    /**
     * Realizar petición a la API de Dolibarr
     * 
     * @param string $endpoint Endpoint de la API
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param array $data Datos a enviar
     * @return array Respuesta de la API
     */
    private function api_request($endpoint, $method = 'GET', $data = array()) {
        $url = $this->api_base_url . $endpoint;
        
        $args = array(
            'method' => $method,
            'headers' => array(
                'DOLAPIKEY' => $this->api_key,
                'Content-Type' => 'application/json',
            ),
            'timeout' => 30,
        );
        
        if (!empty($data) && in_array($method, array('POST', 'PUT', 'PATCH'))) {
            $args['body'] = json_encode($data);
        }
        
        DCF_Logger::log("API Request: $method $url");
        if (defined('DCF_DEBUG_MODE') && DCF_DEBUG_MODE) {
            DCF_Logger::log("Request Data: " . json_encode($data));
        }
        
        $response = wp_remote_request($url, $args);
        
        if (is_wp_error($response)) {
            DCF_Logger::log("API Error: " . $response->get_error_message());
            throw new Exception('Error de conexión con Dolibarr: ' . $response->get_error_message());
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        
        DCF_Logger::log("API Response Code: $response_code");
        if (defined('DCF_DEBUG_MODE') && DCF_DEBUG_MODE) {
            DCF_Logger::log("API Response Body: $response_body");
        }
        
        if ($response_code < 200 || $response_code >= 300) {
            $error_message = 'Error en la API de Dolibarr';
            $body_decoded = json_decode($response_body, true);
            if (isset($body_decoded['error']['message'])) {
                $error_message .= ': ' . $body_decoded['error']['message'];
            }
            throw new Exception($error_message);
        }
        
        return json_decode($response_body, true);
    }
    
    /**
     * Crear un tercero (empresa) en Dolibarr
     * 
     * @param string $company_name Nombre de la empresa
     * @return int ID del tercero creado
     */
    public function create_thirdparty($company_name) {
        $data = array(
            'name' => sanitize_text_field($company_name),
            'client' => 1, // 1 = cliente, 2 = prospecto, 3 = cliente y prospecto
            'code_client' => 'auto',
        );
        
        try {
            $response = $this->api_request('/thirdparties', 'POST', $data);
            
            if (isset($response['id'])) {
                DCF_Logger::log("Tercero creado con ID: " . $response['id']);
                return $response['id'];
            } elseif (is_numeric($response)) {
                DCF_Logger::log("Tercero creado con ID: " . $response);
                return (int)$response;
            }
            
            throw new Exception('No se pudo obtener el ID del tercero creado');
        } catch (Exception $e) {
            DCF_Logger::log("Error al crear tercero: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Crear un contacto en Dolibarr
     * 
     * @param array $contact_data Datos del contacto
     * @return int ID del contacto creado
     */
    public function create_contact($contact_data) {
        $data = array(
            'firstname' => sanitize_text_field($contact_data['nombre']),
            'lastname' => sanitize_text_field($contact_data['apellido']),
            'email' => sanitize_email($contact_data['email']),
            'phone_mobile' => sanitize_text_field($contact_data['telefono']),
        );
        
        // Si hay empresa, vincular el contacto
        if (isset($contact_data['socid']) && $contact_data['socid'] > 0) {
            $data['socid'] = (int)$contact_data['socid'];
        }
        
        // Si hay nota, añadirla
        if (!empty($contact_data['mensaje'])) {
            $data['note_public'] = sanitize_textarea_field($contact_data['mensaje']);
        }
        
        try {
            $response = $this->api_request('/contacts', 'POST', $data);
            
            if (isset($response['id'])) {
                DCF_Logger::log("Contacto creado con ID: " . $response['id']);
                return $response['id'];
            } elseif (is_numeric($response)) {
                DCF_Logger::log("Contacto creado con ID: " . $response);
                return (int)$response;
            }
            
            throw new Exception('No se pudo obtener el ID del contacto creado');
        } catch (Exception $e) {
            DCF_Logger::log("Error al crear contacto: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Añadir una categoría (etiqueta) a un contacto
     * 
     * @param int $contact_id ID del contacto
     * @param string $category_label Etiqueta de la categoría
     * @return bool Éxito de la operación
     */
    public function add_category_to_contact($contact_id, $category_label) {
        try {
            // Primero, buscar o crear la categoría
            $category_id = $this->get_or_create_category($category_label);
            
            if (!$category_id) {
                DCF_Logger::log("No se pudo obtener o crear la categoría");
                return false;
            }
            
            // Añadir la categoría al contacto
            $endpoint = "/contacts/{$contact_id}/categories/{$category_id}";
            $this->api_request($endpoint, 'POST');
            
            DCF_Logger::log("Categoría añadida al contacto ID: $contact_id");
            return true;
        } catch (Exception $e) {
            DCF_Logger::log("Error al añadir categoría al contacto: " . $e->getMessage());
            // No lanzar excepción, solo registrar el error
            return false;
        }
    }
    
    /**
     * Obtener o crear una categoría
     * 
     * @param string $label Etiqueta de la categoría
     * @return int|false ID de la categoría o false si falla
     */
    private function get_or_create_category($label) {
        try {
            // Buscar categorías existentes
            $categories = $this->api_request('/categories?type=contact&sortfield=label&sortorder=ASC&limit=100', 'GET');
            
            if (is_array($categories)) {
                foreach ($categories as $category) {
                    if (isset($category['label']) && strtolower($category['label']) === strtolower($label)) {
                        return $category['id'];
                    }
                }
            }
            
            // Si no existe, crear la categoría
            $data = array(
                'label' => sanitize_text_field($label),
                'type' => 'contact',
            );
            
            $response = $this->api_request('/categories', 'POST', $data);
            
            if (isset($response['id'])) {
                return $response['id'];
            } elseif (is_numeric($response)) {
                return (int)$response;
            }
            
            return false;
        } catch (Exception $e) {
            DCF_Logger::log("Error al obtener o crear categoría: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Asignar un agente comercial a un contacto
     * 
     * @param int $contact_id ID del contacto
     * @param int $user_id ID del usuario/agente (por defecto 1)
     * @return bool Éxito de la operación
     */
    public function assign_sales_agent($contact_id, $user_id = 1) {
        try {
            // En Dolibarr, asignar un representante de ventas a un contacto
            // se hace a través del tercero asociado o mediante una actualización del contacto
            
            // Intentar obtener los datos del contacto primero
            $contact = $this->api_request("/contacts/{$contact_id}", 'GET');
            
            // Si el contacto tiene un tercero asociado, asignar el agente al tercero
            if (isset($contact['socid']) && $contact['socid'] > 0) {
                $endpoint = "/thirdparties/{$contact['socid']}/representatives/{$user_id}";
                $this->api_request($endpoint, 'POST');
                DCF_Logger::log("Agente comercial asignado al tercero del contacto ID: $contact_id");
                return true;
            }
            
            DCF_Logger::log("Contacto sin tercero asociado, no se puede asignar agente comercial directamente");
            return false;
        } catch (Exception $e) {
            DCF_Logger::log("Error al asignar agente comercial: " . $e->getMessage());
            // No lanzar excepción, solo registrar el error
            return false;
        }
    }
}
