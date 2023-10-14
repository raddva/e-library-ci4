<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
    // -- Admin API --//
    $routes->post('admin/login', 'Admin::auth');
    $routes->get('admin/get', 'Admin::getAdmins');
    $routes->get('admin/(:segment)', 'Admin::find/$1');
    // -- Admin API --//

    // -- User API --//
    $routes->post('user/login', 'User::auth');
    $routes->post('user/register', 'User::register');
    $routes->post('user/update/(:segment)', 'User::update/$1');
    $routes->get('user/find/(:segment)', 'User::find/$1');
    $routes->get('user/get', 'User::getUsers');
    $routes->delete('user/delete/(:segment)', 'User::delete/$1');
    // -- User API --//

    // -- History API --//
    $routes->get('history/get/(:segment)', 'History::getHistory/$1');
    // -- History API --//

    // -- Wishlist API --//
    $routes->post('wishlist/create', 'Wishlist::create');
    $routes->get('wishlist/get/(:segment)', 'Wishlist::getList/$1');
    $routes->post('wishlist/delete/(:segment)', 'Wishlist::delete/$1');
    // -- Wishlist API --//

    // -- Books API --//
    $routes->post('books/add', 'Books::create');
    $routes->post('books/update/(:segment)', 'Books::update/$1');
    $routes->get('books/get', 'Books::getBooks');
    $routes->get('books/show/(:segment)', 'Books::show/$1');
    $routes->get('books/find/(:segment)', 'Books::find/$1');
    $routes->delete('books/delete/(:segment)', 'Books::delete/$1');
    // -- Books API --//

    // -- Borrows API --//
    $routes->post('borrow/add', 'Borrow::create');
    $routes->post('borrow/update/(:segment)', 'Borrow::update/$1');
    $routes->get('borrow/get', 'Borrow::getBorrowsData');
    $routes->get('borrow/getPinjam/(:segment)', 'Borrow::getDetailPinjam/$1');
    // -- Borrows API --//
});
