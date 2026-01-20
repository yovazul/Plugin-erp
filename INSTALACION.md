# 📦 INSTALACIÓN DEL PLUGIN EN WORDPRESS

## Pasos para instalar

### 1️⃣ Descargar el Plugin

Ve a: https://github.com/yovazul/Plugin-erp

Click en el botón verde **"Code"** → **"Download ZIP"**

### 2️⃣ Instalar en WordPress

1. Inicia sesión en tu panel de WordPress
2. Ve a **Plugins → Añadir nuevo**
3. Click en **"Subir plugin"** (arriba)
4. Click en **"Seleccionar archivo"**
5. Selecciona el archivo ZIP descargado
6. Click en **"Instalar ahora"**
7. Una vez instalado, click en **"Activar plugin"**

### 3️⃣ Configurar el Plugin

1. Ve a **Ajustes → Dolibarr Form**
2. Completa:
   - **URL de Dolibarr**: `https://tudominio.com/dolibarr` (sin barra final)
   - **API Key**: Tu API Key de Dolibarr
3. Click en **"Guardar Configuración"**
4. Click en **"Probar Conexión"** para verificar

### 4️⃣ Añadir el Formulario

Edita cualquier página y añade:

```
[dolibarr_contact_form]
```

¡Listo! El formulario ya está funcionando.

---

## 📋 Checklist de Configuración de Dolibarr

Antes de usar el plugin, asegúrate de:

- [ ] Módulo **API/Servicios Web** activado en Dolibarr
- [ ] API Key generada desde tu perfil de usuario
- [ ] El usuario tiene permisos para crear terceros y contactos
- [ ] La URL de Dolibarr es accesible desde tu servidor WordPress

---

## ❓ Problemas Comunes

### "No se ha podido descomprimir el paquete"

✅ **Solución**: El archivo ZIP descargado está correcto. WordPress debe poder descomprimirlo sin problemas.

### "No se encontraron plugins"

✅ **Solución**: Asegúrate de que el archivo ZIP contiene el archivo principal `dolibarr-contact-form.php` en la raíz del plugin.

### El formulario no envía datos

✅ **Verifica**:
1. URL de Dolibarr correcta (sin barra final)
2. API Key válida
3. Módulo API REST activado en Dolibarr
4. Permisos del usuario en Dolibarr

---

## 🆘 Soporte

Si tienes problemas: https://github.com/yovazul/Plugin-erp/issues
