import React from "react";
import { Link } from "react-router-dom";
import "./Estilos/styles.css";

function BarraNavegacion() {
  return (
    <>
      <header className="header">
        <div className="container">
          <div className="logo">Gestion Quirurgica</div>
          <nav className="desktop-nav">
            <ul>
              <li>
                <Link to="/">Inicio</Link>
              </li>
              <li>
                <Link to="/solicitud">Solicitud</Link>
              </li>
              <li>
                <Link to="/#">Devolución</Link>
              </li>
              <li>
                <Link to="/#">Central</Link>
              </li>
              <li>
                <Link to="/login">Iniciar sesion</Link>
              </li>
              <li>
                <Link to="/registro">Registrar</Link>
              </li>
              <li>
                <Link to="/instrumentos">Registrar Instrumentos</Link> 
              </li>
            </ul>
          </nav>
        </div>
      </header>
    </>
  );
}

export default BarraNavegacion;
