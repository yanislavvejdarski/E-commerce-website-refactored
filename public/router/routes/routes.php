<?php

use router\Router;

$routes = new Router();

// Home Page
$routes->route('/home', 'MainController@render');
// Home Page
$routes->route('', 'MainController@render');
// Single Product Page
$routes->route('/product/{:id}', 'ProductController@show');
// Cart Page
$routes->route('/cart', 'CartController@show','user');
// Update Cart
$routes->route('/cart/update', 'CartController@update','user');
// Favourites Page
$routes->route('/favourites', 'FavouriteController@show','user');
// Add To Favourites
$routes->route('/favourites/add/product/{:id}', 'FavouriteController@add','user');
// Remove From Favourites
$routes->route('/favourites/remove/product/{:id}', 'FavouriteController@delete' ,'user');
// Add Product To Cart
$routes->route('/cart/add/product/{:id}', 'CartController@add','user');
// Remove Product From Cart
$routes->route('/cart/remove/product/{:id}', 'CartController@delete','user');
// Customer Rate Product
$routes->route('/rate/product/{:id}', 'RatingController@rateProduct','user');
// Customer Submit Rating Product
$routes->route('/rate', 'RatingController@rate','user');
// Edit Rated Page
$routes->route('/editRatedPage', 'RatingController@editRatedPage','user');
// Edit Rated Product
$routes->route('/rate/edit', 'RatingController@editRate','user');
// Personal Customer Rated Products
$routes->route('/ratedProducts', 'RatingController@myRated','user');
// My Account
$routes->route('/myAccount', 'UserController@account','user');
// Edit Profile Page
$routes->route('/myAccount/editPage', 'UserController@editPage','user');
// Edit Profile/Save Changes
$routes->route('/myAccount/edit', 'UserController@edit','user');
// My Orders
$routes->route('/orders', 'OrderController@show','user');
// Add Address Page
$routes->route('/address/new', 'AddressController@newAddress','user');
// Add New Address
$routes->route('/address/add', 'AddressController@add','user');
// Edit Address Page
$routes->route('/editAddressPage', 'AddressController@editAddress','user');
// Edit Address
$routes->route('/address/edit', 'AddressController@edit','user');
// Delete Address
$routes->route('/address/delete', 'AddressController@delete','user');
//Register Page
$routes->route('/registerPage', 'UserController@registerPage');
//Register User
$routes->route('/register', 'UserController@register');
// Log Out
$routes->route('/logout', 'UserController@logout');
// Log In
$routes->route('/login', 'UserController@login');
// Log In Page
$routes->route('/loginPage', 'UserController@loginPage');
// Forgotten Password Page
$routes->route('/password/forgot', 'UserController@forgottenPassword');
// Send New Password
$routes->route('/password/new', 'UserController@sendNewPassword');
// Search Bar
$routes->route('/render', 'SearchController@render');
// Show Types From Categorie Id
$routes->route('/ctgId/{:id}', 'ProductController@show');
// Show Products From Type Id
$routes->route('/typeId/{:id}', 'ProductController@show');
// Make a Order
$routes->route('/order', 'OrderController@order');
// VueJS Product Filtration
$routes->route('/filter/products', 'ProductController@filterProducts');
// Admin Add Product Page
$routes->route('/admin/addProductPage', 'ProductController@addProductPage','admin');
// Admin Add Product
$routes->route('/admin/addProduct', 'ProductController@addProduct','admin');
// Admin Edit Product Page
$routes->route('/admin/editProductPage', 'ProductController@editProductPage','admin');
// Admin Edit Product Page
$routes->route('/admin/editProduct', 'ProductController@editProduct','admin');
// Admin Remove Discount
$routes->route('/admin/removeDiscount', 'ProductController@removeDiscount','admin');

// If entered wrong route,this method will execute
$routes->error404();