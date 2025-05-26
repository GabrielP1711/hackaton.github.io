import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import "./Estilos/styles.css";

function FormularioSolicitud() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    id_instrumentador: "",
    procedimiento_id: "",
    fecha_solicitud: new Date().toISOString().split('T')[0],
    fecha_procedimiento: "",
    hora_procedimiento: "",
    notas_adicionales: "",
  });
  const [procedimientos, setProcedimientos] = useState([]);
  const [bandejas, setBandejas] = useState([]);
  const [bandejasSeleccionadas, setBandejasSeleccionadas] = useState([]);
  const [instrumentosExtras, setInstrumentosExtras] = useState([]);
  const [instrumentosSeleccionados, setInstrumentosSeleccionados] = useState([]);
  const [instrumentoSeleccionado, setInstrumentoSeleccionado] = useState("");
  const [instrumentadores, setInstrumentadores] = useState([]);
  const [cargando, setCargando] = useState(false);
  const [error, setError] = useState("");

  // Cargar datos iniciales al montar el componente
  useEffect(() => {
    const cargarDatos = async () => {
      setCargando(true);
      setError("");

      try {
        // Cargar procedimientos
        const resProcedimientos = await fetch("http://localhost/hackaton.github.io/hackaton/src/obtenerinstrumentadores.php");
        const dataProcedimientos = await resProcedimientos.json();
        
        // Cargar instrumentadores
        const resInstrumentadores = await fetch("http://localhost/hackaton.github.io/hackaton/src/obtenerinstrumentadores.php");
        const dataInstrumentadores = await resInstrumentadores.json();

        // Cargar instrumentos extras
        const resInstrumentos = await fetch("http://localhost/hackaton.github.io/hackaton/src/obtenerinstrumentadores.php");
        const dataInstrumentos = await resInstrumentos.json();

        if (dataProcedimientos.status === 'success') {
          setProcedimientos(dataProcedimientos.data);
        } else {
          // Datos de respaldo en caso de error
          setProcedimientos([
            { id: 1, nombre: "Cirugía General" },
            { id: 2, nombre: "Ortopedia" },
            { id: 3, nombre: "Cardiología" }
          ]);
        }

        if (dataInstrumentadores.status === 'success') {
          setInstrumentadores(dataInstrumentadores.data);
        } else {
          // Datos de respaldo
          setInstrumentadores([
            { id: "123", nombre: "Dr. García" },
            { id: "456", nombre: "Dra. Rodríguez" }
          ]);
        }

        if (dataInstrumentos.status === 'success') {
          setInstrumentosExtras(dataInstrumentos.data);
        } else {
          // Datos de respaldo
          setInstrumentosExtras([
            { id: 1, nombre: "Guantes" },
            { id: 2, nombre: "Gasas" },
            { id: 3, nombre: "Tijeras" },
            { id: 4, nombre: "Sutura" }
          ]);
        }
      } catch (err) {
        console.error("Error al cargar datos:", err);
        setError("Error al cargar los datos. Por favor, inténtelo de nuevo.");
        
        // Cargar datos de respaldo en caso de error
        setProcedimientos([
          { id: 1, nombre: "Cirugía General" },
          { id: 2, nombre: "Ortopedia" },
          { id: 3, nombre: "Cardiología" }
        ]);
        setInstrumentadores([
          { id: "123", nombre: "Dr. García" },
          { id: "456", nombre: "Dra. Rodríguez" }
        ]);
        setInstrumentosExtras([
          { id: 1, nombre: "Guantes" },
          { id: 2, nombre: "Gasas" },
          { id: 3, nombre: "Tijeras" },
          { id: 4, nombre: "Sutura" }
        ]);
      } finally {
        setCargando(false);
      }
    };

    cargarDatos();
  }, []);

  // Cargar bandejas cuando se selecciona un procedimiento
  useEffect(() => {
    if (formData.procedimiento_id) {
      const cargarBandejas = async () => {
        try {
          const res = await fetch(
            `http://localhost/hackaton.github.io/hackaton/src/backend/obtener_bandejas.php?procedimiento_id=${formData.procedimiento_id}`
          );
          const data = await res.json();
          
          if (data.status === 'success') {
            setBandejas(data.data);
          } else {
            // Datos de respaldo
            setBandejas([
              { id: 1, nombre: "Bandeja Básica", procedimiento_id: formData.procedimiento_id },
              { id: 2, nombre: "Bandeja Especializada", procedimiento_id: formData.procedimiento_id }
            ]);
          }
        } catch (err) {
          console.error("Error al cargar bandejas:", err);
          // Datos de respaldo
          setBandejas([
            { id: 1, nombre: "Bandeja Básica", procedimiento_id: formData.procedimiento_id },
            { id: 2, nombre: "Bandeja Especializada", procedimiento_id: formData.procedimiento_id }
          ]);
        }
      };

      cargarBandejas();
    }
  }, [formData.procedimiento_id]);

  // Manejar cambios en el formulario
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });

    // Reiniciar bandejas seleccionadas al cambiar de procedimiento
    if (name === 'procedimiento_id') {
      setBandejasSeleccionadas([]);
      setInstrumentosSeleccionados([]);
    }
  };

  // Agregar/quitar bandeja de la selección
  const toggleBandeja = (bandejaId) => {
    if (bandejasSeleccionadas.includes(bandejaId)) {
      setBandejasSeleccionadas(bandejasSeleccionadas.filter(id => id !== bandejaId));
    } else {
      setBandejasSeleccionadas([...bandejasSeleccionadas, bandejaId]);
    }
  };

  // Agregar instrumento extra a la selección
  const agregarInstrumento = () => {
    if (instrumentoSeleccionado) {
      const instrumento = instrumentosExtras.find(item => item.id.toString() === instrumentoSeleccionado);
      if (instrumento && !instrumentosSeleccionados.some(item => item.id === instrumento.id)) {
        setInstrumentosSeleccionados([...instrumentosSeleccionados, {...instrumento, cantidad: 1}]);
      }
      setInstrumentoSeleccionado("");
    }
  };

  // Eliminar instrumento de la selección
  const eliminarInstrumento = (id) => {
    setInstrumentosSeleccionados(
      instrumentosSeleccionados.filter(instrumento => instrumento.id !== id)
    );
  };

  // Actualizar la cantidad de un instrumento
  const actualizarCantidad = (id, cantidad) => {
    setInstrumentosSeleccionados(
      instrumentosSeleccionados.map(instrumento => 
        instrumento.id === id ? {...instrumento, cantidad} : instrumento
      )
    );
  };

  // Enviar formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    setCargando(true);
    setError("");

    if (!formData.id_instrumentador || !formData.procedimiento_id || !formData.fecha_procedimiento) {
      setError("Por favor complete todos los campos obligatorios");
      setCargando(false);
      return;
    }

    if (bandejasSeleccionadas.length === 0 && instrumentosSeleccionados.length === 0) {
      setError("Por favor seleccione al menos una bandeja o instrumento");
      setCargando(false);
      return;
    }

    const solicitudData = {
      ...formData,
      bandejas: bandejasSeleccionadas,
      instrumentos: instrumentosSeleccionados,
    };

    try {
      const res = await fetch("http://localhost/hackaton.github.io/hackaton/src/backend/guardar_solicitud.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(solicitudData),
      });

      const data = await res.json();

      if (data.status === 'success') {
        alert("Solicitud guardada exitosamente");
        navigate("/"); // Redirigir a otra página si es necesario
      } else {
        setError("Error al guardar la solicitud. Inténtelo de nuevo.");
      }
    } catch (err) {
      console.error("Error al enviar la solicitud:", err);
      setError("Error al enviar la solicitud. Por favor, inténtelo de nuevo.");
    } finally {
      setCargando(false);
    }
  };

  return (
    <div className="container solicitud-container">
      <h2 className="form-heading">Formulario de Solicitud de Procedimiento</h2>

      {error && <div className="error">{error}</div>}

      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="id_instrumentador">Instrumentador:</label>
          <select
            id="id_instrumentador"
            name="id_instrumentador"
            value={formData.id_instrumentador}
            onChange={handleChange}
            className="form-control"
            required
          >
            <option value="">-- Seleccionar Instrumentador --</option>
            {instrumentadores.map((instrumentador) => (
              <option key={instrumentador.id} value={instrumentador.id}>
                {instrumentador.nombre}
              </option>
            ))}
          </select>
        </div>

        <div className="form-group">
          <label htmlFor="procedimiento_id">Procedimiento:</label>
          <select
            id="procedimiento_id"
            name="procedimiento_id"
            value={formData.procedimiento_id}
            onChange={handleChange}
            className="form-control"
            required
          >
            <option value="">-- Seleccionar Procedimiento --</option>
            {procedimientos.map((procedimiento) => (
              <option key={procedimiento.id} value={procedimiento.id}>
                {procedimiento.nombre}
              </option>
            ))}
          </select>
        </div>

        <div className="form-group">
          <label htmlFor="fecha_procedimiento">Fecha del Procedimiento:</label>
          <input
            type="date"
            id="fecha_procedimiento"
            name="fecha_procedimiento"
            value={formData.fecha_procedimiento}
            onChange={handleChange}
            className="form-control"
            required
          />
        </div>

        <div className="form-group">
          <label htmlFor="hora_procedimiento">Hora del Procedimiento:</label>
          <input
            type="time"
            id="hora_procedimiento"
            name="hora_procedimiento"
            value={formData.hora_procedimiento}
            onChange={handleChange}
            className="form-control"
            required
          />
        </div>

        <div className="form-group">
          <label htmlFor="notas_adicionales">Notas Adicionales:</label>
          <textarea
            id="notas_adicionales"
            name="notas_adicionales"
            value={formData.notas_adicionales}
            onChange={handleChange}
            className="form-control"
          />
        </div>

        <div className="form-group">
          <h3>Bandejas Disponibles</h3>
          {cargando ? (
            <p>Cargando bandejas...</p>
          ) : (
            bandejas.map((bandeja) => (
              <div key={bandeja.id}>
                <input
                  type="checkbox"
                  id={`bandeja_${bandeja.id}`}
                  checked={bandejasSeleccionadas.includes(bandeja.id)}
                  onChange={() => toggleBandeja(bandeja.id)}
                />
                <label htmlFor={`bandeja_${bandeja.id}`}>{bandeja.nombre}</label>
              </div>
            ))
          )}
        </div>

        <div className="form-group">
          <h3>Instrumentos Extras</h3>
          <select
            id="instrumentoSeleccionado"
            value={instrumentoSeleccionado}
            onChange={(e) => setInstrumentoSeleccionado(e.target.value)}
            className="form-control"
          >
            <option value="">-- Seleccionar Instrumento Extra --</option>
            {instrumentosExtras.map((instrumento) => (
              <option key={instrumento.id} value={instrumento.id}>
                {instrumento.nombre}
              </option>
            ))}
          </select>
          <button className="btn btn-secondary" type="button" onClick={agregarInstrumento}>
            Agregar Instrumento
          </button>
        </div>

        <div className="form-group">
          <h3>Instrumentos Seleccionados</h3>
          <ul>
            {instrumentosSeleccionados.map((instrumento) => (
              <li key={instrumento.id}>
                {instrumento.nombre}{" "}
                <button className="btn btn-secondary" type="button" onClick={() => eliminarInstrumento(instrumento.id)}>
                  Eliminar
                </button>
                <input
                  type="number"
                  min="1"
                  value={instrumento.cantidad}
                  onChange={(e) => actualizarCantidad(instrumento.id, parseInt(e.target.value))}
                />
              </li>
            ))}
          </ul>
        </div>

        <div className="form-group">
          <button className="btn btn-secondary" type="submit" disabled={cargando}>
            {cargando ? "Guardando..." : "Guardar Solicitud"}
          </button>
        </div>
      </form>
    </div>
  );
}

export default FormularioSolicitud;
