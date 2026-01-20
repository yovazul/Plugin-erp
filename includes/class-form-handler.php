<?php
/**
 * Clase para manejar el procesamiento del formulario
 */

if (!defined('ABSPATH')) {
    exit;
}

class DCF_Form_Handler {
    
    /**
     * Instancia de la API de Dolibarr
     */
    private $api;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->api = new DCF_Dolibarr_API();
    }
    
    /**
     * Procesar el formulario de contacto
     * 
     * @param array $form_data Datos del formulario
     * @return array Resultado del procesamiento
     */
    public function process_form($form_data) {
        try {
            // Validar datos del formulario
            $validation = $this->validate_form_data($form_data);
            
            if (!$validation['valid']) {
                return array(
                    'success' => false,
                    'message' => $validation['message']
                );
            }
            
            // Guardar datos en el log antes de procesarlos (para prevenir pérdida de datos)
            DCF_Logger::log('Procesando formulario: ' . json_encode($form_data));
            
            $contact_data = array(
                'nombre' => $form_data['nombre'],
                'apellido' => $form_data['apellido'],
                'email' => $form_data['email'],
                'telefono' => $form_data['telefono'],
                'mensaje' => isset($form_data['mensaje']) ? $form_data['mensaje'] : '',
            );
            
            // Verificar si se proporcionó empresa
            if (!empty($form_data['empresa'])) {
                // Escenario 1: Con empresa
                return $this->process_with_company($contact_data, $form_data['empresa']);
            } else {
                // Escenario 2: Sin empresa
                return $this->process_without_company($contact_data);
            }
            
        } catch (Exception $e) {
            DCF_Logger::log('Error en process_form: ' . $e->getMessage());
            return array(
                'success' => false,
                'message' => __('Error al procesar el formulario. Por favor, inténtalo de nuevo más tarde.', 'dolibarr-contact-form')
            );
        }
    }
    
    /**
     * Validar datos del formulario
     * 
     * @param array $data Datos a validar
     * @return array Resultado de la validación
     */
    private function validate_form_data($data) {
        $errors = array();
        
        // Validar nombre
        if (empty($data['nombre']) || strlen(trim($data['nombre'])) < 2) {
            $errors[] = __('El nombre es obligatorio y debe tener al menos 2 caracteres.', 'dolibarr-contact-form');
        }
        
        // Validar apellido
        if (empty($data['apellido']) || strlen(trim($data['apellido'])) < 2) {
            $errors[] = __('El apellido es obligatorio y debe tener al menos 2 caracteres.', 'dolibarr-contact-form');
        }
        
        // Validar email
        if (empty($data['email']) || !is_email($data['email'])) {
            $errors[] = __('Por favor, introduce un correo electrónico válido.', 'dolibarr-contact-form');
        }
        
        // Validar teléfono (debe contener solo números, espacios, guiones y paréntesis)
        if (empty($data['telefono'])) {
            $errors[] = __('El teléfono móvil es obligatorio.', 'dolibarr-contact-form');
        } else {
            $telefono_clean = preg_replace('/[^0-9+]/', '', $data['telefono']);
            if (strlen($telefono_clean) < 9) {
                $errors[] = __('Por favor, introduce un teléfono válido (mínimo 9 dígitos).', 'dolibarr-contact-form');
            }
        }
        
        if (!empty($errors)) {
            return array(
                'valid' => false,
                'message' => implode(' ', $errors)
            );
        }
        
        return array('valid' => true);
    }
    
    /**
     * Procesar formulario con empresa
     * 
     * @param array $contact_data Datos del contacto
     * @param string $company_name Nombre de la empresa
     * @return array Resultado del procesamiento
     */
    private function process_with_company($contact_data, $company_name) {
        try {
            // Paso 1: Crear el tercero (empresa)
            DCF_Logger::log('Creando tercero (empresa): ' . $company_name);
            $thirdparty_id = $this->api->create_thirdparty($company_name);
            
            if (!$thirdparty_id) {
                throw new Exception('No se pudo crear el tercero');
            }
            
            // Paso 2: Crear el contacto vinculado a la empresa
            $contact_data['socid'] = $thirdparty_id;
            DCF_Logger::log('Creando contacto vinculado al tercero ID: ' . $thirdparty_id);
            $contact_id = $this->api->create_contact($contact_data);
            
            if (!$contact_id) {
                throw new Exception('No se pudo crear el contacto');
            }
            
            DCF_Logger::log('Formulario procesado exitosamente con empresa. Contact ID: ' . $contact_id);
            
            return array(
                'success' => true,
                'contact_id' => $contact_id,
                'thirdparty_id' => $thirdparty_id
            );
            
        } catch (Exception $e) {
            DCF_Logger::log('Error en process_with_company: ' . $e->getMessage());
            return array(
                'success' => false,
                'message' => __('Error al crear el contacto con la empresa. Por favor, inténtalo de nuevo.', 'dolibarr-contact-form')
            );
        }
    }
    
    /**
     * Procesar formulario sin empresa
     * 
     * @param array $contact_data Datos del contacto
     * @return array Resultado del procesamiento
     */
    private function process_without_company($contact_data) {
        try {
            // Paso 1: Crear el contacto (sin vincular a empresa)
            DCF_Logger::log('Creando contacto sin empresa');
            $contact_id = $this->api->create_contact($contact_data);
            
            if (!$contact_id) {
                throw new Exception('No se pudo crear el contacto');
            }
            
            // Paso 2: Añadir etiqueta "contacto desde la web"
            DCF_Logger::log('Añadiendo etiqueta "contacto desde la web" al contacto ID: ' . $contact_id);
            $this->api->add_category_to_contact($contact_id, 'contacto desde la web');
            
            // Paso 3: Asignar a un agente comercial (ID por defecto: 1)
            DCF_Logger::log('Asignando agente comercial al contacto ID: ' . $contact_id);
            // Nota: La asignación de agente comercial requiere que el contacto esté vinculado a un tercero
            // Como este contacto no tiene tercero, esta operación puede no ser posible
            $this->api->assign_sales_agent($contact_id, 1);
            
            DCF_Logger::log('Formulario procesado exitosamente sin empresa. Contact ID: ' . $contact_id);
            
            return array(
                'success' => true,
                'contact_id' => $contact_id
            );
            
        } catch (Exception $e) {
            DCF_Logger::log('Error en process_without_company: ' . $e->getMessage());
            return array(
                'success' => false,
                'message' => __('Error al crear el contacto. Por favor, inténtalo de nuevo.', 'dolibarr-contact-form')
            );
        }
    }
}
