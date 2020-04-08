<?php

namespace controller;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use exception\NotFoundException;
use model\DAO\FavouriteDAO;
use model\DAO\ProductDAO;
use helpers\Request;

class FavouriteController extends AbstractController
{
    /**
     * Show Favourites
     */
    public function show()
    {
        $sessionParams = $this->session->getSessionParams();
        $userController = new UserController();
        $favouriteDAO = new FavouriteDAO();
        $favourites = $favouriteDAO->showFavourites($sessionParams['loggedUserId']);
        include_once 'view/favourites.php';
    }

    /**
     * Add Product To Favourites
     *
     * @throws NotFoundException
     */
    public function add()
    {
        $getParams = $this->request->getParams();
        $postParams = $this->request->postParams();
        $sessionParams = $this->session->getSessionParams();
        $paramsAndRules = [
            $getParams['product'] => 'isVariableSet'
        ];
        $favoriteDAO = new FavouriteDAO();
        $check = $favoriteDAO->checkIfInFavourites(
            $getParams['product'],
            $sessionParams['loggedUserId']
        );
        $productDAO = new ProductDAO();
        $checker = $productDAO->findProduct($getParams['product']);
        if ($check) {
            echo 'Already added in Favourites';
        } elseif ($this->validator->validate($paramsAndRules)) {
            $productId = $getParams['product'];
            if ($checker->id != '') {
                $favoriteDAO->addToFavourites(
                    $getParams['product'],
                    $sessionParams['loggedUserId']
                );
            }
            if (isset($postParams['like'])) {
                header('Location:/product/' . $productId);
            }
            $this->show();
            include_once 'view/favourites.php';
        } else {
            throw new NotFoundException('Can\'t add Invalid Product to Favourites');
        }
    }

    /**
     * Delete Product From Favourites
     *
     * @throws NotFoundException
     */
    public function delete()
    {
        $getParams = $this->request->getParams();
        $sessionParams = $this->session->getSessionParams();
        $fromProductPage = $this->request->postParam('like');
        $paramsAndRules = [
            $getParams['product'] => 'isVariableSet|isNumeric'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $favoriteDAO = new FavouriteDAO();
            $favoriteDAO->deleteFromFavourites(
                $getParams['product'],
                $sessionParams['loggedUserId']
            );
            if ($fromProductPage) {
                $productId = $getParams['product'];
                header('Location: /product/' . $productId);
            } else {
                $this->show();
            }
        } else {
            $this->show();

            throw new NotFoundException('Can\'t add Invalid Product to Favourites');
        }
    }
}