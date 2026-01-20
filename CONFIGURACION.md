# 🔧 Cómo Configurar el Plugin (Guía Visual)

## 📍 Dónde Encontrar la Configuración

Después de instalar y **activar** el plugin, hay **DOS formas** de acceder a la configuración:

### Opción 1: Desde la Lista de Plugins

1. Ve a **Plugins → Plugins instalados**
2. Busca **"Dolibarr Contact Form"**
3. Verás un enlace **"Configuración"** debajo del nombre del plugin
4. Click en **"Configuración"**

### Opción 2: Desde el Menú de Ajustes

1. En el menú lateral izquierdo, ve a **Ajustes**
2. Busca la opción **"Dolibarr Form"**
3. Click en **"Dolibarr Form"**

---

## ⚙️ Configurar la API de Dolibarr

Una vez en la página de configuración, verás un formulario con estos campos:

### 1. URL de Dolibarr

**Ingresa:**
```
https://intetron.co/plataforma
```

⚠️ **IMPORTANTE**: 
- No incluyas `/api/index.php/explorer/`
- No termines con `/`
- Solo la URL base de tu instalación

### 2. API Key de Dolibarr

**Ingresa:**
```
5P3cw77r825RIXwE8eGuZIj4dmcPF0kK
```

### 3. Guardar Configuración

1. Click en el botón azul **"Guardar Configuración"**
2. Verás el mensaje: "Configuración guardada correctamente"
3. La página mostrará el estado:
   ```
   ✓ Estado: Plugin configurado correctamente.
   ```

### 4. Probar la Conexión

1. Baja hasta la sección **"Probar Conexión"**
2. Click en el botón **"Probar Conexión"**
3. Debe aparecer: **"✓ Conexión exitosa con Dolibarr"**

---

## 🚨 Si no ves el menú "Dolibarr Form"

### Solución 1: Desactivar y Reactivar el Plugin

1. Ve a **Plugins → Plugins instalados**
2. Busca **"Dolibarr Contact Form"**
3. Click en **"Desactivar"**
4. Espera 2 segundos
5. Click en **"Activar"**
6. Refresca la página
7. Ahora deberías ver **Ajustes → Dolibarr Form**

### Solución 2: Verificar el Archivo Principal

El plugin debe tener esta estructura de carpetas:

```
wp-content/plugins/
└── Plugin-erp/  o  dolibarr-contact-form/
    ├── dolibarr-contact-form.php  ← Este es el archivo principal
    ├── admin/
    ├── assets/
    ├── includes/
    └── templates/
```

Si el archivo `dolibarr-contact-form.php` no está en la raíz de la carpeta del plugin, WordPress no lo reconocerá.

### Solución 3: Verificar Permisos de Usuario

Debes estar conectado como **Administrador** para ver las opciones de configuración.

---

## 📝 Configuración Rápida (Copiar y Pegar)

Para tu caso específico, copia y pega exactamente esto:

**URL de Dolibarr:**
```
https://intetron.co/plataforma
```

**API Key:**
```
5P3cw77r825RIXwE8eGuZIj4dmcPF0kK
```

---

## ✅ Después de Configurar

1. Crea una **nueva página** en WordPress
2. En el editor, agrega este shortcode:
   ```
   [dolibarr_contact_form]
   ```
3. **Publica** la página
4. **Visita** la página para ver el formulario
5. **Prueba** enviando un contacto de prueba

Los datos se enviarán automáticamente a tu Dolibarr en:
`https://intetron.co/plataforma`

---

## 🆘 ¿Aún no aparece el menú?

Si después de desactivar y reactivar el plugin no aparece el menú:

1. Verifica que el plugin esté **activado** (debe tener un fondo azul claro en la lista)
2. Verifica que seas **Administrador**
3. Intenta acceder directamente a:
   ```
   https://tu-sitio-wordpress.com/wp-admin/options-general.php?page=dolibarr-contact-form
   ```
4. Si da error 404, hay un problema con la instalación del plugin

**Reinstalación Limpia:**
1. Desinstala completamente el plugin
2. Descarga la última versión desde GitHub
3. Instala de nuevo
4. Activa
5. Configura con los datos de arriba
