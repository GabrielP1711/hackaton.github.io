import { useState } from "react";
import React from "react";
import "./Estilos/Elogin.css";
function Login() {
  return (
    <>
      <div className="login-container">
        <h1>Login</h1>
        <div className="container-form">
          <p>Id:</p>
          <input type="text" placeholder="Enter your ID" />
          <p>Password:</p>
          <input type="password" placeholder="Enter your password" />
          <br />
          <button type="button">Entrar</button>
        </div>
      </div>
    </>
  );
}

export default Login;
