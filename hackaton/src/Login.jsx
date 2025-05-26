import { useState } from "react";
import React from "react";
import "./Estilos/Login.css";

function Login() {
  return (
    <>
      <div className="login-container">
        <div className="login-form">
          <p>Id:</p>
          <input type="text" placeholder="Enter your ID" className="IdUSer" />
          <p>Password:</p>
          <input
            type="password"
            placeholder="Enter your password"
            className="PassUser"
          />
          <br />
          <button type="button" className="Bentrar">
            Entrar
          </button>
        </div>
      </div>
    </>
  );
}

export default Login;
