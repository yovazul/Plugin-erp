# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

## [1.0.0] - 2026-01-20

### Añadido
- Formulario de contacto con campos: nombre, apellido, email, teléfono móvil, empresa y mensaje
- Integración completa con API de Dolibarr v22.0.4
- Creación automática de terceros (empresas) en Dolibarr
- Vinculación automática de contactos a empresas
- Etiquetado automático "contacto desde la web" para contactos sin empresa
- Asignación automática a agentes comerciales
- Validación de datos del lado del cliente (JavaScript)
- Validación de datos del lado del servidor (PHP)
- Sistema de logging completo con rotación automática de archivos
- Manejo robusto de errores con mensajes amigables
- Shortcode `[dolibarr_contact_form]` para incluir el formulario en páginas/entradas
- Diseño responsive y accesible
- Envío del formulario mediante AJAX sin recarga de página
- Protección mediante nonce para peticiones AJAX
- Sanitización y escape de datos para seguridad
- Prevención de acceso directo a archivos PHP
- Estilos CSS personalizables
- JavaScript con validación en tiempo real
- Documentación completa en README.md y USAGE.md
- Script de verificación de integridad del plugin

### Configuración inicial
- API Key: `5P3cw77r825RIXwE8eGuZIj4dmcPF0kK`
- URL Base: `https://intetron.co/plataforma/api/index.php`
- Agente comercial por defecto: ID 1

### Archivos principales
- `dolibarr-contact-form.php`: Archivo principal del plugin
- `includes/class-dolibarr-api.php`: Clase de integración con API
- `includes/class-form-handler.php`: Manejador de formularios
- `includes/class-logger.php`: Sistema de logging
- `templates/contact-form.php`: Template HTML del formulario
- `assets/css/style.css`: Estilos del formulario
- `assets/js/script.js`: JavaScript para validación y AJAX
