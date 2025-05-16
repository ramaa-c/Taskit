<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <title><?= esc($titulo ?? 'TaskIt') ?></title>
<style>
/* ===== RESET Y ESTILOS BASE ===== */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    height: 100%;
}

body {
    font-family: 'Roboto Condensed', sans-serif;
    background-color: #f2f2f2;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
}
.header-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

/* ===== ESTRUCTURA PRINCIPAL ===== */
.contenedor-principal {
    display: flex;
    flex: 1;
}

.contenido-principal {
    flex-grow: 1;
    padding: 20px;
    background-color: #f2f2f2;
}

/* ===== COMPONENTE NAVBAR ===== */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #222;
    color: white;
    padding: 10px 20px;
}

.navbar-left {
    display: flex;
    align-items: center;
}

.logo {
    width: 60px;
    height: 60px;
    margin-right: 5px;
}

.nombre-app {
    font-size: 30px;
    font-weight: bold;
}

.navbar-right .btn-login {
    text-decoration: none;
    background-color: #007bff;
    color: white;
    padding: 8px 14px;
    border-radius: 4px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.navbar-right .btn-login:hover {
    background-color: #0056b3;
}

/* ===== COMPONENTE SIDEBAR ===== */
.sidebar {
    width: 250px;
    background-color: #2c3e50;
    color: white;
    padding: 20px;
    flex-shrink: 0;
}

.menu-lateral ul {
    list-style: none;
    padding: 10px 0;
}

.menu-lateral li {
    margin-bottom: 15px;
    padding: 8px 12px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.menu-lateral li:hover {
    background-color: #34495e;
}

.menu-lateral li.active {
    background-color: #3498db;
}

.menu-lateral a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 5px 0;
}

.menu-lateral li.active a {
    font-weight: bold;
}

/* ===== COMPONENTE FOOTER ===== */
footer {
    background-color: #222;
    color: #ccc;
    text-align: center;
    padding: 15px 0;
    margin-top: auto;
}

/* ===== TABLA DE TAREAS ===== */
.tareas-container {
    width: 100%;
    overflow-x: auto;
    margin-top: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 15px;
}
.tareas-container, .subtareas-container {
    width: 100%;
    overflow-x: auto;
    margin-top: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 15px;
}

.tabla-tareas, .tabla-subtareas {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.tabla-tareas thead tr, .tabla-subtareas thead tr {
    background-color: #2c3e50;
    color: white;
}

.tabla-tareas th, .tabla-subtareas th {
    padding: 12px 15px;
    text-align: left;
    font-weight: 500;
    position: sticky;
    top: 0;
    border-bottom: 2px solid #1a252f;
}

.tabla-tareas th a, .tabla-subtareas th a {
    color: white;
    text-decoration: none;
    display: block;
    padding: inherit;
}

.tabla-tareas th a:hover, .tabla-subtareas th a:hover {
    color: white;
    text-decoration: none;
}

/* Celdas y filas */
.tabla-tareas td, .tabla-subtareas td {
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: middle;
    background-color: white;
}

/* Bordes y hover */
.tabla-tareas tr:not(:last-child) td, 
.tabla-subtareas tr:not(:last-child) td {
    border-bottom: 1px solid #e0e0e0;
}

.tabla-tareas tbody tr:hover, 
.tabla-subtareas tbody tr:hover {
    background-color: #f5f7fa;
}

.tabla-tareas tbody tr:last-child td,
.tabla-subtareas tbody tr:last-child td {
    border-bottom: none !important;
}
/* ===== PRIORIDADES ===== */
.prioridad-badge {
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.85em;
    font-weight: 500;
    display: inline-block;
}

.prioridad-alta {
    background-color:rgb(242, 168, 179);
    color: red;
}

.prioridad-media {
    background-color:rgb(240, 229, 192);
    color: #f57f17;
}

.prioridad-baja {
    background-color:rgb(204, 247, 208);
    color: #2e7d32;
}

/* ===== BOTONES PRINCIPALES ===== */
.btn-nueva-tarea {
    display: inline-block;
    background-color: #3498db;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.btn-nueva-tarea:hover {
    background-color: #2980b9;
    transform: translateY(-1px);
}

/* ===== ACCIONES DE TAREA ===== */
.acciones-tarea {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
}

.accion-icono {
    color: #7f8c8d;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    background: transparent;
    border: none;
    padding: 4px;
    cursor: pointer;
}

.accion-texto {
    color: #3498db;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.2s ease;
    padding: 4px 8px;
    border-radius: 4px;
}

.accion-icono:hover {
    transform: scale(1.1);
    background-color: transparent;
}

.accion-icono[title="Editar"]:hover {
    color: #2ecc71;
}

.accion-icono[title="Borrar"]:hover {
    color: #e74c3c;
}

.accion-icono[title="Archivar"]:hover {
    color: #3498db;
}

.accion-texto:hover {
    background-color: #e3f2fd;
    color: #2980b9;
}

/* ===== ESTADO VACÍO ===== */
.sin-tareas {
    padding: 20px;
    text-align: center;
    color: #666;
}

/* ===== MODAL ===== */
#modal-descripcion {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

#modal-contenido {
    background-color: white;
    padding: 25px;
    border-radius: 8px;
    max-width: 80%;
    max-height: 80%;
    overflow: auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

/* ===== ENLACES FOOTER ===== */
.enlaces-footer {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.enlaces-footer a {
    margin-right: 15px;
    color: #3498db;
    text-decoration: none;
}

.enlaces-footer a:hover {
    text-decoration: underline;
}

/* ===== ESTILOS PARA ARCHIVAR ===== */
.form-archivar {
    display: inline;
    margin: 0;
    padding: 0;
}

.accion-icono[title="Archivar"] {
    color: #95a5a6;
}

.accion-icono[title="Archivar"]:hover {
    color: #16a085;
    transform: scale(1.1);
}

.form-archivar button {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.subtareas-container {
    width: 100%;
    overflow-x: auto;
    margin-top: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 15px;
}

.prioridad-subtarea {
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.85em;
    font-weight: 500;
    display: inline-block;
}

.prioridad-subtarea.alta {
    background-color: rgb(242, 168, 179);
    color: red;
}

.prioridad-subtarea.normal {
    background-color: #fff8e1;
    color: #f57f17;
}

.prioridad-subtarea.baja {
    background-color: rgb(204, 247, 208);
    color: #2e7d32;
}

.btn-nueva-subtarea:hover {
    background-color: #1abc9c;
    transform: translateY(-1px);
}

/* Acciones de subtarea */
.acciones-subtarea {
    white-space: nowrap;
}

.accion-subtarea {
    color: #3498db;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 3px;
    transition: all 0.2s;
}

.accion-subtarea:hover {
    background-color: #e3f2fd;
}

/* Estado vacío */
.sin-subtareas {
    padding: 20px;
    text-align: center;
    color: #7f8c8d;
    font-style: italic;
}

/* Enlaces footer */
.subtareas-footer {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.subtareas-footer a {
    margin-right: 15px;
    color: #3498db;
    text-decoration: none;
}

.subtareas-footer a:hover {
    text-decoration: underline;
}
.modal-nueva_tarea {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-contenido-nueva_tarea {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    position: relative;
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.cerrar-modal-nueva_tarea {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #7f8c8d;
    cursor: pointer;
    transition: color 0.2s;
}

.cerrar-modal-nueva_tarea:hover {
    color: #e74c3c;
}

.modal-contenido-nueva_tarea h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 25px;
    font-size: 24px;
}

.modal-contenido-nueva_tarea form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.modal-contenido-nueva_tarea label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
}

.modal-contenido-nueva_tarea input[type="text"],
.modal-contenido-nueva_tarea input[type="date"],
.modal-contenido-nueva_tarea select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border 0.3s;
}

.modal-contenido-nueva_tarea input[type="text"]:focus,
.modal-contenido-nueva_tarea input[type="date"]:focus,
.modal-contenido-nueva_tarea select:focus {
    border-color: #3498db;
    outline: none;
}

.modal-contenido-nueva_tarea button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #16a085;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.modal-contenido-nueva_tarea button[type="submit"]:hover {
    background-color: #1abc9c;
}

.text-danger {
    color: #e74c3c;
    font-size: 14px;
    margin-top: 5px;
    display: block;
}
/* ===== ESTILOS PARA MODAL DE NUEVA SUBTAREA ===== */
.modal-nueva-subtarea {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-contenido-subtarea {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    position: relative;
    animation: modalFadeIn 0.3s ease-out;
}

.cerrar-modal-subtarea {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #7f8c8d;
    cursor: pointer;
    transition: color 0.2s;
}

.cerrar-modal-subtarea:hover {
    color: #e74c3c;
}

.modal-contenido-subtarea h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 25px;
    font-size: 24px;
}

.modal-contenido-subtarea form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.modal-contenido-subtarea label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
}

.modal-contenido-subtarea input[type="text"],
.modal-contenido-subtarea input[type="date"],
.modal-contenido-subtarea select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border 0.3s;
}

.modal-contenido-subtarea input[type="text"]:focus,
.modal-contenido-subtarea input[type="date"]:focus,
.modal-contenido-subtarea select:focus {
    border-color: #3498db;
    outline: none;
}

.modal-contenido-subtarea button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #16a085;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.modal-contenido-subtarea button[type="submit"]:hover {
    background-color: #1abc9c;
}

.modal-contenido-subtarea select[multiple] {
    min-height: 100px;
}

.modal-contenido-subtarea > form > div {
    margin-bottom: 5px;
}
/* MODAL EDITAR TAREA */
.modal-editar-tarea {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-contenido-editar-tarea {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    position: relative;
    animation: modalFadeIn 0.3s ease-out;
}

.cerrar-modal-editar-tarea {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #7f8c8d;
    cursor: pointer;
    transition: color 0.2s;
}

.cerrar-modal-editar-tarea:hover {
    color: #e74c3c;
}

.modal-contenido-editar-tarea h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 25px;
    font-size: 24px;
}

.modal-contenido-editar-tarea form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.modal-contenido-editar-tarea label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
}

.modal-contenido-editar-tarea input[type="text"],
.modal-contenido-editar-tarea input[type="date"],
.modal-contenido-editar-tarea select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border 0.3s;
}

.modal-contenido-editar-tarea input[type="text"]:focus,
.modal-contenido-editar-tarea input[type="date"]:focus,
.modal-contenido-editar-tarea select:focus {
    border-color: #3498db;
    outline: none;
}

.modal-contenido-editar-tarea button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #f39c12;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.modal-contenido-editar-tarea button[type="submit"]:hover {
    background-color: #e67e22;
}

.modal-contenido-editar-tarea .text-danger {
    color: #e74c3c;
    font-size: 14px;
    margin-top: 5px;
    display: block;
}
.modal-nueva_tarea,
.modal-editar-tarea {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5); /* fondo oscuro */
    justify-content: center;
    align-items: center;
}

.modal-contenido-nueva_tarea,
.modal-contenido-editar-tarea {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    position: relative;
}
td form {
    display: inline-block;
    width: 100%;
}

select[name="estado"] {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: white;
    color: #2c3e50;
    font-family: 'Roboto Condensed', sans-serif;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232c3e50'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 16px;
}

select[name="estado"]:hover {
    border-color: #3498db;
}

select[name="estado"]:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

select[name="estado"] option {
    padding: 8px;
    background-color: white;
    color: #2c3e50;
}

select[name="estado"] option[value="en_proceso"] {
    color: #f39c12;
}

select[name="estado"] option[value="completada"] {
    color: #2ecc71;
}
</style>
</head>
<body>
