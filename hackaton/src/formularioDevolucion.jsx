import React, { useState } from "react";
import "./Estilos/styles.css"; // Asegúrate de que los estilos estén correctamente importados

const FormularioDevolucion = () => {
  const [idInstrumentador, setIdInstrumentador] = useState("");
  const [idBandeja, setIdBandeja] = useState("");
  const [anotaciones, setAnotaciones] = useState("");

  const handleSubmit = (e) => {
    e.preventDefault();
    // Aquí puedes manejar el envío del formulario
    console.log("ID Instrumentador:", idInstrumentador);
    console.log("ID Bandeja:", idBandeja);
    console.log("Anotaciones:", anotaciones);
  };

  return (
    <div className="form-container">
      <h2 className="form-title">Registrar Solicitud</h2>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="idInstrumentador">ID del Instrumentador:</label>
          <input
            type="text"
            id="idInstrumentador"
            className="form-control"
            value={idInstrumentador}
            onChange={(e) => setIdInstrumentador(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label htmlFor="idBandeja">ID de la Bandeja:</label>
          <input
            type="text"
            id="idBandeja"
            className="form-control"
            value={idBandeja}
            onChange={(e) => setIdBandeja(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label htmlFor="anotaciones">Anotaciones:</label>
          <textarea
            id="anotaciones"
            className="form-control"
            value={anotaciones}
            onChange={(e) => setAnotaciones(e.target.value)}
            rows="4"
            required
          ></textarea>
        </div>
        <div className="form-actions">
          <button type="submit" className="btn btn-primary">
            Registrar
          </button>
          <button type="reset" className="btn btn-secondary">
            Limpiar
          </button>
        </div>
      </form>
    </div>
  );
};

export default FormularioDevolucion;
