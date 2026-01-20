# 🔧 Guía de Solución de Problemas

## Error: "No se ha facilitado una URL válida"

Este error ocurre cuando la configuración no es correcta. Sigue estos pasos:

### ✅ Paso 1: Verificar la URL de Dolibarr

1. Ve a **Ajustes → Dolibarr Form** en WordPress
2. Verifica que la **URL de Dolibarr** esté completa y correcta

**Formato correcto:**
```
https://tudominio.com/dolibarr
```

**❌ Formato incorrecto:**
```
https://tudominio.com/dolibarr/    (con barra final)
tudominio.com/dolibarr             (sin https://)
http://localhost                    (solo si es local)
```

### ✅ Paso 2: Verificar la API Key

1. En Dolibarr, ve a tu perfil de usuario
2. Click en la pestaña **API Keys**
3. Genera una nueva API Key si es necesario
4. Copia la clave completa
5. Pégala en WordPress en **Ajustes → Dolibarr Form**

### ✅ Paso 3: Habilitar la API en Dolibarr

1. Inicia sesión en Dolibarr como **administrador**
2. Ve a **Inicio → Configuración → Módulos/Aplicaciones**
3. Busca **"API / Servicios Web"**
4. Actívalo si no está activado
5. Guarda los cambios

### ✅ Paso 4: Verificar Permisos del Usuario

El usuario que generó la API Key debe tener permisos para:
- ✅ Crear terceros (third parties)
- ✅ Crear contactos
- ✅ Crear tickets (opcional)

Para verificar:
1. En Dolibarr, ve a **Usuarios y Grupos → Usuario**
2. Edita el usuario
3. En la pestaña **Permisos**, verifica que tenga:
   - **Terceros**: Crear/modificar
   - **Contactos**: Crear/modificar
   - **Tickets**: Crear/modificar (opcional)

### ✅ Paso 5: Probar la Conexión

1. Ve a **Ajustes → Dolibarr Form**
2. Click en el botón **"Probar Conexión"**
3. Si falla, revisa el mensaje de error

### 🔍 Paso 6: Revisar Logs de Error

Si el problema persiste, revisa los logs de WordPress:

**En tu hosting:**
- Busca el archivo `wp-content/debug.log`
- Busca líneas que contengan "DCF" o "Dolibarr"

**Para habilitar logging en WordPress:**

Edita `wp-config.php` y añade:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Los logs mostrarán información como:
```
DCF API Request: POST https://tudominio.com/dolibarr/api/index.php/thirdparties
DCF API Body: {"name":"...","email":"..."}
```

## Otros Problemas Comunes

### El formulario no se muestra

**Causa**: El shortcode no está correctamente escrito  
**Solución**: Asegúrate de usar exactamente: `[dolibarr_contact_form]`

### El botón se queda cargando indefinidamente

**Causa**: Error de JavaScript o AJAX  
**Solución**: 
1. Abre la consola del navegador (F12)
2. Busca errores en la pestaña "Console"
3. Revisa la pestaña "Network" para ver la respuesta AJAX

### Los datos no aparecen en Dolibarr

**Causa**: El contacto se creó pero no lo encuentras  
**Solución**:
1. En Dolibarr, ve a **Terceros → Lista de terceros**
2. Ve a **Contactos/Direcciones → Lista de contactos**
3. Busca por el email o nombre enviado desde el formulario

### Error SSL/Certificado

**Causa**: Certificado SSL autofirmado o inválido  
**Solución**: En el código, cambia en `class-dolibarr-api.php`:
```php
'sslverify' => false  // Solo para desarrollo local
```

⚠️ **NO uses esto en producción**

## 🆘 ¿Aún tienes problemas?

1. Exporta los logs de error de WordPress
2. Toma capturas de pantalla de:
   - La configuración en WordPress
   - El mensaje de error completo
   - Los logs de error si los tienes
3. Abre un issue en: https://github.com/yovazul/Plugin-erp/issues

---

## 📊 Checklist de Verificación

Antes de pedir ayuda, verifica:

- [ ] La URL de Dolibarr es correcta y termina sin `/`
- [ ] La API Key está copiada correctamente
- [ ] El módulo API/Servicios Web está activado en Dolibarr
- [ ] El usuario tiene permisos para crear terceros y contactos
- [ ] La prueba de conexión es exitosa
- [ ] Los logs de error no muestran problemas obvios
- [ ] El formulario aparece correctamente en la página
- [ ] No hay errores en la consola del navegador
