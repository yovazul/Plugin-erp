const express = require('express');
const router = express.Router();

// Simulación de base de datos en memoria
let contactos = [
  {
    id: 1,
    nombre: 'Juan Pérez',
    email: 'juan.perez@example.com',
    telefono: '+34 600 123 456',
    empresa: 'Tech Solutions SA',
    cargo: 'Director de TI',
    fechaCreacion: new Date().toISOString()
  },
  {
    id: 2,
    nombre: 'María García',
    email: 'maria.garcia@example.com',
    telefono: '+34 600 789 012',
    empresa: 'Innovate Corp',
    cargo: 'Gerente de Compras',
    fechaCreacion: new Date().toISOString()
  }
];

let nextId = 3;

// GET - Listar todos los contactos
router.get('/', (req, res) => {
  res.json({
    total: contactos.length,
    contactos: contactos
  });
});

// GET - Obtener un contacto por ID
router.get('/:id', (req, res) => {
  const id = parseInt(req.params.id);
  const contacto = contactos.find(c => c.id === id);
  
  if (!contacto) {
    return res.status(404).json({ error: 'Contacto no encontrado' });
  }
  
  res.json(contacto);
});

// POST - Crear un nuevo contacto
router.post('/', (req, res) => {
  const { nombre, email, telefono, empresa, cargo } = req.body;
  
  // Validación básica
  if (!nombre || !email) {
    return res.status(400).json({ 
      error: 'Los campos nombre y email son obligatorios' 
    });
  }
  
  // Validar email único
  if (contactos.some(c => c.email === email)) {
    return res.status(400).json({ 
      error: 'Ya existe un contacto con ese email' 
    });
  }
  
  const nuevoContacto = {
    id: nextId++,
    nombre,
    email,
    telefono: telefono || '',
    empresa: empresa || '',
    cargo: cargo || '',
    fechaCreacion: new Date().toISOString()
  };
  
  contactos.push(nuevoContacto);
  res.status(201).json(nuevoContacto);
});

// PUT - Actualizar un contacto
router.put('/:id', (req, res) => {
  const id = parseInt(req.params.id);
  const index = contactos.findIndex(c => c.id === id);
  
  if (index === -1) {
    return res.status(404).json({ error: 'Contacto no encontrado' });
  }
  
  const { nombre, email, telefono, empresa, cargo } = req.body;
  
  // Validar email único (excepto el contacto actual)
  if (email && contactos.some(c => c.email === email && c.id !== id)) {
    return res.status(400).json({ 
      error: 'Ya existe otro contacto con ese email' 
    });
  }
  
  const contactoActualizado = {
    ...contactos[index],
    nombre: nombre || contactos[index].nombre,
    email: email || contactos[index].email,
    telefono: telefono !== undefined ? telefono : contactos[index].telefono,
    empresa: empresa !== undefined ? empresa : contactos[index].empresa,
    cargo: cargo !== undefined ? cargo : contactos[index].cargo,
    fechaModificacion: new Date().toISOString()
  };
  
  contactos[index] = contactoActualizado;
  res.json(contactoActualizado);
});

// DELETE - Eliminar un contacto
router.delete('/:id', (req, res) => {
  const id = parseInt(req.params.id);
  const index = contactos.findIndex(c => c.id === id);
  
  if (index === -1) {
    return res.status(404).json({ error: 'Contacto no encontrado' });
  }
  
  const contactoEliminado = contactos.splice(index, 1)[0];
  res.json({ 
    message: 'Contacto eliminado correctamente',
    contacto: contactoEliminado 
  });
});

module.exports = router;
