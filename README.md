# Dolibarr Contact Form - Plugin para WordPress

Plugin de WordPress que integra un formulario de contacto con Dolibarr ERP. Captura información de contactos desde tu sitio web y los envía automáticamente a tu sistema Dolibarr.

## 🚀 Características

- ✅ Formulario de contacto personalizable
- ✅ Integración directa con API de Dolibarr
- ✅ Creación automática de terceros (empresas) y contactos
- ✅ Creación opcional de tickets de soporte
- ✅ Validación de datos en tiempo real
- ✅ Diseño responsive y moderno
- ✅ Panel de administración intuitivo
- ✅ Mensajes personalizables
- ✅ Soporte para campo de empresa (opcional)

## 📋 Requisitos

- WordPress >= 5.0
- PHP >= 7.4
- Dolibarr ERP con API REST habilitada
- API Key de Dolibarr

## 🔧 Instalación

### Método 1: Desde WordPress (recomendado)

1. Descarga el archivo ZIP del plugin desde GitHub
2. En WordPress, ve a **Plugins → Añadir nuevo → Subir plugin**
3. Selecciona el archivo ZIP descargado
4. Haz clic en **Instalar ahora**
5. Activa el plugin

### Método 2: Manual

1. Descarga el plugin desde GitHub
2. Descomprime el archivo ZIP
3. Sube la carpeta `dolibarr-contact-form` a `/wp-content/plugins/`
4. Ve a **Plugins** en WordPress y activa el plugin

## ⚙️ Configuración de Dolibarr

Antes de usar el plugin, asegúrate de tener Dolibarr configurado:

### 1. Habilitar la API REST en Dolibarr

1. Inicia sesión en Dolibarr como administrador
2. Ve a **Inicio → Configuración → Módulos/Aplicaciones**
3. Busca y activa el módulo **API/Servicios Web**

### 2. Generar API Key

1. Ve a tu perfil de usuario en Dolibarr
2. Accede a la pestaña **API Keys**
3. Genera una nueva API Key
4. Guarda la clave generada

### 3. Configurar el Plugin en WordPress

1. En WordPress, ve a **Ajustes → Dolibarr Form**
2. Ingresa los siguientes datos:
   - **URL de Dolibarr**: La URL completa de tu instalación (ej: `https://tudominio.com/dolibarr`)
   - **API Key**: La API Key generada en el paso anterior
3. Personaliza los mensajes de éxito y error
4. Haz clic en **Guardar Configuración**
5. Usa el botón **Probar Conexión** para verificar que todo funcione

## 🎯 Uso del Plugin

### Agregar el Formulario a una Página

Simplemente añade el shortcode en cualquier página o entrada:

```
[dolibarr_contact_form]
```

### Ocultar el Campo de Empresa

Si no necesitas capturar el nombre de la empresa:

```
[dolibarr_contact_form show_company="no"]
```

### Campos del Formulario

El formulario captura los siguientes datos:

- **Nombre de la Empresa** (opcional)
- **Nombre** (requerido)
- **Apellido** (requerido)
- **Correo Electrónico** (requerido)
- **Teléfono** (requerido)
- **Mensaje** (requerido - se guarda como nota pública)

## 📊 ¿Qué se Crea en Dolibarr?

Cuando se envía el formulario:

1. **Si se proporciona nombre de empresa**: Se crea un tercero (third party) en Dolibarr
2. **Siempre**: Se crea un contacto con todos los datos
3. **Opcionalmente**: Se crea un ticket de soporte con el mensaje

## 🧪 Pruebas

### Probar la Conexión con Dolibarr

Usa el botón de prueba en la página de configuración del plugin en WordPress.

### Probar el Formulario

1. Crea una nueva página en WordPress
2. Añade el shortcode `[dolibarr_contact_form]`
3. Publica la página
4. Completa y envía el formulario
5. Verifica en Dolibarr que se creó el contacto/tercero

## 🔧 Solución de Problemas

### El formulario no envía datos

1. Verifica que la URL de Dolibarr sea correcta (sin barra final)
2. Confirma que la API Key sea válida
3. Asegúrate de que el módulo API REST esté activado en Dolibarr
4. Revisa los permisos del usuario que generó la API Key

### Error de conexión

- Verifica que tu servidor WordPress pueda acceder a la URL de Dolibarr
- Si Dolibarr usa HTTPS con certificado autofirmado, puede haber problemas de SSL
- Revisa los logs de PHP en WordPress para más detalles

### Los datos no aparecen en Dolibarr

- Verifica los permisos del usuario de la API Key en Dolibarr
- Asegúrate de que tenga permisos para crear terceros, contactos y tickets

## 📁 Estructura del Plugin

```
dolibarr-contact-form/
├── admin/
│   └── settings-page.php        # Página de configuración
├── assets/
│   ├── css/
│   │   └── style.css           # Estilos del formulario
│   └── js/
│       └── form.js             # JavaScript del formulario
├── includes/
│   ├── class-dolibarr-api.php  # Clase para comunicación con API
│   └── form-handler.php        # Procesamiento del formulario
├── templates/
│   └── contact-form.php        # Template del formulario
├── dolibarr-contact-form.php   # Archivo principal del plugin
└── README.md                   # Este archivo
```

## 🔐 Seguridad

- Todas las entradas son sanitizadas y validadas
- Se usa nonce de WordPress para proteger contra CSRF
- Las API Keys se almacenan de forma segura en la base de datos de WordPress
- Validación de emails en el frontend y backend
- Protección contra inyección SQL mediante funciones nativas de WordPress

## 🛠️ Tecnologías Utilizadas

- **WordPress** - CMS
- **PHP** >= 7.4
- **jQuery** - Librería JavaScript
- **Dolibarr API REST** - API del ERP

## 📝 Personalización

### Modificar Estilos

Edita el archivo [assets/css/style.css](assets/css/style.css) para personalizar la apariencia del formulario.

### Añadir Campos Personalizados

1. Modifica [templates/contact-form.php](templates/contact-form.php) para añadir campos
2. Actualiza [includes/form-handler.php](includes/form-handler.php) para procesar los nuevos campos
3. Modifica [includes/class-dolibarr-api.php](includes/class-dolibarr-api.php) para enviar los datos a Dolibarr

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'Añadir nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📄 Licencia

GPL v2 or later

## 👤 Autor

**YovaZul**
- GitHub: [@yovazul](https://github.com/yovazul)

## 📞 Soporte

Si tienes problemas o preguntas:
- Abre un [issue en GitHub](https://github.com/yovazul/Plugin-erp/issues)
- Consulta la documentación de [Dolibarr API](https://wiki.dolibarr.org/index.php/Module_Web_Services_API_REST_(developer))

---

⭐ Si este plugin te fue útil, considera darle una estrella en GitHub
- **Nodemon** - Auto-reload en desarrollo

## 📝 Licencia

ISC
