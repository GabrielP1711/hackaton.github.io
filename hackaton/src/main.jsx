import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import { Route, Routes } from "react-router-dom";
import Login from "./Login";

createRoot(document.getElementById("root")).render(
  <StrictMode>
    <Login></Login>
    <BrowserRouter>
      <Routes>
        {/*  <Route path="/login" element={<Login />} />*/}
        {/*  <Route path="/formSolicitud" element={<Abogados />} />*/}
        {/* <Route path="/form" element={<Clientes />} />*/}
        {/* <Route path="/administrador" element={<Administrador />} />*/}
        {/* <Route path="/especialidades" element={<Especialidades />} />*/}
      </Routes>
    </BrowserRouter>
  </StrictMode>
);
