<?php
use helpers\Request;

$routes = new Router($_SERVER["REQUEST_URI"]);

// Home Page
$routes->route("/home", "MainController@render");
// Home Page
$routes->route("", "MainController@render");
// Single Product Page
$routes->route("/product/{:id}", "ProductController@show");
// Cart Page
$routes->route("/cart", "CartController@show");
// Update Cart
$routes->route("/cart/update", "CartController@update");
// Favourites Page
$routes->route("/favourites", "FavouriteController@show");
// Add To Favourites
$routes->route("/favourites/add/product/{:id}", "FavouriteController@add");
// Remove From Favourites
$routes->route("/favourites/remove/product/{:id}", "FavouriteController@delete");
// Add Product To Cart
$routes->route("/cart/add/product/{:id}", "CartController@add");
// Remove Product From Cart
$routes->route("/cart/remove/product/{:id}", "CartController@delete");
// Customer Rate Product
$routes->route("/rate/product/{:id}", "RatingController@rateProduct");
// Customer Submit Rating Product
$routes->route("/rate", "RatingController@rate");
// Edit Rated Page
$routes->route("/editRatedPage", "RatingController@editRatedPage");
// Edit Rated Product
$routes->route("/rate/edit", "RatingController@editRate");
// Personal Customer Rated Products
$routes->route("/ratedProducts", "RatingController@myRated");
// My Account
$routes->route("/myAccount", "UserController@account");
// Edit Profile Page
$routes->route("/myAccount/editPage", "UserController@editPage");
// Edit Profile/Save Changes
$routes->route("/myAccount/edit", "UserController@edit");
// My Orders
$routes->route("/orders", "OrderController@show");
// Add Address Page
$routes->route("/address/new", "AddressController@newAddress");
// Add New Address
$routes->route("/address/add", "AddressController@add");
// Edit Address Page
$routes->route("/editAddressPage", "AddressController@editAddress");
// Edit Address
$routes->route("/address/edit", "AddressController@edit");
// Delete Address
$routes->route("/address/delete", "AddressController@delete");
//Register Page
$routes->route("/registerPage", "UserController@registerPage");
//Register User
$routes->route("/register", "UserController@register");
// Log Out
$routes->route("/logout", "UserController@logout");
// Log In
$routes->route("/login", "UserController@login");
// Log In Page
$routes->route("/loginPage", "UserController@loginPage");
// Forgotten Password Page
$routes->route("/password/forgot", "UserController@forgottenPassword");
// Send New Password
$routes->route("/password/new", "UserController@sendNewPassword");
// Search Bar
$routes->route("/render", "SearchController@render");
// Show Types From Categorie Id
$routes->route("/ctgId/{:id}", "ProductController@show");
// Show Products From Type Id
$routes->route("/typeId/{:id}", "ProductController@show");
// Make a Order
$routes->route("/order", "OrderController@order");
// VueJS Product Filtration
$routes->route("/filter/products", "ProductController@filterProducts");
// Admin Add Product Page
$routes->route("/admin/addProductPage", "ProductController@addProductPage");
// Admin Add Product
$routes->route("/admin/addProduct", "ProductController@addProduct");
// Admin Edit Product Page
$routes->route("/admin/editProductPage", "ProductController@editProductPage");
// Admin Edit Product Page
$routes->route("/admin/editProduct", "ProductController@editProduct");
// Admin Remove Discount
$routes->route("/admin/removeDiscount", "ProductController@removeDiscount");

// If entered wrong route,this method will execute
$routes->error404();




















