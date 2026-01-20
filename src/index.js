const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
require('dotenv').config();

const contactosRouter = require('./routes/contactos');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Rutas
app.get('/', (req, res) => {
  res.json({
    message: 'Plugin ERP - API de Contactos',
    version: '1.0.0',
    endpoints: {
      'GET /': 'Información de la API',
      'GET /api/contactos': 'Listar todos los contactos',
      'GET /api/contactos/:id': 'Obtener un contacto por ID',
      'POST /api/contactos': 'Crear un nuevo contacto',
      'PUT /api/contactos/:id': 'Actualizar un contacto',
      'DELETE /api/contactos/:id': 'Eliminar un contacto'
    }
  });
});

app.use('/api/contactos', contactosRouter);

// Manejo de errores 404
app.use((req, res) => {
  res.status(404).json({ error: 'Ruta no encontrada' });
});

// Manejo de errores global
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ error: 'Error interno del servidor' });
});

// Iniciar servidor solo si no está en modo test
if (process.env.NODE_ENV !== 'test') {
  app.listen(PORT, () => {
    console.log(`🚀 Servidor corriendo en http://localhost:${PORT}`);
    console.log(`📝 Documentación disponible en http://localhost:${PORT}/`);
  });
}

module.exports = app;
