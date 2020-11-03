<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Umum::index');
$routes->get('/auth', 'Auth::index');
$routes->get('/auth/forgotpassword', 'Auth::forgotPassword');
$routes->get('/auth/saveforgot', 'Auth::saveForgot');
$routes->get('/auth/blocked', 'Auth::blocked');
$routes->get('/auth/ubahpassword', 'Auth::ubahPassword');
$routes->put('/auth/savepasswordbaru', 'Auth::savePasswordBaru');

$routes->get('/berandaadmin', 'Admin\BerandaAdmin::index');
$routes->get('/user', 'Admin\User::index');
$routes->get('/user/createUser', 'Admin\User::createUser');
$routes->post('/user/saveUser', 'Admin\User::saveUser');
$routes->get('/user/edituser/(:num)', 'Admin\User::editUser/$1');
$routes->put('/user/updateuser/(:num)', 'Admin\User::updateUser/$1');
$routes->get('/kuesioner', 'Admin\KuesionerAdmin::index');
$routes->get('/kuesioner/createpertanyaan', 'Admin\KuesionerAdmin::createPertanyaan');
$routes->post('/kuesioner/savepertanyaan', 'Admin\KuesionerAdmin::savePertanyaan');
$routes->get('/kuesioner/editpertanyaan/(:num)', 'Admin\KuesionerAdmin::editPertanyaan/$1');
$routes->put('/kuesioner/saveeditpertanyaan/(:num)', 'Admin\KuesionerAdmin::saveEditPertanyaan/$1');
$routes->delete('/kuesioner/(:num)', 'Admin\KuesionerAdmin::deletePertanyaan/$1');
$routes->get('/hasilkuesioner', 'Admin\KuesionerAdmin::hasilKuesioner');
$routes->get('/export', 'Admin\KuesionerAdmin::exportPdf');

$routes->get('/berandaalumni', 'Alumni\BerandaAlumni::index');
$routes->get('/kuesioneralumni/(:num)', 'Alumni\KuesionerAlumni::index/$1');
$routes->post('/kuesioneralumni/savekuesioner/(:num)', 'Alumni\KuesionerAlumni::saveKuesioner/$1');
$routes->get('/kuesioneralumni/editkuesioner/(:num)', 'Alumni\KuesionerAlumni::editKuesioner/$1');
$routes->put('/kuesioneralumni/saveeditkuesioner/(:num)', 'Alumni\KuesionerAlumni::saveEditKuesioner/$1');

$routes->get('/profile', 'Profile::index');
$routes->get('/profile/editprofile/(:num)', 'Profile::editProfile/$1');
$routes->post('/profile/saveprofile/(:num)', 'Profile::saveProfile/$1');
/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
