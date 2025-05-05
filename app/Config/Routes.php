<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultController('Auth');
$routes->get('/', 'Auth::index');


$routes->get('login', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/registro', 'Auth::registro');
$routes->post('auth/guardarUsuario', 'Auth::guardarUsuario');
$routes->get('auth/logout', 'Auth::logout');


$routes->get('tareas', 'Tareas::index');
$routes->get('tareas/addTarea', 'Tareas::agregarTarea');
$routes->post('tareas/guardarTarea', 'Tareas::guardarTarea');
$routes->get('tareas/buscarTarea/(:num)', 'Tareas::buscarTarea/$1');
$routes->post('tareas/actualizarTarea', 'Tareas::actualizarTarea');
$routes->get('tareas/borrarTarea/(:num)', 'Tareas::borrarTarea/$1');


$routes->get('tareas/mis_subtareas/', 'Tareas::misSubtareas');
$routes->get('tareas/subtareas_asignadas/', 'Tareas::subtareasAsignadas');
$routes->get('tareas/agregarSubtarea/(:num)', 'Tareas::agregarSubtarea/$1');
$routes->get('tareas/addSubtarea/(:num)', 'Tareas::addSubtarea/$1');
$routes->post('tareas/guardarSubTarea', 'Tareas::guardarSubTarea');

