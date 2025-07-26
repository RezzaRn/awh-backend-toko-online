<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->group('api', function ($routes) {
    $routes->group('auth', function ($routes) {
        $routes->post('register', 'Auth::register');
        $routes->post('login', 'Auth::login');
        $routes->get('me', 'Auth::me');
    });

    $routes->group('products', ['filter' => 'rolefilter'], function ($routes) {
        $routes->get('/', 'Products::index');
        $routes->post('/', 'Products::addProduct');
        $routes->put('(:any)', 'Products::updateProduct/$1');
        $routes->delete('(:any)', 'Products::deleteProduct/$1');
    });

    $routes->group('orders', function ($routes) {
        $routes->get('/', 'Orders::index');
        $routes->post('checkout', 'Orders::checkout');
        $routes->get('details/(:any)', 'Orders::getOrderDetails/$1');
    });

    $routes->group('reports', function ($routes) {
        $routes->get('/', 'Reports::index');
    });

    $routes->group('users', function ($routes) {
        $routes->get('/', 'Users::index');
    });
});
