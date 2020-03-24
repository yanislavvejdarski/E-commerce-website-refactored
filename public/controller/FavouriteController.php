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
    public function show()
    {
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $sessionParams = $this->session->getSessionParams();
        $userController = new UserController();
        $favouriteDAO = new FavouriteDAO();
        if (!empty($sessionParams["logged_user_id"])) {
            $favourites = $favouriteDAO->showFavourites($sessionParams["logged_user_id"]);
        }
        if (!isset($sessionParams["logged_user_id"])) {
            include_once "view/login.php";
        } else {
            include_once "view/favourites.php";
        }
    }

    public function add()
    {
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $getParams = $this->request->getParams();
        $postParams = $this->request->postParams();
        $sessionParams = $this->session->getSessionParams();
        if (isset($getParams["product"])) {
            if (isset($postParams["like"])) {
                $prdId = $postParams["like"];
                $favoriteDAO = new FavouriteDAO();
                $check = $favoriteDAO->checkIfInFavourites($getParams["product"], $sessionParams["logged_user_id"]);

                if ($check) {
                    echo "Already added in Favourites";
                } else {
                    $productDAO = new ProductDAO();
                    $cheker = $productDAO->findProduct($getParams["product"]);
                    if ($cheker->id != "") {
                        $favoriteDAO->addToFavourites($getParams["product"], $sessionParams["logged_user_id"]);
                        header("Location:/product/$prdId");
                    } else {
                        $this->show();
                        include_once "view/favourites.php";
                    }
                }
            } else {
                $favoriteDAO = new FavouriteDAO();
                $check = $favoriteDAO->checkIfInFavourites($getParams["product"], $sessionParams["logged_user_id"]);
                if ($check) {
                    echo "Already added in Favourites";
                } else {
                    $productDAO = new ProductDAO();
                    $cheker = $productDAO->findProduct($getParams["product"]);
                    if ($cheker->id != "") {
                        $favoriteDAO->addToFavourites($getParams["product"], $sessionParams["logged_user_id"]);
                        $this->show();
                        include_once "view/favourites.php";
                    } else {
                        $this->show();
                        include_once "view/favourites.php";
                    }
                }
            }
        } else {
            throw new NotFoundException("Can't add Invalid Product to Favourites");
        }
    }


    public function delete()
    {
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $getParams = $this->request->getParams();
        $sessionParams = $this->session->getSessionParams();
        $like = $this->request->postParam("like");
        if (isset($like)) {
            $prdId = $like;
            if (isset($getParams["product"]) && is_numeric($getParams["product"])) {
                $favoriteDAO = new FavouriteDAO();
                $favoriteDAO->deleteFromFavourites($getParams["product"], $sessionParams["logged_user_id"]);
                header("Location: /product/" . $prdId);
            } else {
                $this->show();
                throw new NotFoundException("Can't add Invalid Product to Favourites");
            }
        } else {
            if (isset($getParams["product"]) && is_numeric($getParams["product"])) {
                $favoriteDAO = new FavouriteDAO();
                $favoriteDAO->deleteFromFavourites($getParams["product"], $sessionParams["logged_user_id"]);
                $this->show();
            } else {
                $this->show();
                throw new NotFoundException("Can't add Invalid Product to Favourites");
            }
        }
    }
}