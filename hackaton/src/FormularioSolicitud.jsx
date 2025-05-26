import React, { useState, useEffect } from "react";

function FormularioSolicitud() {
  const [procedimientos, setProcedimientos] = useState([]);
  const [herramientasPorProcedimiento, setHerramientasPorProcedimiento] =
    useState({});
  const [herramientasExtras, setHerramientasExtras] = useState([]);
  const [instrumentosSeleccionados, setInstrumentosSeleccionados] = useState(
    []
  );
  const [herramientaSeleccionada, setHerramientaSeleccionada] = useState("");
  const [procedimientoSeleccionado, setProcedimientoSeleccionado] =
    useState("");

  // Simulación de datos desde la base de datos
  useEffect(() => {
    // Simulación de procedimientos y sus herramientas
    setProcedimientos(["Cirugía General", "Ortopedia", "Cardiología"]);
    setHerramientasPorProcedimiento({
      "Cirugía General": ["Bisturí", "Pinzas", "Sutura"],
      Ortopedia: ["Martillo Ortopédico", "Sierra", "Tornillos"],
      Cardiología: ["Catéter", "Marcapasos", "Estetoscopio"],
    });
    setHerramientasExtras(["Guantes", "Gasas", "Tijeras"]);
  }, []);

  const agregarHerramienta = () => {
    if (herramientaSeleccionada) {
      setInstrumentosSeleccionados((prev) => [
        ...prev,
        herramientaSeleccionada,
      ]);
      setHerramientaSeleccionada(""); // Reinicia el combobox
    }
  };

  return (
    <div>
      <h2>Formulario de Solicitud</h2>

      {/* Selector de Procedimientos */}
      <div>
        <label htmlFor="procedimiento">Seleccionar Procedimiento:</label>
        <select
          id="procedimiento"
          value={procedimientoSeleccionado}
          onChange={(e) => {
            setProcedimientoSeleccionado(e.target.value);
            setInstrumentosSeleccionados([]); // Reinicia los instrumentos seleccionados al cambiar de procedimiento
          }}
        >
          <option value="">-- Seleccionar --</option>
          {procedimientos.map((procedimiento, index) => (
            <option key={index} value={procedimiento}>
              {procedimiento}
            </option>
          ))}
        </select>
      </div>

      {/* Selector de Herramientas del Procedimiento */}
      {procedimientoSeleccionado && (
        <div>
          <h3>Herramientas del Procedimiento</h3>
          <ul>
            {herramientasPorProcedimiento[procedimientoSeleccionado]?.map(
              (herramienta, index) => (
                <li key={index}>{herramienta}</li>
              )
            )}
          </ul>
        </div>
      )}

      {/* Selector de Herramientas Extras */}
      <div>
        <label htmlFor="herramienta">Seleccionar Herramienta Extra:</label>
        <select
          id="herramienta"
          value={herramientaSeleccionada}
          onChange={(e) => setHerramientaSeleccionada(e.target.value)}
        >
          <option value="">-- Seleccionar --</option>
          {herramientasExtras.map((herramienta, index) => (
            <option key={index} value={herramienta}>
              {herramienta}
            </option>
          ))}
        </select>
        <button onClick={agregarHerramienta}>Agregar Herramienta</button>
      </div>

      {/* Div para mostrar los instrumentos seleccionados */}
      <div>
        <h3>Instrumentos Seleccionados</h3>
        <ul>
          {instrumentosSeleccionados.map((instrumento, index) => (
            <li key={index}>{instrumento}</li>
          ))}
        </ul>
      </div>
    </div>
  );
}

export default FormularioSolicitud;
