# Guía de Uso - Dolibarr Contact Form

## Instalación rápida

1. Copia el directorio completo del plugin en `wp-content/plugins/dolibarr-contact-form/`
2. Activa el plugin desde el panel de WordPress (Plugins > Plugins instalados)
3. El plugin está listo para usar

## Uso del Shortcode

### Uso básico
Para agregar el formulario a cualquier página o entrada, simplemente agrega:

```
[dolibarr_contact_form]
```

### Personalización del título
Puedes personalizar el título del formulario:

```
[dolibarr_contact_form title="Contáctanos"]
```

O sin título:

```
[dolibarr_contact_form title=""]
```

## Ejemplos de Página

### Ejemplo 1: Página de Contacto Simple

```html
<!-- Contenido de la página -->
<h1>Contacto</h1>
<p>¿Tienes alguna pregunta? Rellena el siguiente formulario y nos pondremos en contacto contigo lo antes posible.</p>

[dolibarr_contact_form title="Formulario de Contacto"]

<p>También puedes llamarnos al: +34 XXX XXX XXX</p>
```

### Ejemplo 2: Página de Solicitud de Presupuesto

```html
<h1>Solicita tu Presupuesto</h1>
<p>Completa el formulario y te enviaremos un presupuesto personalizado.</p>

[dolibarr_contact_form title="Solicitud de Presupuesto"]
```

### Ejemplo 3: Formulario en Sidebar

El shortcode también puede utilizarse en widgets de texto:

1. Ve a Apariencia > Widgets
2. Agrega un widget de "Texto" o "HTML personalizado"
3. Inserta el shortcode: `[dolibarr_contact_form title="Contáctanos"]`

## Comportamiento del Formulario

### Cuando se completa el campo "Empresa"
- ✅ Se crea una empresa (tercero) en Dolibarr
- ✅ Se crea el contacto vinculado a esa empresa
- ✅ Se asocia el mensaje como nota pública

### Cuando el campo "Empresa" está vacío
- ✅ Se crea solo el contacto en Dolibarr
- ✅ Se añade automáticamente la etiqueta "contacto desde la web"
- ✅ Se intenta asignar a un agente comercial (ID: 1)

## Validaciones

El formulario valida automáticamente:
- ✅ Nombre y apellido (mínimo 2 caracteres)
- ✅ Email con formato válido
- ✅ Teléfono móvil (mínimo 9 dígitos)
- ✅ Campos obligatorios marcados con asterisco (*)

## Mensajes de Respuesta

### Éxito
Cuando el formulario se envía correctamente, se muestra:
> "Formulario enviado exitosamente. Nos pondremos en contacto contigo pronto."

### Error
Si hay algún problema, se muestra un mensaje específico:
> "Error al crear el contacto. Por favor, inténtalo de nuevo."

## Logs y Depuración

Los logs se guardan automáticamente en:
```
wp-content/dolibarr-contact-form.log
```

Este archivo contiene información detallada sobre:
- Peticiones a la API de Dolibarr
- Respuestas recibidas
- Errores encontrados
- Timestamps de todas las operaciones

## Configuración Avanzada

### Cambiar la API Key o URL Base

Para modificar la configuración de Dolibarr, edita el archivo:
```
includes/class-dolibarr-api.php
```

Busca las propiedades:
```php
private $api_base_url = 'https://intetron.co/plataforma/api/index.php';
private $api_key = '5P3cw77r825RIXwE8eGuZIj4dmcPF0kK';
```

### Cambiar el ID del Agente Comercial

Por defecto, el agente comercial tiene ID 1. Para cambiarlo, edita:
```
includes/class-form-handler.php
```

Busca la línea:
```php
$this->api->assign_sales_agent($contact_id, 1);
```

Y cambia el `1` por el ID deseado.

## Personalización Visual

### Modificar Colores y Estilos

Edita el archivo:
```
assets/css/style.css
```

Variables principales:
```css
.dcf-submit-btn {
    background-color: #007bff; /* Color del botón */
}

.dcf-required {
    color: #dc3545; /* Color de campos obligatorios */
}
```

### Modificar el Layout del Formulario

Edita el archivo:
```
templates/contact-form.php
```

Puedes modificar la estructura HTML según tus necesidades.

## Solución de Problemas Comunes

### El formulario no se muestra
- Verifica que el plugin esté activado
- Asegúrate de estar usando el shortcode correcto: `[dolibarr_contact_form]`
- Verifica que no haya errores en la consola del navegador

### Los datos no se envían a Dolibarr
1. Verifica el archivo de log en `wp-content/dolibarr-contact-form.log`
2. Confirma que la URL y API Key sean correctas
3. Asegúrate de que Dolibarr sea accesible desde tu servidor WordPress
4. Verifica los permisos de la API Key en Dolibarr

### Error de validación de campos
- Verifica que los campos obligatorios estén completos
- El email debe tener un formato válido
- El teléfono debe tener al menos 9 dígitos

### Problemas de estilo
- Verifica que no haya conflictos con el tema de WordPress
- Prueba desactivando otros plugins temporalmente
- Revisa la consola del navegador en busca de errores CSS

## Seguridad

El plugin implementa las siguientes medidas de seguridad:
- ✅ Verificación de nonce en peticiones AJAX
- ✅ Sanitización de todos los inputs
- ✅ Escape de outputs
- ✅ Validación del lado del servidor
- ✅ Prevención de acceso directo a archivos

## Compatibilidad

- ✅ WordPress 5.0+
- ✅ PHP 7.0+
- ✅ Dolibarr 22.0.4
- ✅ Responsive (funciona en móviles y tablets)
- ✅ Compatible con la mayoría de temas de WordPress

## Soporte

Para reportar problemas o solicitar ayuda:
1. Revisa primero el archivo de log
2. Verifica la documentación completa en el README.md
3. Abre un issue en el repositorio de GitHub

---

**Nota**: Este plugin requiere que tu instancia de Dolibarr tenga la API REST habilitada y configurada correctamente.
