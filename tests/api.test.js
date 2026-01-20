const request = require('supertest');
const app = require('../src/index');

describe('Plugin ERP - API de Contactos', () => {
  
  describe('GET /', () => {
    it('debe devolver información de la API', async () => {
      const response = await request(app).get('/');
      expect(response.status).toBe(200);
      expect(response.body).toHaveProperty('message');
      expect(response.body).toHaveProperty('endpoints');
    });
  });

  describe('GET /api/contactos', () => {
    it('debe listar todos los contactos', async () => {
      const response = await request(app).get('/api/contactos');
      expect(response.status).toBe(200);
      expect(response.body).toHaveProperty('total');
      expect(response.body).toHaveProperty('contactos');
      expect(Array.isArray(response.body.contactos)).toBe(true);
    });
  });

  describe('GET /api/contactos/:id', () => {
    it('debe obtener un contacto existente', async () => {
      const response = await request(app).get('/api/contactos/1');
      expect(response.status).toBe(200);
      expect(response.body).toHaveProperty('id', 1);
      expect(response.body).toHaveProperty('nombre');
      expect(response.body).toHaveProperty('email');
    });

    it('debe devolver 404 para un contacto inexistente', async () => {
      const response = await request(app).get('/api/contactos/9999');
      expect(response.status).toBe(404);
      expect(response.body).toHaveProperty('error');
    });
  });

  describe('POST /api/contactos', () => {
    it('debe crear un nuevo contacto', async () => {
      const nuevoContacto = {
        nombre: 'Carlos López',
        email: 'carlos.lopez@example.com',
        telefono: '+34 600 111 222',
        empresa: 'Nueva Empresa SA',
        cargo: 'Desarrollador'
      };

      const response = await request(app)
        .post('/api/contactos')
        .send(nuevoContacto);

      expect(response.status).toBe(201);
      expect(response.body).toHaveProperty('id');
      expect(response.body.nombre).toBe(nuevoContacto.nombre);
      expect(response.body.email).toBe(nuevoContacto.email);
    });

    it('debe rechazar contacto sin nombre', async () => {
      const contactoInvalido = {
        email: 'test@example.com'
      };

      const response = await request(app)
        .post('/api/contactos')
        .send(contactoInvalido);

      expect(response.status).toBe(400);
      expect(response.body).toHaveProperty('error');
    });

    it('debe rechazar contacto sin email', async () => {
      const contactoInvalido = {
        nombre: 'Test Usuario'
      };

      const response = await request(app)
        .post('/api/contactos')
        .send(contactoInvalido);

      expect(response.status).toBe(400);
      expect(response.body).toHaveProperty('error');
    });
  });

  describe('PUT /api/contactos/:id', () => {
    it('debe actualizar un contacto existente', async () => {
      const actualizacion = {
        nombre: 'Juan Pérez Actualizado',
        cargo: 'CTO'
      };

      const response = await request(app)
        .put('/api/contactos/1')
        .send(actualizacion);

      expect(response.status).toBe(200);
      expect(response.body.nombre).toBe(actualizacion.nombre);
      expect(response.body.cargo).toBe(actualizacion.cargo);
    });

    it('debe devolver 404 al actualizar contacto inexistente', async () => {
      const response = await request(app)
        .put('/api/contactos/9999')
        .send({ nombre: 'Test' });

      expect(response.status).toBe(404);
    });
  });

  describe('DELETE /api/contactos/:id', () => {
    it('debe eliminar un contacto existente', async () => {
      const response = await request(app).delete('/api/contactos/2');
      expect(response.status).toBe(200);
      expect(response.body).toHaveProperty('message');
      expect(response.body).toHaveProperty('contacto');
    });

    it('debe devolver 404 al eliminar contacto inexistente', async () => {
      const response = await request(app).delete('/api/contactos/9999');
      expect(response.status).toBe(404);
    });
  });

  describe('Rutas no existentes', () => {
    it('debe devolver 404 para rutas no encontradas', async () => {
      const response = await request(app).get('/api/ruta-inexistente');
      expect(response.status).toBe(404);
      expect(response.body).toHaveProperty('error');
    });
  });
});
