import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import "./Estilos/RegistroBandeja.css";

function RegistroBandeja() {
  const navigate = useNavigate();
  const [bandeja, setBandeja] = useState({
    nombre: "",
    descripcion: "",
    procedimiento_id: "",
  });
  const [instrumentos, setInstrumentos] = useState([
    { nombre: "", cantidad: 1 },
  ]);
  const [procedimientos, setProcedimientos] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  // Cargar procedimientos al montar el componente
  useEffect(() => {
    const cargarProcedimientos = async () => {
      try {
        const response = await fetch(
          "http://localhost/hackaton.github.io/hackaton/src/backend/obtener_procedimientos.php"
        );
        if (!response.ok) {
          throw new Error("Error al cargar los procedimientos");
        }
        const data = await response.json();
        setProcedimientos(data);
      } catch (error) {
        console.error("Error:", error);
        setProcedimientos([
          { id: 1, nombre: "Cirugía General" },
          { id: 2, nombre: "Ortopedia" },
          { id: 3, nombre: "Cardiología" },
        ]); // Datos de respaldo en caso de error
      }
    };

    cargarProcedimientos();
  }, []);

  // Manejar cambios en los datos de la bandeja
  const handleBandejaChange = (e) => {
    const { name, value } = e.target;
    setBandeja((prevState) => ({
      ...prevState,
      [name]: value,
    }));
  };

  // Manejar cambios en los instrumentos
  const handleInstrumentoChange = (index, field, value) => {
    const newInstrumentos = [...instrumentos];
    newInstrumentos[index][field] = value;
    setInstrumentos(newInstrumentos);
  };

  // Agregar un nuevo instrumento a la lista
  const agregarInstrumento = () => {
    setInstrumentos([...instrumentos, { nombre: "", cantidad: 1 }]);
  };

  // Eliminar un instrumento de la lista
  const eliminarInstrumento = (index) => {
    const newInstrumentos = instrumentos.filter((_, i) => i !== index);
    setInstrumentos(newInstrumentos);
  };

  // Enviar el formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    // Validaciones básicas
    if (!bandeja.nombre || !bandeja.procedimiento_id) {
      setError("El nombre de la bandeja y el procedimiento son obligatorios");
      setLoading(false);
      return;
    }

    if (instrumentos.some((inst) => !inst.nombre)) {
      setError("Todos los instrumentos deben tener nombre");
      setLoading(false);
      return;
    }

    const bandejaData = {
      ...bandeja,
      instrumentos: instrumentos,
    };

    try {
      const response = await fetch(
        "http://localhost/hackaton.github.io/hackaton/src/backend/registrar_bandeja.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(bandejaData),
        }
      );

      if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
      }

      const data = await response.json();

      if (data.status === "success") {
        alert("Bandeja registrada exitosamente");
        navigate("/bandejas"); // Redirigir a la página de bandejas
      } else {
        setError(data.message || "Error al registrar la bandeja");
      }
    } catch (err) {
      setError("Error de conexión con el servidor");
      console.error("Error:", err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <br />
      <br />
      <br />
      <div className="registro-bandeja-container">
        <h2 className="registro-title">Registro de Bandeja Quirúrgica</h2>

        {error && <div className="error-message">{error}</div>}

        <form onSubmit={handleSubmit} className="bandeja-form">
          <div className="form-section">
            <h3>Información de la Bandeja</h3>

            <div className="form-group">
              <label htmlFor="nombre">Nombre de la Bandeja:</label>
              <input
                type="text"
                id="nombre"
                name="nombre"
                value={bandeja.nombre}
                onChange={handleBandejaChange}
                placeholder="Ej: Bandeja de Cirugía General"
                className="form-control"
                required
              />
            </div>

            <div className="form-group">
              <label htmlFor="procedimiento_id">Procedimiento:</label>
              <select
                id="procedimiento_id"
                name="procedimiento_id"
                value={bandeja.procedimiento_id}
                onChange={handleBandejaChange}
                className="form-control"
                required
              >
                <option value="">-- Seleccionar Procedimiento --</option>
                {procedimientos.map((proc) => (
                  <option key={proc.id} value={proc.id}>
                    {proc.nombre}
                  </option>
                ))}
              </select>
            </div>
          </div>

          <div className="form-section">
            <h3>Instrumentos</h3>

            {instrumentos.map((instrumento, index) => (
              <div key={index} className="instrumento-item">
                <div className="instrumento-form">
                  <div className="form-group">
                    <label htmlFor={`instrumento-${index}`}>Nombre:</label>
                    <input
                      type="text"
                      id={`instrumento-${index}`}
                      value={instrumento.nombre}
                      onChange={(e) =>
                        handleInstrumentoChange(index, "nombre", e.target.value)
                      }
                      placeholder="Nombre del instrumento"
                      className="form-control"
                      required
                    />
                  </div>

                  <div className="form-group cantidad">
                    <label htmlFor={`cantidad-${index}`}>Cantidad:</label>
                    <input
                      type="number"
                      id={`cantidad-${index}`}
                      value={instrumento.cantidad}
                      onChange={(e) =>
                        handleInstrumentoChange(
                          index,
                          "cantidad",
                          parseInt(e.target.value) || 1
                        )
                      }
                      min="1"
                      className="form-control"
                      required
                    />
                  </div>
                </div>

                <button
                  type="button"
                  className="btn btn-danger"
                  onClick={() => eliminarInstrumento(index)}
                  disabled={instrumentos.length === 1}
                >
                  Eliminar
                </button>
              </div>
            ))}

            <button
              type="button"
              className="btn btn-secondary"
              onClick={agregarInstrumento}
            >
              + Agregar Instrumento
            </button>
          </div>

          <div className="form-actions">
            <button
              type="submit"
              className="btn btn-primary"
              disabled={loading}
            >
              {loading ? "Guardando..." : "Guardar Bandeja"}
            </button>
          </div>
        </form>
      </div>
    </>
  );
}

export default RegistroBandeja;
