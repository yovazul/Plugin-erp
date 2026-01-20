# Dolibarr Contact Form - WordPress Plugin

Plugin de WordPress que integra un formulario de contacto con el ERP Dolibarr versión 22.0.4.

## Descripción

Este plugin permite crear contactos y empresas (terceros) en Dolibarr directamente desde un formulario de WordPress. Los datos enviados se sincronizan automáticamente con tu instancia de Dolibarr mediante su API REST.

## Características

- ✅ Formulario de contacto personalizable con campos: nombre, apellido, email, teléfono móvil, empresa y mensaje
- ✅ Integración completa con la API de Dolibarr v22.0.4
- ✅ Creación automática de terceros (empresas) cuando se proporciona el campo empresa
- ✅ Vinculación automática de contactos a empresas
- ✅ Etiquetado automático de contactos sin empresa como "contacto desde la web"
- ✅ Asignación automática a agentes comerciales
- ✅ Validación de datos del lado del cliente y del servidor
- ✅ Manejo robusto de errores con mensajes amigables para el usuario
- ✅ Sistema de logging completo para prevención de pérdida de datos
- ✅ Rotación automática de archivos de log
- ✅ Shortcode para incluir el formulario en cualquier página o entrada
- ✅ Diseño responsive y accesible
- ✅ Envío mediante AJAX sin recarga de página

## Requisitos

- WordPress 5.0 o superior
- PHP 7.0 o superior
- Dolibarr 22.0.4 o compatible
- Acceso a la API REST de Dolibarr con una API Key válida

## Instalación

1. Descarga o clona este repositorio en el directorio `wp-content/plugins/` de tu instalación de WordPress
2. Ve al panel de administración de WordPress
3. Navega a **Plugins > Plugins instalados**
4. Busca "Dolibarr Contact Form" y haz clic en **Activar**

## Configuración

### Configuración de la API de Dolibarr

El plugin está preconfigurado con los siguientes parámetros:

- **URL Base**: `https://intetron.co/plataforma/api/index.php`
- **API Key**: `5P3cw77r825RIXwE8eGuZIj4dmcPF0kK`

Estos valores están definidos en el archivo `includes/class-dolibarr-api.php`. Si necesitas cambiarlos, edita las propiedades `$api_base_url` y `$api_key` de la clase `DCF_Dolibarr_API`.

### Permisos necesarios en Dolibarr

Asegúrate de que la API Key tenga permisos para:
- Crear terceros (thirdparties)
- Crear contactos (contacts)
- Crear y asignar categorías
- Asignar representantes comerciales

## Uso

### Shortcode

Para incluir el formulario en cualquier página o entrada, utiliza el shortcode:

```
[dolibarr_contact_form]
```

También puedes personalizar el título:

```
[dolibarr_contact_form title="Contáctanos"]
```

### Flujo de trabajo

#### Con empresa
Cuando el usuario completa el campo "empresa":
1. Se crea un tercero (empresa) en Dolibarr
2. Se crea el contacto vinculado a ese tercero
3. Se añade la nota pública si se proporcionó un mensaje

#### Sin empresa
Cuando el usuario deja vacío el campo "empresa":
1. Se crea solo el contacto en Dolibarr
2. Se añade automáticamente la etiqueta "contacto desde la web"
3. Se intenta asignar un agente comercial (ID por defecto: 1)

## Sistema de Logging

El plugin registra todas las operaciones importantes en un archivo de log ubicado en:

```
wp-content/dolibarr-contact-form.log
```

### Características del logging:
- Registro de todas las peticiones a la API
- Registro de respuestas y errores
- Rotación automática cuando el archivo supera 5MB
- Mantiene los últimos 5 backups

### Ver los logs

Los logs se pueden ver directamente desde el servidor o mediante FTP. Cada entrada incluye:
- Timestamp
- Nivel de log (INFO, WARNING, ERROR)
- Mensaje descriptivo

## Estructura de archivos

```
dolibarr-contact-form/
├── dolibarr-contact-form.php    # Archivo principal del plugin
├── includes/
│   ├── class-dolibarr-api.php   # Clase para integración con API
│   ├── class-form-handler.php   # Manejador de formularios
│   └── class-logger.php         # Sistema de logging
├── templates/
│   └── contact-form.php         # Template HTML del formulario
├── assets/
│   ├── css/
│   │   └── style.css            # Estilos del formulario
│   └── js/
│       └── script.js            # Validación y AJAX
├── .gitignore
└── README.md
```

## Validación de datos

### Validación del lado del cliente (JavaScript)
- Campos obligatorios: nombre, apellido, email, teléfono
- Formato de email válido
- Teléfono con mínimo 9 dígitos
- Mínimo 2 caracteres para nombre y apellido

### Validación del lado del servidor (PHP)
- Sanitización de todos los inputs
- Validación de email con `is_email()`
- Validación de longitud de campos
- Validación de formato de teléfono

## Manejo de errores

El plugin proporciona mensajes de error claros y amigables:
- Errores de validación específicos por campo
- Mensajes de error de API traducidos
- Fallback genérico para errores inesperados
- Todos los errores se registran en el log

## Seguridad

- Prevención de acceso directo a archivos PHP
- Verificación de nonce en peticiones AJAX
- Sanitización de todos los inputs del usuario
- Escape de outputs en templates
- Validación de permisos de WordPress

### Nota Importante sobre Seguridad

**API Key y Configuración**: Por defecto, este plugin tiene la API Key y la URL base hardcodeadas según los requisitos del proyecto. Para un entorno de producción, considera:

1. **Proteger los logs**: Los archivos de log pueden contener información sensible. Asegúrate de que no sean accesibles públicamente.
   
2. **Modo Debug**: En producción, establece `DCF_DEBUG_MODE` a `false` en el archivo principal para reducir el logging de datos sensibles:
   ```php
   define('DCF_DEBUG_MODE', false);
   ```

3. **Permisos del archivo de log**: Asegúrate de que el archivo de log tenga permisos apropiados (recomendado: 640 o 600)

4. **Rotación de logs**: El plugin rotará automáticamente los logs cuando superen 5MB, pero considera implementar una limpieza programada de backups antiguos.

5. **HTTPS**: Asegúrate de que tanto tu sitio WordPress como tu instalación de Dolibarr usen HTTPS para proteger los datos en tránsito.

## Desarrollo

### Personalización

Para personalizar el plugin:

1. **Estilos**: Edita `assets/css/style.css`
2. **Comportamiento JS**: Edita `assets/js/script.js`
3. **Template HTML**: Edita `templates/contact-form.php`
4. **Lógica API**: Edita `includes/class-dolibarr-api.php`

### Hooks disponibles

El plugin utiliza los siguientes hooks de WordPress:
- `wp_enqueue_scripts`: Para cargar estilos y scripts
- `wp_ajax_dcf_submit_form`: Para manejar envíos con usuario autenticado
- `wp_ajax_nopriv_dcf_submit_form`: Para manejar envíos sin autenticación

## Solución de problemas

### El formulario no se envía
1. Verifica que JavaScript esté habilitado en el navegador
2. Comprueba la consola del navegador en busca de errores
3. Revisa el archivo de log para más detalles

### Error de conexión con Dolibarr
1. Verifica que la URL base sea correcta
2. Confirma que la API Key sea válida
3. Asegúrate de que el servidor de WordPress pueda conectarse a Dolibarr
4. Revisa los permisos de la API Key en Dolibarr

### Los contactos no se crean
1. Revisa el archivo de log para ver el error específico
2. Verifica que la API Key tenga los permisos necesarios
3. Confirma que Dolibarr esté operativo y accesible

## Licencia

GPL v2 or later

## Soporte

Para reportar bugs o solicitar nuevas características, por favor abre un issue en el repositorio de GitHub.

## Créditos

Desarrollado para integración con Dolibarr ERP versión 22.0.4.
