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
        $favourites = $favouriteDAO->showFavourites($_SESSION["logged_user_id"]);
        if(!isset($_SESSION["logged_user_id"])){
            include_once "view/login.php";
        }else{
            include_once "view/favourites.php";
        }

    }


    public function add(){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        if (isset($_GET["id"])){
            if (isset($_POST["like"])) {
                $prdId = $_POST["like"];
                $favoriteDAO=new FavouriteDAO();
                $check = $favoriteDAO->checkIfInFavourites($_GET["id"] , $_SESSION["logged_user_id"]);

                if ($check){
                    echo "Already added in Favourites";
                }
                else{
                    $productDAO = new ProductDAO();
                    $cheker = $productDAO->findProduct($_GET["id"]);
                    if ($cheker->id != ""){
                        $favoriteDAO->addToFavourites($_GET["id"],$_SESSION["logged_user_id"]);
                        header("Location: index.php?target=product&action=show&prdId=$prdId");
                    }
                    else{
                        $this->show();
                        include_once "view/favourites.php";
                    }

                }
            }
            else{
                $favoriteDAO=new FavouriteDAO();
                $check = $favoriteDAO->checkIfInFavourites($_GET["id"] , $_SESSION["logged_user_id"]);

                if ($check){
                    echo "Already added in Favourites";
                }
                else{
                    $productDAO = new ProductDAO();
                    $cheker = $productDAO->findProduct($_GET["id"]);
                    if ($cheker->id != ""){
                        $favoriteDAO->addToFavourites($_GET["id"],$_SESSION["logged_user_id"]);
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


    public function delete(){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        if (isset($_POST["like"])){
            $prdId = $_POST["like"];
            if (isset($_GET["id"]) && is_numeric($_GET["id"])){


                $favoriteDAO=new FavouriteDAO();
                $favoriteDAO->deleteFromFavourites($_GET["id"] , $_SESSION["logged_user_id"]);
                header("Location:index.php?target=product&action=show&prdId=".$prdId);

            }else{
                $this->show();
                throw new NotFoundException("Can't add Invalid Product to Favourites");
            }
        }
        else{
            if (isset($_GET["id"]) && is_numeric($_GET["id"])){


                $favoriteDAO=new FavouriteDAO();
                $favoriteDAO->deleteFromFavourites($_GET["id"] , $_SESSION["logged_user_id"]);
                $this->show();

            }else{
                $this->show();
                throw new NotFoundException("Can't add Invalid Product to Favourites");
            }
        }




    }
}