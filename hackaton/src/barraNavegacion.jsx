import React from "react";
import "./Estilos/styles.css";

function BarraNavegacion() {
  return (
    <>
      <header className="header">
        <div className="container">
          <div className="logo">Gestion quirurgica</div>
          <nav className="desktop-nav">
            <ul>
              <li>
                <span className="navbar-link">Solicitud</span>
              </li>
              <li>
                <span className="navbar-link">Devolución</span>
              </li>
              <li>
                <span className="navbar-link">Central</span>
              </li>
            </ul>
          </nav>
        </div>
      </header>
    </>
  );
}

export default BarraNavegacion;
