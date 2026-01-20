# ✅ Lista de Verificación Rápida

## Antes de usar el formulario, verifica:

### 1. Configuración del Plugin en WordPress

Ve a **Ajustes → Dolibarr Form** y asegúrate de que veas:

```
✓ Estado: Plugin configurado correctamente.
✓ URL: https://tudominio.com/dolibarr
✓ API Key: ************************* (25 caracteres)
```

Si ves:
```
⚠ Estado: El plugin necesita configuración.
```

Completa los campos faltantes.

### 2. Formato Correcto de la URL

**✅ URLs correctas:**
- `https://midominio.com/dolibarr`
- `https://erp.miempresa.com`
- `http://localhost/dolibarr` (solo para desarrollo local)

**❌ URLs incorrectas:**
- `https://midominio.com/dolibarr/` ← No debe terminar en `/`
- `midominio.com/dolibarr` ← Falta `https://`
- `https://midominio.com/dolibarr/htdocs` ← No incluir subdirectorios

### 3. API Key

La API Key debe:
- Tener entre 20-40 caracteres
- No tener espacios al inicio o final
- Estar activa en Dolibarr

Para generar una nueva:
1. Login en Dolibarr
2. Click en tu nombre de usuario (arriba derecha)
3. Pestaña **"API Keys"**
4. Click **"Generar"**
5. Copia la clave completa

### 4. Permisos en Dolibarr

El usuario de la API Key debe tener permisos para:
- [x] **Terceros** → Crear/Modificar
- [x] **Contactos** → Crear/Modificar
- [x] **Tickets** → Crear (opcional)

### 5. Módulo API Activo

En Dolibarr:
1. **Inicio → Configuración → Módulos/Aplicaciones**
2. Busca: **"API / Servicios Web"**
3. Debe estar **Activado** (verde)

### 6. Probar Conexión

En **Ajustes → Dolibarr Form**:
1. Click en **"Probar Conexión"**
2. Debe aparecer: `✓ Conexión exitosa con Dolibarr`

Si falla, revisa los pasos anteriores.

---

## 🐛 Si el formulario da error

### Error mostrado:

```
El plugin no está configurado correctamente.
```

**Solución:** Revisa que la URL y API Key estén guardadas correctamente.

Si eres administrador, el error mostrará:
```
URL configurada: ✓
API Key configurada: ✓
```

Si alguno muestra ✗, ese campo no está configurado.

### Ver logs de error

1. Habilita debug en WordPress (edita `wp-config.php`):
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

2. Envía el formulario de nuevo

3. Revisa el archivo: `wp-content/debug.log`

Busca líneas que digan:
```
DCF Configuration Error:
  URL: https://...
  API Key: [configurada] o [vacía]
```

---

## 📝 Checklist Pre-Uso

Marca cada item antes de usar el plugin:

- [ ] Plugin instalado y activado en WordPress
- [ ] URL de Dolibarr configurada (sin `/` al final)
- [ ] API Key configurada
- [ ] Módulo API activo en Dolibarr
- [ ] Usuario tiene permisos adecuados
- [ ] Prueba de conexión exitosa
- [ ] Formulario agregado a una página: `[dolibarr_contact_form]`
- [ ] La página se ve correctamente
- [ ] Prueba enviando el formulario

---

## 🎯 Pasos para Primera Configuración

1. **Descarga e instala** el plugin desde GitHub
2. **Activa** el plugin en WordPress
3. Ve a **Ajustes → Dolibarr Form**
4. **Copia la URL** de tu Dolibarr (sin `/` al final)
5. **Genera y copia** la API Key desde Dolibarr
6. **Guarda** la configuración
7. Click en **Probar Conexión**
8. Si es exitoso, agrega `[dolibarr_contact_form]` a una página
9. **Prueba** el formulario

---

## 🆘 Soporte

Si completaste todos los pasos y aún tienes problemas:

1. Copia el contenido de `wp-content/debug.log`
2. Haz capturas de la página de configuración
3. Abre un issue: https://github.com/yovazul/Plugin-erp/issues
