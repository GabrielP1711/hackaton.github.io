import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Estilos/styles.css';

const FormularioProcedimiento = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    nombre: '',
    descripcion: '',
    cedulaPaciente: '',
    fecha: '',
  });

  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      // Aquí irá la lógica para enviar los datos al backend
      console.log('Datos del formulario:', formData);
      // Redirigir al usuario después de un registro exitoso
      navigate('/');
    } catch (err) {
      setError('Error al registrar el procedimiento');
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prevState => ({
      ...prevState,
      [name]: value
    }));
  };

  return (
    <div className="container">
      <div className="form-container">
        <h2>Registro de Procedimiento</h2>
        {error && <div className="alert alert-error">{error}</div>}
        
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="nombre">Nombre del Procedimiento:</label>
            <input
              type="text"
              id="nombre"
              name="nombre"
              value={formData.nombre}
              onChange={handleChange}
              required
              className="form-control"
            />
          </div>

          <div className="form-group">
            <label htmlFor="descripcion">Descripción:</label>
            <textarea
              id="descripcion"
              name="descripcion"
              value={formData.descripcion}
              onChange={handleChange}
              required
              className="form-control"
              rows="4"
            />
          </div>

          <div className="form-group">
            <label htmlFor="cedulaPaciente">Cédula del Paciente:</label>
            <input
              type="text"
              id="cedulaPaciente"
              name="cedulaPaciente"
              value={formData.cedulaPaciente}
              onChange={handleChange}
              required
              className="form-control"
              pattern="[0-9]*"
            />
          </div>

          <div className="form-group">
            <label htmlFor="fecha">Fecha del Procedimiento:</label>
            <input
              type="datetime-local"
              id="fecha"
              name="fecha"
              value={formData.fecha}
              onChange={handleChange}
              required
              className="form-control"
            />
          </div>

          <div className="form-actions">
            <button type="submit" className="btn btn-primary">
              Registrar Procedimiento
            </button>
            <button 
              type="button" 
              className="btn btn-secondary"
              onClick={() => navigate('/')}
            >
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default FormularioProcedimiento;