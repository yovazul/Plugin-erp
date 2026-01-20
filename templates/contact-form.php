<?php
/**
 * Template del formulario de contacto
 */

if (!defined('ABSPATH')) {
    exit;
}

$show_company = isset($atts['show_company']) && $atts['show_company'] === 'yes';
?>

<div class="dcf-contact-form-wrapper">
    <form id="dcf-contact-form" class="dcf-form" method="post">
        
        <?php if ($show_company): ?>
        <div class="dcf-form-group">
            <label for="dcf-company-name">
                <?php _e('Nombre de la Empresa', 'dolibarr-contact-form'); ?>
                <span class="dcf-optional">(<?php _e('Opcional', 'dolibarr-contact-form'); ?>)</span>
            </label>
            <input type="text" 
                   id="dcf-company-name" 
                   name="company_name" 
                   class="dcf-input">
        </div>
        <?php endif; ?>
        
        <div class="dcf-form-row">
            <div class="dcf-form-group dcf-col-half">
                <label for="dcf-firstname">
                    <?php _e('Nombre', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
                </label>
                <input type="text" 
                       id="dcf-firstname" 
                       name="firstname" 
                       class="dcf-input" 
                       required>
            </div>
            
            <div class="dcf-form-group dcf-col-half">
                <label for="dcf-lastname">
                    <?php _e('Apellido', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
                </label>
                <input type="text" 
                       id="dcf-lastname" 
                       name="lastname" 
                       class="dcf-input" 
                       required>
            </div>
        </div>
        
        <div class="dcf-form-row">
            <div class="dcf-form-group dcf-col-half">
                <label for="dcf-email">
                    <?php _e('Correo Electrónico', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
                </label>
                <input type="email" 
                       id="dcf-email" 
                       name="email" 
                       class="dcf-input" 
                       required>
            </div>
            
            <div class="dcf-form-group dcf-col-half">
                <label for="dcf-phone">
                    <?php _e('Teléfono', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
                </label>
                <input type="tel" 
                       id="dcf-phone" 
                       name="phone" 
                       class="dcf-input" 
                       required>
            </div>
        </div>
        
        <div class="dcf-form-group">
            <label for="dcf-message">
                <?php _e('Mensaje', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
            </label>
            <textarea id="dcf-message" 
                      name="message" 
                      class="dcf-textarea" 
                      rows="5" 
                      required></textarea>
        </div>
        
        <div class="dcf-form-group">
            <button type="submit" class="dcf-submit-btn">
                <?php _e('Enviar Mensaje', 'dolibarr-contact-form'); ?>
            </button>
        </div>
        
        <div id="dcf-form-messages"></div>
        
        <?php wp_nonce_field('dcf_nonce', 'dcf_nonce_field'); ?>
    </form>
</div>
