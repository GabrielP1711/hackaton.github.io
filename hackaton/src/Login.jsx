import { useState } from "react";
import React from "react";
import { Link } from "react-router-dom";
import "./Estilos/Login.css";

function Login() {
  return (
    <>
      <div className="login-container">
        <div className="login-form">
          <p>Id:</p>
          <input type="text" placeholder="Ingrese Id" className="IdUSer" />
          <p>Password:</p>
          <input
            type="password"
            placeholder="Ingrese contraseña"
            className="PassUser"
          />
          <br />
          <button type="button" className="Bentrar">
            Entrar
          </button>
          <div className = 'register-link'> 
          <Link to="/registro">Registrate aquí</Link>
        </div>
        </div>
      </div>
    </>
  );
}

export default Login;
