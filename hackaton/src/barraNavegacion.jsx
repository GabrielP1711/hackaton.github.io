import React, { useState } from "react";
import { Link } from "react-router-dom";
import "./Estilos/styles.css";

function BarraNavegacion() {
  const [menuOpen, setMenuOpen] = useState(false);

  const toggleMenu = () => {
    setMenuOpen(!menuOpen);
  };

  return (
    <>
      <header className="header">
        <div className="container">
          <Link to="/" className="logo">
            INVENTARIO
          </Link>

          {/* Navegación de escritorio */}
          <nav className="desktop-nav">
            <ul>
              <li>
                <Link to="/solicitud">Solicitud</Link>
              </li>
              <li>
                <Link to="/devolucion">Devolución</Link>
              </li>
              <li>
                <Link to="/central">Central</Link>
              </li>
            </ul>
          </nav>

          {/* Botón hamburguesa para móvil */}
          <div className="hamburger" onClick={toggleMenu}>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </header>

      {/* Menú móvil */}
      <div className={`mobile-menu ${menuOpen ? "active" : ""}`}>
        <ul>
          <li>
            <Link to="/solicitud" onClick={toggleMenu}>
              Solicitud
            </Link>
          </li>
          <li>
            <Link to="/devolucion" onClick={toggleMenu}>
              Devolución
            </Link>
          </li>
          <li>
            <Link to="/central" onClick={toggleMenu}>
              Central
            </Link>
          </li>
        </ul>
      </div>
    </>
  );
}

export default BarraNavegacion;
