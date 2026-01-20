# Plugin ERP - Gestión de Contactos

Plugin API REST para gestión de contactos en sistemas ERP.

## 🚀 Características

- ✅ API REST completa para gestión de contactos
- ✅ CRUD (Crear, Leer, Actualizar, Eliminar)
- ✅ Validación de datos
- ✅ Tests automatizados con Jest
- ✅ Documentación de endpoints

## 📋 Requisitos

- Node.js >= 14.x
- npm o yarn

## 🔧 Instalación

```bash
# Instalar dependencias
npm install

# Copiar archivo de configuración
cp .env.example .env
```

## 🎯 Uso

### Iniciar servidor en modo desarrollo

```bash
npm run dev
```

### Iniciar servidor en modo producción

```bash
npm start
```

El servidor estará disponible en `http://localhost:3000`

## 📚 Endpoints de la API

### Información de la API
```
GET /
```

### Listar todos los contactos
```
GET /api/contactos
```

### Obtener un contacto por ID
```
GET /api/contactos/:id
```

### Crear un nuevo contacto
```
POST /api/contactos
Content-Type: application/json

{
  "nombre": "Juan Pérez",
  "email": "juan.perez@example.com",
  "telefono": "+34 600 123 456",
  "empresa": "Tech Solutions SA",
  "cargo": "Director de TI"
}
```

### Actualizar un contacto
```
PUT /api/contactos/:id
Content-Type: application/json

{
  "nombre": "Juan Pérez Actualizado",
  "cargo": "CTO"
}
```

### Eliminar un contacto
```
DELETE /api/contactos/:id
```

## 🧪 Pruebas

### Ejecutar todos los tests

```bash
npm test
```

### Ejecutar tests en modo watch

```bash
npm run test:watch
```

### Probar con curl

```bash
# Listar contactos
curl http://localhost:3000/api/contactos

# Crear contacto
curl -X POST http://localhost:3000/api/contactos \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test Usuario",
    "email": "test@example.com",
    "telefono": "+34 600 999 888",
    "empresa": "Test Corp",
    "cargo": "Tester"
  }'

# Obtener contacto por ID
curl http://localhost:3000/api/contactos/1

# Actualizar contacto
curl -X PUT http://localhost:3000/api/contactos/1 \
  -H "Content-Type: application/json" \
  -d '{"cargo": "Senior Tester"}'

# Eliminar contacto
curl -X DELETE http://localhost:3000/api/contactos/1
```

## 📁 Estructura del Proyecto

```
Plugin-erp/
├── src/
│   ├── index.js              # Punto de entrada de la aplicación
│   └── routes/
│       └── contactos.js      # Rutas de la API de contactos
├── tests/
│   └── api.test.js          # Tests de la API
├── .env.example             # Ejemplo de configuración
├── .gitignore              # Archivos ignorados por git
├── jest.config.js          # Configuración de Jest
├── package.json            # Dependencias del proyecto
└── README.md               # Este archivo
```

## 🔐 Variables de Entorno

Crea un archivo `.env` basado en `.env.example`:

```
PORT=3000
NODE_ENV=development
```

## 🛠️ Tecnologías Utilizadas

- **Express.js** - Framework web
- **CORS** - Manejo de políticas de origen cruzado
- **Body Parser** - Parsing de peticiones HTTP
- **Jest** - Framework de testing
- **Supertest** - Testing de APIs HTTP
- **Nodemon** - Auto-reload en desarrollo

## 📝 Licencia

ISC
