jQuery(document).ready(function($) {
    'use strict';
    
    var form = $('#dcf-contact-form');
    var submitBtn = form.find('.dcf-submit-btn');
    var messagesDiv = $('#dcf-form-messages');
    
    form.on('submit', function(e) {
        e.preventDefault();
        
        // Limpiar mensajes anteriores
        messagesDiv.html('');
        
        // Deshabilitar botón
        submitBtn.prop('disabled', true);
        var originalText = submitBtn.text();
        submitBtn.html(dcfAjax.loading + ' <span class="dcf-loading"></span>');
        
        // Obtener datos del formulario
        var formData = {
            action: 'dcf_submit_form',
            nonce: dcfAjax.nonce,
            firstname: $('#dcf-firstname').val(),
            lastname: $('#dcf-lastname').val(),
            email: $('#dcf-email').val(),
            phone: $('#dcf-phone').val(),
            message: $('#dcf-message').val(),
            company_name: $('#dcf-company-name').val() || ''
        };
        
        // Enviar vía AJAX
        $.ajax({
            url: dcfAjax.ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Mostrar mensaje de éxito
                    messagesDiv.html(
                        '<div class="dcf-message dcf-message-success">' + 
                        response.data.message + 
                        '</div>'
                    );
                    
                    // Limpiar formulario
                    form[0].reset();
                    
                    // Scroll al mensaje
                    $('html, body').animate({
                        scrollTop: messagesDiv.offset().top - 100
                    }, 500);
                } else {
                    // Mostrar mensaje de error
                    messagesDiv.html(
                        '<div class="dcf-message dcf-message-error">' + 
                        response.data.message + 
                        '</div>'
                    );
                }
            },
            error: function(xhr, status, error) {
                messagesDiv.html(
                    '<div class="dcf-message dcf-message-error">' + 
                    'Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo.' + 
                    '</div>'
                );
            },
            complete: function() {
                // Rehabilitar botón
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Validación en tiempo real
    var emailInput = $('#dcf-email');
    emailInput.on('blur', function() {
        var email = $(this).val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).css('border-color', '#e74c3c');
        } else {
            $(this).css('border-color', '#ddd');
        }
    });
});
