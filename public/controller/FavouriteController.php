<?php
namespace controller;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use exception\NotFoundException;
use model\FavouriteDAO;
use model\ProductDAO;
use PDOException;
use controller\UserController;

class FavouriteController{
    public function show(){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $userController=new UserController();
        $favouriteDAO=new FavouriteDAO();
        if (!empty($_SESSION["logged_user_id"])) {
            $favourites = $favouriteDAO->showFavourites($_SESSION["logged_user_id"]);
        }
        if(!isset($_SESSION["logged_user_id"])){
            include_once "view/login.php";
        }else{
            include_once "view/favourites.php";
        }

    }


    public function add($params){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        if (isset($params["addToFavourites"])){
            if (isset($_POST["like"])) {
                $prdId = $_POST["like"];
                $favoriteDAO=new FavouriteDAO();
                $check = $favoriteDAO->checkIfInFavourites($params["addToFavourites"] , $_SESSION["logged_user_id"]);

                if ($check){
                    echo "Already added in Favourites";
                }
                else{
                    $productDAO = new ProductDAO();
                    $cheker = $productDAO->findProduct($params["addToFavourites"]);
                    if ($cheker->id != ""){
                        $favoriteDAO->addToFavourites($params["addToFavourites"],$_SESSION["logged_user_id"]);
                        header("Location:/product/$prdId");
                    }
                    else{
                        $this->show();
                        include_once "view/favourites.php";
                    }

                }
            }
            else{
                $favoriteDAO=new FavouriteDAO();
                $check = $favoriteDAO->checkIfInFavourites($params["addToFavourites"] , $_SESSION["logged_user_id"]);

                if ($check){
                    echo "Already added in Favourites";
                }
                else{
                    $productDAO = new ProductDAO();
                    $cheker = $productDAO->findProduct($params["addToFavourites"]);
                    if ($cheker->id != ""){
                        $favoriteDAO->addToFavourites($params["addToFavourites"],$_SESSION["logged_user_id"]);
                        $this->show();
                        include_once "view/favourites.php";
                    }
                    else{
                        $this->show();
                        include_once "view/favourites.php";
                    }

                }
            }
        }
        else{
            throw new NotFoundException("Can't add Invalid Product to Favourites");
        }
    }


    public function delete($params){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        if (isset($_POST["like"])){
            $prdId = $_POST["like"];
            if (isset($params["removeFromFavourites"]) && is_numeric($params["removeFromFavourites"])){
                $favoriteDAO=new FavouriteDAO();
                $favoriteDAO->deleteFromFavourites($params["removeFromFavourites"] , $_SESSION["logged_user_id"]);
                header("Location: /product/".$prdId);
            }else{
                $this->show();
                throw new NotFoundException("Can't add Invalid Product to Favourites");
            }
        }
        else{
            if (isset($params["removeFromFavourites"]) && is_numeric($params["removeFromFavourites"])){


                $favoriteDAO=new FavouriteDAO();
                $favoriteDAO->deleteFromFavourites($params["removeFromFavourites"] , $_SESSION["logged_user_id"]);
                $this->show();

            }else{
                $this->show();
                throw new NotFoundException("Can't add Invalid Product to Favourites");
            }
        }




    }
}