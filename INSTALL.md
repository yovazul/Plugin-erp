# Instalación - Dolibarr Contact Form

## Guía Rápida de Instalación

### Método 1: Instalación Manual

1. **Descarga el plugin**
   ```bash
   git clone https://github.com/yovazul/Plugin-erp.git dolibarr-contact-form
   ```

2. **Copia a WordPress**
   - Copia la carpeta completa a `wp-content/plugins/dolibarr-contact-form/`
   - O sube el archivo ZIP desde el panel de WordPress (Plugins > Añadir nuevo > Subir plugin)

3. **Activa el plugin**
   - Ve a WordPress Admin > Plugins > Plugins instalados
   - Busca "Dolibarr Contact Form"
   - Haz clic en "Activar"

4. **Usa el shortcode**
   - Edita cualquier página o entrada
   - Agrega el shortcode: `[dolibarr_contact_form]`
   - Publica la página

### Método 2: Subir ZIP

1. **Crea un archivo ZIP**
   ```bash
   cd Plugin-erp
   zip -r dolibarr-contact-form.zip . -x "*.git*" "*.DS_Store" "verify-plugin.sh"
   ```

2. **Sube a WordPress**
   - WordPress Admin > Plugins > Añadir nuevo
   - Click "Subir plugin"
   - Selecciona el archivo ZIP
   - Click "Instalar ahora"
   - Click "Activar plugin"

## Requisitos del Sistema

### Requisitos Mínimos
- **WordPress**: 5.0 o superior
- **PHP**: 7.0 o superior
- **MySQL**: 5.6 o superior (cualquier versión compatible con WordPress)

### Requisitos de Servidor
- **Memoria PHP**: Mínimo 64MB (recomendado 128MB+)
- **Tiempo de ejecución**: Mínimo 30 segundos
- **Extensiones PHP requeridas**:
  - curl o allow_url_fopen habilitado
  - json
  - mbstring

### Requisitos de Dolibarr
- **Versión**: Dolibarr 22.0.4 o compatible
- **API REST**: Habilitada
- **API Key**: Con permisos para:
  - Crear terceros (thirdparties)
  - Crear contactos (contacts)
  - Gestionar categorías
  - Asignar representantes comerciales

## Verificación de la Instalación

### 1. Verifica que el plugin esté activo
```bash
# Desde el directorio de WordPress
wp plugin list
# Busca "dolibarr-contact-form" con status "active"
```

### 2. Verifica la estructura de archivos
```bash
cd wp-content/plugins/dolibarr-contact-form
./verify-plugin.sh
```

Deberías ver todas las verificaciones en verde (✓).

### 3. Prueba el shortcode
1. Crea una nueva página de prueba
2. Agrega el shortcode: `[dolibarr_contact_form]`
3. Publica y visita la página
4. Deberías ver el formulario de contacto

### 4. Verifica la conectividad con Dolibarr
1. Rellena y envía el formulario de prueba
2. Verifica el archivo de log: `wp-content/dolibarr-contact-form.log`
3. Busca errores de conexión o API
4. Verifica en Dolibarr que se creó el contacto

## Configuración Post-Instalación

### 1. Ajustar Permisos de Archivos
```bash
# Recomendado para el archivo de log
chmod 640 wp-content/dolibarr-contact-form.log
chown www-data:www-data wp-content/dolibarr-contact-form.log
```

### 2. Configurar Modo Debug (Opcional)
Para habilitar logging completo en desarrollo:

Edita `dolibarr-contact-form.php`:
```php
define('DCF_DEBUG_MODE', true); // Solo para desarrollo
```

**⚠️ IMPORTANTE**: Mantén en `false` en producción.

### 3. Proteger Archivo de Log
Crea un archivo `.htaccess` en `wp-content/`:
```apache
<Files "dolibarr-contact-form.log*">
    Order Allow,Deny
    Deny from all
</Files>
```

### 4. Verificar Configuración de Dolibarr

En Dolibarr, verifica:
1. **Home > Setup > Modules**
   - API/Webservices esté habilitado
   
2. **Home > Setup > Security**
   - API Key configurada y activa
   - Permisos correctos asignados

3. **Home > Setup > Dictionaries**
   - Categorías de contacto habilitadas

## Solución de Problemas de Instalación

### El plugin no aparece en la lista
- Verifica que la carpeta esté en `wp-content/plugins/`
- Verifica los permisos del directorio (755)
- Verifica que el archivo principal sea `dolibarr-contact-form.php`

### Error al activar el plugin
- Verifica la versión de PHP (mínimo 7.0)
- Revisa el log de errores de WordPress
- Verifica que no haya conflictos con otros plugins

### El formulario no se muestra
- Verifica que el shortcode esté escrito correctamente
- Limpia la caché del sitio
- Verifica que no haya errores JavaScript en la consola
- Desactiva temporalmente otros plugins para detectar conflictos

### Error de conexión con Dolibarr
1. **Verifica la URL base**
   - Debe ser accesible desde el servidor de WordPress
   - Prueba con curl desde el servidor:
   ```bash
   curl -H "DOLAPIKEY: 5P3cw77r825RIXwE8eGuZIj4dmcPF0kK" \
        https://intetron.co/plataforma/api/index.php/thirdparties
   ```

2. **Verifica la API Key**
   - Debe estar activa en Dolibarr
   - Debe tener los permisos necesarios

3. **Verifica el firewall**
   - El servidor de WordPress debe poder acceder al servidor de Dolibarr
   - Puertos HTTPS (443) deben estar abiertos

### No se crean los contactos
1. Revisa el log: `wp-content/dolibarr-contact-form.log`
2. Verifica los permisos de la API Key en Dolibarr
3. Verifica que Dolibarr esté operativo
4. Prueba crear un contacto manualmente desde la API

## Desinstalación

### Desactivar el plugin
1. WordPress Admin > Plugins > Plugins instalados
2. Busca "Dolibarr Contact Form"
3. Click "Desactivar"

### Eliminar el plugin
1. Después de desactivar, click "Eliminar"
2. Confirma la eliminación

### Limpieza manual
```bash
# Eliminar archivos del plugin
rm -rf wp-content/plugins/dolibarr-contact-form/

# Eliminar logs
rm wp-content/dolibarr-contact-form.log*
```

## Actualizaciones

Para actualizar el plugin:
1. Descarga la nueva versión
2. Desactiva el plugin actual
3. Reemplaza los archivos en `wp-content/plugins/dolibarr-contact-form/`
4. Activa el plugin de nuevo
5. Verifica que todo funcione correctamente

## Soporte

Para ayuda adicional:
- Revisa la documentación completa en [README.md](README.md)
- Consulta la guía de uso en [USAGE.md](USAGE.md)
- Revisa las notas de seguridad en [SECURITY.md](SECURITY.md)
- Abre un issue en GitHub para reportar bugs

---

**¿Listo para comenzar?**
Una vez instalado, simplemente agrega `[dolibarr_contact_form]` a cualquier página y ¡tu formulario estará listo!
