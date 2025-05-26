import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import './Estilos/styles.css';

const HomePage = () => {
  const [usuario, setUsuario] = useState(null);
  const [procedimientos, setProcedimientos] = useState([]);
  const [hayAlertas, setHayAlertas] = useState(false);
  const [menuAbierto, setMenuAbierto] = useState(false);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const responseUsuario = await fetch('/api/usuario-actual');
        const dataUsuario = await responseUsuario.json();
        setUsuario(dataUsuario);

        const responseProcedimientos = await fetch('/api/procedimientos-activos');
        const dataProcedimientos = await responseProcedimientos.json();
        setProcedimientos(dataProcedimientos);
      } catch (error) {
        console.error('Error al cargar datos:', error);
      }
    };

    fetchData();
  }, []);

  return (
    <>


      <main>
        <div className="container">
          <section className="hero">
            <div className="hero-content">
              <div className="hero-text">
                <h1>Sistema de Gestión de Instrumental Quirúrgico</h1>
                <p>Bienvenido al sistema de gestión y trazabilidad de instrumental quirúrgico.</p>
              </div>
            </div>
          </section>

          <section className="services">
            <div className="services-grid">
              <div className="card">
                <div className="card-header">
                  <h5>Procedimientos Activos</h5>
                </div>
                <div className="card-body">
                  <Link to="/nuevo-procedimiento" className="btn btn-primary">
                    Nuevo Procedimiento
                  </Link>

                  {procedimientos.length === 0 ? (
                    <div className="alert">No hay procedimientos activos en este momento.</div>
                  ) : (
                    <div className="list-group">
                      {procedimientos.map((procedimiento) => (
                        <Link 
                          to={`/ver-procedimiento/${procedimiento.id}`}
                          key={procedimiento.id}
                          className="list-item"
                        >
                          <div className="list-content">
                            <h5>{procedimiento.nombre}</h5>
                            <small>{new Date(procedimiento.fecha).toLocaleString()}</small>
                          </div>
                          <p>{procedimiento.descripcion.substring(0, 100)}...</p>
                        </Link>
                      ))}
                    </div>
                  )}
                </div>
              </div>

              <div className="card">
                <div className="card-header">
                  <h5>Solicitud de Instrumental</h5>
                </div>
                <div className="card-body">
                  <p>Realice una solicitud de instrumental para un procedimiento quirúrgico.</p>
                  <Link to="/solicitar-instrumental" className="btn btn-secondary">
                    Nueva Solicitud
                  </Link>
                </div>
              </div>

              <div className="card">
                <div className="card-header">
                  <h5>Alertas del Sistema</h5>
                </div>
                <div className="card-body">
                  <div id="alertas">
                    {hayAlertas ? (
                      <div className="alert alert-danger">
                        <strong>¡Atención!</strong> Hay instrumentos faltantes en el conteo.
                        <Link to="/instrumentos-faltantes" className="alert-link">
                          Ver detalles
                        </Link>
                      </div>
                    ) : (
                      <div className="alert alert-success">
                        No hay alertas en este momento.
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </main>
    </>
  );
};

export default HomePage;