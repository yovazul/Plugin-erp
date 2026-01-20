<?php
/**
 * Plugin Name: Dolibarr Contact Form
 * Plugin URI: https://github.com/yovazul/Plugin-erp
 * Description: Formulario de contacto que envía datos directamente a Dolibarr ERP
 * Version: 1.0.0
 * Author: YovaZul
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

// Definir constantes
define('DCF_VERSION', '1.0.0');
define('DCF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DCF_PLUGIN_URL', plugin_dir_url(__FILE__));

// Clase principal del plugin
class Dolibarr_Contact_Form {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Hooks de activación y desactivación
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Inicializar el plugin
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    public function init() {
        // Cargar traducciones
        load_plugin_textdomain('dolibarr-contact-form', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Incluir archivos necesarios
        $this->includes();
        
        // Registrar shortcode
        add_shortcode('dolibarr_contact_form', array($this, 'render_form'));
        
        // Hooks de admin
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_settings'));
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
        }
        
        // Registrar scripts y estilos
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // AJAX handler
        add_action('wp_ajax_dcf_submit_form', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_dcf_submit_form', array($this, 'handle_form_submission'));
        add_action('wp_ajax_dcf_test_connection', array($this, 'test_connection'));
    }
    
    /**
     * Agregar enlace de configuración en la lista de plugins
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=dolibarr-contact-form') . '">' . __('Configuración', 'dolibarr-contact-form') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    
    private function includes() {
        require_once DCF_PLUGIN_DIR . 'includes/class-dolibarr-api.php';
        require_once DCF_PLUGIN_DIR . 'includes/form-handler.php';
    }
    
    public function activate() {
        // Crear opciones por defecto
        add_option('dcf_dolibarr_url', '');
        add_option('dcf_dolibarr_api_key', '');
        add_option('dcf_success_message', __('¡Gracias! Tu mensaje ha sido enviado correctamente.', 'dolibarr-contact-form'));
        add_option('dcf_error_message', __('Ha ocurrido un error. Por favor, inténtalo de nuevo.', 'dolibarr-contact-form'));
    }
    
    public function deactivate() {
        // Limpiar si es necesario
    }
    
    public function add_admin_menu() {
        add_options_page(
            __('Dolibarr Contact Form', 'dolibarr-contact-form'),
            __('Dolibarr Form', 'dolibarr-contact-form'),
            'manage_options',
            'dolibarr-contact-form',
            array($this, 'render_admin_page')
        );
    }
    
    public function register_settings() {
        register_setting('dcf_settings_group', 'dcf_dolibarr_url');
        register_setting('dcf_settings_group', 'dcf_dolibarr_api_key');
        register_setting('dcf_settings_group', 'dcf_success_message');
        register_setting('dcf_settings_group', 'dcf_error_message');
    }
    
    public function render_admin_page() {
        require_once DCF_PLUGIN_DIR . 'admin/settings-page.php';
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('dcf-styles', DCF_PLUGIN_URL . 'assets/css/style.css', array(), DCF_VERSION);
        wp_enqueue_script('dcf-script', DCF_PLUGIN_URL . 'assets/js/form.js', array('jquery'), DCF_VERSION, true);
        
        wp_localize_script('dcf-script', 'dcfAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dcf_nonce'),
            'loading' => __('Enviando...', 'dolibarr-contact-form')
        ));
    }
    
    public function render_form($atts) {
        $atts = shortcode_atts(array(
            'show_company' => 'yes',
        ), $atts);
        
        ob_start();
        require DCF_PLUGIN_DIR . 'templates/contact-form.php';
        return ob_get_clean();
    }
    
    public function handle_form_submission() {
        check_ajax_referer('dcf_nonce', 'nonce');
        
        require_once DCF_PLUGIN_DIR . 'includes/form-handler.php';
        dcf_process_form();
    }
    
    public function test_connection() {
        check_ajax_referer('dcf_test_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => __('No tienes permisos para realizar esta acción', 'dolibarr-contact-form')
            ));
        }
        
        $api = new Dolibarr_API();
        $result = $api->test_connection();
        
        if ($result['success']) {
            wp_send_json_success(array(
                'message' => __('Conexión exitosa con Dolibarr', 'dolibarr-contact-form')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Error de conexión: ', 'dolibarr-contact-form') . $result['error']
            ));
        }
    }
}

// Inicializar el plugin
function dcf_init() {
    return Dolibarr_Contact_Form::get_instance();
}

// Iniciar
dcf_init();
