import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import { Route, Routes } from "react-router-dom";
import Login from "./Login";
import BarraNavegacion from "./barraNavegacion";
import HomePage from "./HomePage";
import FormularioProcedimiento from './FormularioProcedimiento';
import FormularioSolicitud from "./FormularioSolicitud";
import RegistroInstrumentador from "./RegistroInstrumentador";
import RegistroBandeja from "./RegistroBandeja";


createRoot(document.getElementById("root")).render(
  <StrictMode>
    <BrowserRouter>
      <BarraNavegacion />
      <Routes>
         <Route path="/" element={<HomePage />} />  
          <Route path="/login" element={<Login />} />
          <Route path="/nuevo-procedimiento" element={<FormularioProcedimiento />} />
          <Route path="/nueva-solicitud" element={<FormularioSolicitud />} />
          <Route path="/registro" element={<RegistroInstrumentador />} />
          <Route path="/instrumentos" element={<RegistroBandeja />} />
          <Route path="/solicitud" element={<FormularioSolicitud />} />


        {/*  <Route path="/login" element={<Login />} />*/}
        {/*  <Route path="/formSolicitud" element={<Abogados />} />*/}
        {/* <Route path="/form" element={<Clientes />} />*/}
        {/* <Route path="/administrador" element={<Administrador />} />*/}
        {/* <Route path="/especialidades" element={<Especialidades />} />*/}
      </Routes>
    </BrowserRouter>
  </StrictMode>
);
