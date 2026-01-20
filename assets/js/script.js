/**
 * JavaScript para el formulario de contacto Dolibarr
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        const $form = $('#dcf-contact-form');
        const $submitBtn = $form.find('.dcf-submit-btn');
        const $btnText = $form.find('.dcf-btn-text');
        const $btnLoader = $form.find('.dcf-btn-loader');
        const $successMsg = $('.dcf-success');
        const $errorMsg = $('.dcf-error');

        /**
         * Validar campo individual
         */
        function validateField($field) {
            const fieldName = $field.attr('name');
            const fieldValue = $field.val().trim();
            const $formGroup = $field.closest('.dcf-form-group');
            const $errorMessage = $formGroup.find('.dcf-error-message');
            
            let isValid = true;
            let errorMessage = '';

            // Limpiar errores previos
            $formGroup.removeClass('has-error');
            $field.removeClass('dcf-error-input');
            $errorMessage.text('');

            // Validar campos obligatorios
            if ($field.prop('required') && fieldValue === '') {
                isValid = false;
                errorMessage = dcfAjax.messages.required;
            }

            // Validaciones específicas por campo
            switch(fieldName) {
                case 'nombre':
                case 'apellido':
                    if (fieldValue && fieldValue.length < 2) {
                        isValid = false;
                        errorMessage = 'Debe tener al menos 2 caracteres';
                    }
                    break;

                case 'email':
                    if (fieldValue && !isValidEmail(fieldValue)) {
                        isValid = false;
                        errorMessage = dcfAjax.messages.invalid_email;
                    }
                    break;

                case 'telefono':
                    if (fieldValue && !isValidPhone(fieldValue)) {
                        isValid = false;
                        errorMessage = dcfAjax.messages.invalid_phone;
                    }
                    break;
            }

            // Mostrar error si no es válido
            if (!isValid) {
                $formGroup.addClass('has-error');
                $field.addClass('dcf-error-input');
                $errorMessage.text(errorMessage);
            }

            return isValid;
        }

        /**
         * Validar email
         */
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        /**
         * Validar teléfono
         */
        function isValidPhone(phone) {
            // Eliminar espacios, guiones y paréntesis
            const cleanPhone = phone.replace(/[\s\-()]/g, '');
            // Debe tener al menos 9 dígitos
            return /^[\+]?[0-9]{9,}$/.test(cleanPhone);
        }

        /**
         * Validar todo el formulario
         */
        function validateForm() {
            let isValid = true;

            $form.find('input[required], textarea[required]').each(function() {
                if (!validateField($(this))) {
                    isValid = false;
                }
            });

            return isValid;
        }

        /**
         * Mostrar mensaje
         */
        function showMessage(type, message) {
            hideMessages();
            
            if (type === 'success') {
                $successMsg.text(message).fadeIn();
            } else {
                $errorMsg.text(message).fadeIn();
            }

            // Hacer scroll al mensaje
            $('html, body').animate({
                scrollTop: $('.dcf-messages').offset().top - 100
            }, 500);
        }

        /**
         * Ocultar mensajes
         */
        function hideMessages() {
            $successMsg.fadeOut();
            $errorMsg.fadeOut();
        }

        /**
         * Resetear formulario
         */
        function resetForm() {
            $form[0].reset();
            $form.find('.dcf-form-group').removeClass('has-error');
            $form.find('input, textarea').removeClass('dcf-error-input');
            $form.find('.dcf-error-message').text('');
        }

        /**
         * Habilitar/deshabilitar botón de envío
         */
        function toggleSubmitButton(disabled) {
            $submitBtn.prop('disabled', disabled);
            
            if (disabled) {
                $btnText.hide();
                $btnLoader.show();
            } else {
                $btnText.show();
                $btnLoader.hide();
            }
        }

        // Validación en tiempo real al perder el foco
        $form.find('input, textarea').on('blur', function() {
            if ($(this).val().trim() !== '') {
                validateField($(this));
            }
        });

        // Limpiar errores al escribir
        $form.find('input, textarea').on('input', function() {
            const $formGroup = $(this).closest('.dcf-form-group');
            $formGroup.removeClass('has-error');
            $(this).removeClass('dcf-error-input');
        });

        // Manejar envío del formulario
        $form.on('submit', function(e) {
            e.preventDefault();

            // Ocultar mensajes previos
            hideMessages();

            // Validar formulario
            if (!validateForm()) {
                showMessage('error', 'Por favor, corrige los errores antes de enviar el formulario.');
                return;
            }

            // Deshabilitar botón
            toggleSubmitButton(true);

            // Preparar datos
            const formData = {
                action: 'dcf_submit_form',
                nonce: dcfAjax.nonce,
                nombre: $('#dcf-nombre').val().trim(),
                apellido: $('#dcf-apellido').val().trim(),
                email: $('#dcf-email').val().trim(),
                telefono: $('#dcf-telefono').val().trim(),
                empresa: $('#dcf-empresa').val().trim(),
                mensaje: $('#dcf-mensaje').val().trim()
            };

            // Enviar via AJAX
            $.ajax({
                url: dcfAjax.ajaxurl,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    toggleSubmitButton(false);

                    if (response.success) {
                        showMessage('success', response.data.message);
                        resetForm();
                    } else {
                        showMessage('error', response.data.message || dcfAjax.messages.error);
                    }
                },
                error: function(xhr, status, error) {
                    toggleSubmitButton(false);
                    console.error('Error AJAX:', error);
                    showMessage('error', dcfAjax.messages.error);
                }
            });
        });
    });

})(jQuery);
