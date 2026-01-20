<?php
/**
 * Plugin Name: Dolibarr Contact Form
 * Plugin URI: https://github.com/yovazul/Plugin-erp
 * Description: Plugin de WordPress que integra un formulario de contacto con el ERP Dolibarr. Permite crear terceros y contactos directamente desde WordPress.
 * Version: 1.0.0
 * Author: Yovazul
 * Author URI: https://github.com/yovazul
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dolibarr-contact-form
 * Domain Path: /languages
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('DCF_VERSION', '1.0.0');
define('DCF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DCF_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DCF_LOG_FILE', WP_CONTENT_DIR . '/dolibarr-contact-form.log');

// Incluir archivos necesarios
require_once DCF_PLUGIN_DIR . 'includes/class-dolibarr-api.php';
require_once DCF_PLUGIN_DIR . 'includes/class-form-handler.php';
require_once DCF_PLUGIN_DIR . 'includes/class-logger.php';

/**
 * Activación del plugin
 */
function dcf_activate() {
    // Crear directorio de logs si no existe
    $log_dir = dirname(DCF_LOG_FILE);
    if (!file_exists($log_dir)) {
        wp_mkdir_p($log_dir);
    }
    
    // Log de activación
    DCF_Logger::log('Plugin activado');
    
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'dcf_activate');

/**
 * Desactivación del plugin
 */
function dcf_deactivate() {
    DCF_Logger::log('Plugin desactivado');
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'dcf_deactivate');

/**
 * Cargar estilos y scripts del frontend
 */
function dcf_enqueue_scripts() {
    wp_enqueue_style('dcf-styles', DCF_PLUGIN_URL . 'assets/css/style.css', array(), DCF_VERSION);
    wp_enqueue_script('dcf-scripts', DCF_PLUGIN_URL . 'assets/js/script.js', array('jquery'), DCF_VERSION, true);
    
    // Pasar datos al JavaScript
    wp_localize_script('dcf-scripts', 'dcfAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dcf_submit_form'),
        'messages' => array(
            'required' => __('Este campo es obligatorio', 'dolibarr-contact-form'),
            'invalid_email' => __('Por favor, introduce un correo electrónico válido', 'dolibarr-contact-form'),
            'invalid_phone' => __('Por favor, introduce un teléfono válido', 'dolibarr-contact-form'),
            'submitting' => __('Enviando...', 'dolibarr-contact-form'),
            'error' => __('Ha ocurrido un error. Por favor, inténtalo de nuevo.', 'dolibarr-contact-form')
        )
    ));
}
add_action('wp_enqueue_scripts', 'dcf_enqueue_scripts');

/**
 * Registrar shortcode del formulario
 */
function dcf_contact_form_shortcode($atts) {
    // Atributos del shortcode
    $atts = shortcode_atts(array(
        'title' => __('Formulario de Contacto', 'dolibarr-contact-form')
    ), $atts);
    
    ob_start();
    include DCF_PLUGIN_DIR . 'templates/contact-form.php';
    return ob_get_clean();
}
add_shortcode('dolibarr_contact_form', 'dcf_contact_form_shortcode');

/**
 * Manejar envío del formulario via AJAX
 */
function dcf_handle_form_submission() {
    // Verificar nonce
    check_ajax_referer('dcf_submit_form', 'nonce');
    
    try {
        // Instanciar el manejador del formulario
        $form_handler = new DCF_Form_Handler();
        
        // Procesar el formulario
        $result = $form_handler->process_form($_POST);
        
        if ($result['success']) {
            wp_send_json_success(array(
                'message' => __('Formulario enviado exitosamente. Nos pondremos en contacto contigo pronto.', 'dolibarr-contact-form')
            ));
        } else {
            wp_send_json_error(array(
                'message' => $result['message']
            ));
        }
    } catch (Exception $e) {
        DCF_Logger::log('Error en envío del formulario: ' . $e->getMessage());
        wp_send_json_error(array(
            'message' => __('Ha ocurrido un error al procesar tu solicitud. Por favor, inténtalo de nuevo más tarde.', 'dolibarr-contact-form')
        ));
    }
}
add_action('wp_ajax_dcf_submit_form', 'dcf_handle_form_submission');
add_action('wp_ajax_nopriv_dcf_submit_form', 'dcf_handle_form_submission');

/**
 * Cargar traducciones
 */
function dcf_load_textdomain() {
    load_plugin_textdomain('dolibarr-contact-form', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'dcf_load_textdomain');
