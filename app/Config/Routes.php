<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultController('Auth');
$routes->get('/', 'Auth::index');


$routes->match(['get', 'post'], '/login', 'Auth::login');
$routes->match(['get', 'post'], '/registro', 'Auth::crearUsuario');
$routes->get('auth/logout', 'Auth::logout');


$routes->get('mis_tareas', 'Tareas::index');
$routes->match(['get', 'post'], 'tareas/nueva_tarea', 'Tareas::crearTarea');
$routes->match(['get', 'post'], 'tareas/editar_tarea/(:num)', 'Tareas::editarTarea/$1');
$routes->get('tareas/borrar_tarea/(:num)', 'Tareas::borrarTarea/$1');
$routes->post('tareas/cambiarEstado/(:num)', 'Tareas::cambiarEstado/$1');
$routes->post('subtareas/validarSubtareasCompletas', 'Subtareas::validarSubtareasCompletas');
$routes->get('tareas/archivadas', 'Tareas::tareasArchivadas');
$routes->match(['get', 'post'], 'tareas/archivar/(:num)', 'Tareas::archivar/$1');
$routes->match(['get', 'post'], 'tareas/desarchivar/(:num)', 'Tareas::desarchivar/$1');


$routes->get('subtareas/mis_subtareas/(:num)', 'Subtareas::misSubtareas/$1');
$routes->get('subtareas/subtareas_asignadas', 'Subtareas::subtareasAsignadas');
$routes->match(['get', 'post'], 'subtareas/nueva_subtarea/(:num)', 'Subtareas::crearSubTarea/$1');
$routes->match(['get', 'post'], 'subtareas/editar_subtarea/(:num)', 'Subtareas::editarSubtarea/$1');
$routes->get('subtareas/borrar_subtarea/(:num)', 'Subtareas::borrarSubtarea/$1');
$routes->get('subtareas/cambiar_estado/(:num)', 'Subtareas::cambiarEstado/$1');
$routes->post('subtareas/cambiar_estado/(:num)', 'Subtareas::cambiarEstado/$1');
