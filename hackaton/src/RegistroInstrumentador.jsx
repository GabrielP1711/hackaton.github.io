import React, { useState } from "react";
import "./Estilos/Login.css"; // Importar los estilos existentes

function RegistroInstrumentador() {
  const [id, setId] = useState("");
  const [nombre, setNombre] = useState("");
  const [foto, setFoto] = useState(null);

  const handleSubmit = (e) => {
    e.preventDefault();

    // Crear un objeto FormData para enviar los datos, incluyendo la foto
    const formData = new FormData();
    formData.append("id", id);
    formData.append("nombre", nombre);
    formData.append("foto", foto);

    // Enviar los datos al servidor (puedes ajustar la URL según tu API)
    fetch("http://localhost/hackaton.github.io/hackaton/src/registrar_procedimiento.phphttp://localhost/hackaton.github.io/hackaton/src/registrar.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        alert("Instrumentador registrado con éxito");
        // Reiniciar los campos del formulario
        setId("");
        setNombre("");
        setFoto(null);
      })
      .catch((error) => {
        console.error("Error al registrar el instrumentador:", error);
      });
  };

  return (
    <div className="login-container">
      <h2 className="form-title">Registro de Instrumentador Quirúrgico</h2>
      <form className="login-form" onSubmit={handleSubmit}>
        {/* Campo ID */}
        <div className="form-group">
          <label htmlFor="id">ID:</label>
          <input
            type="text"
            id="id"
            className="form-control"
            value={id}
            onChange={(e) => setId(e.target.value)}
            required
          />
        </div>

        {/* Campo Nombre */}
        <div className="form-group">
          <label htmlFor="nombre">Nombre:</label>
          <input
            type="text"
            id="nombre"
            className="form-control"
            value={nombre}
            onChange={(e) => setNombre(e.target.value)}
            required
          />
        </div>

        {/* Campo Foto */}
        <div className="form-group">
          <label htmlFor="foto">Foto del Rostro:</label>
          <input
            type="file"
            id="foto"
            className="form-control"
            accept="image/*"
            onChange={(e) => setFoto(e.target.files[0])}
            required
          />
        </div>

        {/* Botón de Enviar */}
        <button type="submit" className="btn btn-primary">
          Registrar Instrumentador
        </button>
      </form>
    </div>
  );
}

export default RegistroInstrumentador;
