import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import { Route, Routes } from "react-router-dom";
import Login from "./Login";
import BarraNavegacion from "./barraNavegacion";
import HomePage from "./HomePage";

createRoot(document.getElementById("root")).render(
  <StrictMode>
    <BrowserRouter>
      <BarraNavegacion />
      <Routes>
         <Route path="/" element={<HomePage />} />  
          <Route path="/login" element={<Login />} />
        {/*  <Route path="/login" element={<Login />} />*/}
        {/*  <Route path="/formSolicitud" element={<Abogados />} />*/}
        {/* <Route path="/form" element={<Clientes />} />*/}
        {/* <Route path="/administrador" element={<Administrador />} />*/}
        {/* <Route path="/especialidades" element={<Especialidades />} />*/}
      </Routes>
    </BrowserRouter>
  </StrictMode>
);
