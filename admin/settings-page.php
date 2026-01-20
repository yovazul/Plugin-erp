<?php
/**
 * Página de configuración del plugin
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php if (isset($_GET['settings-updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Configuración guardada correctamente.', 'dolibarr-contact-form'); ?></p>
        </div>
    <?php endif; ?>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('dcf_settings_group');
        do_settings_sections('dcf_settings_group');
        ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="dcf_dolibarr_url"><?php _e('URL de Dolibarr', 'dolibarr-contact-form'); ?></label>
                </th>
                <td>
                    <input type="url" 
                           id="dcf_dolibarr_url" 
                           name="dcf_dolibarr_url" 
                           value="<?php echo esc_attr(get_option('dcf_dolibarr_url')); ?>" 
                           class="regular-text"
                           placeholder="https://tudominio.com/dolibarr">
                    <p class="description">
                        <?php _e('URL base de tu instalación de Dolibarr (sin barra final)', 'dolibarr-contact-form'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="dcf_dolibarr_api_key"><?php _e('API Key de Dolibarr', 'dolibarr-contact-form'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           id="dcf_dolibarr_api_key" 
                           name="dcf_dolibarr_api_key" 
                           value="<?php echo esc_attr(get_option('dcf_dolibarr_api_key')); ?>" 
                           class="regular-text"
                           placeholder="xxxxxxxxxxxxxxxxxxxxxxxxx">
                    <p class="description">
                        <?php _e('API Key generada desde Dolibarr (Usuario → API Keys)', 'dolibarr-contact-form'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="dcf_success_message"><?php _e('Mensaje de éxito', 'dolibarr-contact-form'); ?></label>
                </th>
                <td>
                    <textarea id="dcf_success_message" 
                              name="dcf_success_message" 
                              rows="3" 
                              class="large-text"><?php echo esc_textarea(get_option('dcf_success_message')); ?></textarea>
                    <p class="description">
                        <?php _e('Mensaje que se muestra cuando el formulario se envía correctamente', 'dolibarr-contact-form'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="dcf_error_message"><?php _e('Mensaje de error', 'dolibarr-contact-form'); ?></label>
                </th>
                <td>
                    <textarea id="dcf_error_message" 
                              name="dcf_error_message" 
                              rows="3" 
                              class="large-text"><?php echo esc_textarea(get_option('dcf_error_message')); ?></textarea>
                    <p class="description">
                        <?php _e('Mensaje que se muestra cuando hay un error al enviar el formulario', 'dolibarr-contact-form'); ?>
                    </p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(__('Guardar Configuración', 'dolibarr-contact-form')); ?>
    </form>
    
    <hr>
    
    <h2><?php _e('Probar Conexión', 'dolibarr-contact-form'); ?></h2>
    <p><?php _e('Verifica que la conexión con Dolibarr funcione correctamente:', 'dolibarr-contact-form'); ?></p>
    
    <button type="button" class="button button-secondary" id="dcf-test-connection">
        <?php _e('Probar Conexión', 'dolibarr-contact-form'); ?>
    </button>
    
    <div id="dcf-test-result" style="margin-top: 15px;"></div>
    
    <hr>
    
    <h2><?php _e('Uso del Shortcode', 'dolibarr-contact-form'); ?></h2>
    <p><?php _e('Usa este shortcode en cualquier página o entrada para mostrar el formulario:', 'dolibarr-contact-form'); ?></p>
    <code>[dolibarr_contact_form]</code>
    
    <p><?php _e('Para ocultar el campo de empresa:', 'dolibarr-contact-form'); ?></p>
    <code>[dolibarr_contact_form show_company="no"]</code>
</div>

<script>
jQuery(document).ready(function($) {
    $('#dcf-test-connection').on('click', function() {
        var button = $(this);
        var resultDiv = $('#dcf-test-result');
        
        button.prop('disabled', true).text('<?php _e('Probando...', 'dolibarr-contact-form'); ?>');
        resultDiv.html('');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'dcf_test_connection',
                nonce: '<?php echo wp_create_nonce('dcf_test_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    resultDiv.html('<div class="notice notice-success inline"><p>✓ ' + response.data.message + '</p></div>');
                } else {
                    resultDiv.html('<div class="notice notice-error inline"><p>✗ ' + response.data.message + '</p></div>');
                }
            },
            error: function() {
                resultDiv.html('<div class="notice notice-error inline"><p><?php _e('Error al realizar la prueba', 'dolibarr-contact-form'); ?></p></div>');
            },
            complete: function() {
                button.prop('disabled', false).text('<?php _e('Probar Conexión', 'dolibarr-contact-form'); ?>');
            }
        });
    });
});
</script>
