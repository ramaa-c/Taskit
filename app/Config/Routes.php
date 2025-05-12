<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultController('Auth');
$routes->get('/', 'Auth::index');


$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->match(['get', 'post'], 'registro', 'Auth::crearUsuario');
$routes->get('auth/logout', 'Auth::logout');


$routes->get('tareas', 'Tareas::index');
$routes->match(['get', 'post'], 'tareas/nueva_tarea', 'Tareas::crearTarea');
$routes->match(['get', 'post'], 'tareas/editar_tarea/(:num)', 'Tareas::editarTarea/$1');
$routes->get('tareas/borrar_tarea/(:num)', 'Tareas::borrarTarea/$1');


$routes->get('subtareas/mis_subtareas/', 'Tareas::misSubtareas');
$routes->get('subtareas/subtareas_asignadas/', 'Tareas::subtareasAsignadas');
$routes->match(['get', 'post'], 'subtareas/nueva_subtarea/(:num)', 'Subtareas::crearSubTarea/$1');
$routes->get('subtareas/editar_subtarea', 'Subtareas::editarSubtarea/$1');
$routes->get('subtareas/borrar_subtarea', 'Subtareas::borrarSubtarea/$1');