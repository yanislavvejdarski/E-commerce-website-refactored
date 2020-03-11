<?php
$routes = new Router();
$uri = $_SERVER["REQUEST_URI"];

// Home Page
$routes::route("/home", "MainController@render", $uri);
// Single Product Page
$routes::route("/product/{:id}", "ProductController@show", $uri);
// Cart Page
$routes::route("/cart", "CartController@show", $uri);
// Update Cart
$routes::route("/updateCart", "CartController@update", $uri);
// Favourites Page
$routes::route("/favourites", "FavouriteController@show", $uri);
// Add To Favourites
$routes::route("/addToFavourites/{:id}", "FavouriteController@add", $uri);
// Remove From Favourites
$routes::route("/removeFromFavourites/{:id}", "FavouriteController@delete", $uri);
// Add Product To Cart
$routes::route("/addToCart/{:id}", "CartController@add", $uri);
// Remove Product From Cart
$routes::route("/removeFromCart/{:id}", "CartController@delete", $uri);
// Customer Rate Product
$routes::route("/rateProduct/{:id}", "RatingController@rateProduct", $uri);
// Customer Submit Rating Product
$routes::route("/rate", "RatingController@rate", $uri);
// Edit Rated Page
$routes::route("/editRatedPage", "RatingController@editRatedPage", $uri);
// Edit Rated Product
$routes::route("/editRate", "RatingController@editRate", $uri);
// Personal Customer Rated Products
$routes::route("/ratedproducts", "RatingController@myRated", $uri);
// My Account
$routes::route("/myaccount", "UserController@account", $uri);
// Edit Profile Page
$routes::route("/editProfilePage", "UserController@editPage", $uri);
// Edit Profile/Save Changes
$routes::route("/editProfile", "UserController@edit", $uri);
// My Orders
$routes::route("/myorders", "OrderController@show", $uri);
// Add Address Page
$routes::route("/addaddress", "AddressController@newAddress", $uri);
// Add New Address
$routes::route("/addNewAddress", "AddressController@add", $uri);
// Edit Address Page
$routes::route("/editAddressPage", "AddressController@editAddress", $uri);
// Edit Address
$routes::route("/editAddress", "AddressController@edit", $uri);
// Delete Address
$routes::route("/deleteAddress", "AddressController@delete", $uri);
//Register Page
$routes::route("/registerPage", "UserController@registerPage", $uri);
//Register User
$routes::route("/register", "UserController@register", $uri);
// Log Out
$routes::route("/logout", "UserController@logout", $uri);
// Log In
$routes::route("/login", "UserController@login", $uri);
// Log In Page
$routes::route("/loginPage", "UserController@loginPage", $uri);
// Forgotten Password Page
$routes::route("/forgottenPassword", "UserController@forgottenPassword", $uri);
// Send New Password
$routes::route("/sendNewPassword", "UserController@sendNewPassword", $uri);
// Search Bar
$routes::route("/render", "SearchController@render", $uri);
// Show Types From Categorie Id
$routes::route("/ctgId/{:id}", "ProductController@show", $uri);
// Show Products From Type Id
$routes::route("/typeId/{:id}", "ProductController@show", $uri);
// Make a Order
$routes::route("/order", "OrderController@order", $uri);
// VueJS Product Filtration
$routes::route("/filterProducts", "ProductController@filterProducts", $uri);
// Admin Add Product Page
$routes::route("/addProductPage", "ProductController@addProductPage", $uri);
// Admin Add Product
$routes::route("/addProduct", "ProductController@addProduct", $uri);
// Admin Edit Product Page
$routes::route("/editProductPage", "ProductController@editProductPage", $uri);
// Admin Edit Product Page
$routes::route("/editProduct", "ProductController@editProduct", $uri);
// Admin Remove Discount
$routes::route("/removeDiscount", "ProductController@removeDiscount", $uri);
// Admin Add Discount
$routes::route("/addDiscount", "ProductController@addDiscount", $uri);

















