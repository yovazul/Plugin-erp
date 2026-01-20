<?php
/**
 * Template para el formulario de contacto
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="dcf-contact-form-wrapper">
    <?php if (!empty($atts['title'])) : ?>
        <h2 class="dcf-form-title"><?php echo esc_html($atts['title']); ?></h2>
    <?php endif; ?>
    
    <div class="dcf-messages">
        <div class="dcf-message dcf-success" style="display: none;"></div>
        <div class="dcf-message dcf-error" style="display: none;"></div>
    </div>
    
    <form id="dcf-contact-form" class="dcf-contact-form" method="post" novalidate>
        <div class="dcf-form-row">
            <div class="dcf-form-group">
                <label for="dcf-nombre">
                    <?php _e('Nombre', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
                </label>
                <input 
                    type="text" 
                    id="dcf-nombre" 
                    name="nombre" 
                    class="dcf-input" 
                    required
                    placeholder="<?php esc_attr_e('Tu nombre', 'dolibarr-contact-form'); ?>"
                />
                <span class="dcf-error-message"></span>
            </div>
            
            <div class="dcf-form-group">
                <label for="dcf-apellido">
                    <?php _e('Apellido', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
                </label>
                <input 
                    type="text" 
                    id="dcf-apellido" 
                    name="apellido" 
                    class="dcf-input" 
                    required
                    placeholder="<?php esc_attr_e('Tu apellido', 'dolibarr-contact-form'); ?>"
                />
                <span class="dcf-error-message"></span>
            </div>
        </div>
        
        <div class="dcf-form-row">
            <div class="dcf-form-group">
                <label for="dcf-email">
                    <?php _e('Correo Electrónico', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
                </label>
                <input 
                    type="email" 
                    id="dcf-email" 
                    name="email" 
                    class="dcf-input" 
                    required
                    placeholder="<?php esc_attr_e('tu@email.com', 'dolibarr-contact-form'); ?>"
                />
                <span class="dcf-error-message"></span>
            </div>
            
            <div class="dcf-form-group">
                <label for="dcf-telefono">
                    <?php _e('Teléfono Móvil', 'dolibarr-contact-form'); ?> <span class="dcf-required">*</span>
                </label>
                <input 
                    type="tel" 
                    id="dcf-telefono" 
                    name="telefono" 
                    class="dcf-input" 
                    required
                    placeholder="<?php esc_attr_e('+34 600 000 000', 'dolibarr-contact-form'); ?>"
                />
                <span class="dcf-error-message"></span>
            </div>
        </div>
        
        <div class="dcf-form-group">
            <label for="dcf-empresa">
                <?php _e('Empresa', 'dolibarr-contact-form'); ?> <span class="dcf-optional"><?php _e('(opcional)', 'dolibarr-contact-form'); ?></span>
            </label>
            <input 
                type="text" 
                id="dcf-empresa" 
                name="empresa" 
                class="dcf-input"
                placeholder="<?php esc_attr_e('Nombre de tu empresa', 'dolibarr-contact-form'); ?>"
            />
            <span class="dcf-error-message"></span>
        </div>
        
        <div class="dcf-form-group">
            <label for="dcf-mensaje">
                <?php _e('Mensaje', 'dolibarr-contact-form'); ?> <span class="dcf-optional"><?php _e('(opcional)', 'dolibarr-contact-form'); ?></span>
            </label>
            <textarea 
                id="dcf-mensaje" 
                name="mensaje" 
                class="dcf-textarea" 
                rows="5"
                placeholder="<?php esc_attr_e('¿En qué podemos ayudarte?', 'dolibarr-contact-form'); ?>"
            ></textarea>
            <span class="dcf-error-message"></span>
        </div>
        
        <div class="dcf-form-group">
            <button type="submit" class="dcf-submit-btn">
                <span class="dcf-btn-text"><?php _e('Enviar', 'dolibarr-contact-form'); ?></span>
                <span class="dcf-btn-loader" style="display: none;">
                    <span class="dcf-spinner"></span>
                    <?php _e('Enviando...', 'dolibarr-contact-form'); ?>
                </span>
            </button>
        </div>
        
        <div class="dcf-form-note">
            <span class="dcf-required">*</span> <?php _e('Campos obligatorios', 'dolibarr-contact-form'); ?>
        </div>
    </form>
</div>
