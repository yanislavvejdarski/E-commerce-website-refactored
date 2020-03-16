<?php
namespace controller;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use exception\NotFoundException;
use model\FavouriteDAO;
use model\ProductDAO;
use Request;

class FavouriteController extends AbstractController {
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

/*
 *
 * @param array
 * Annotations
 *
 */
    public function add(){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $params = $this->request->getParams();
        $post = $this->request->postParams();

        if (isset($params["product"])){
            if (isset($post["like"])) {
                $prdId = $post["like"];
                $favoriteDAO=new FavouriteDAO();
                $check = $favoriteDAO->checkIfInFavourites($params["product"] , $_SESSION["logged_user_id"]);

                if ($check){
                    echo "Already added in Favourites";
                }
                else{
                    $productDAO = new ProductDAO();
                    $cheker = $productDAO->findProduct($params["product"]);
                    if ($cheker->id != ""){
                        $favoriteDAO->addToFavourites($params["product"],$_SESSION["logged_user_id"]);
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
                $check = $favoriteDAO->checkIfInFavourites($params["product"] , $_SESSION["logged_user_id"]);

                if ($check){
                    echo "Already added in Favourites";
                }
                else{
                    $productDAO = new ProductDAO();
                    $cheker = $productDAO->findProduct($params["product"]);
                    if ($cheker->id != ""){
                        $favoriteDAO->addToFavourites($params["product"],$_SESSION["logged_user_id"]);
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
        $params = $this->request->getParams();
        $like = $this->request->postParam("like");

        if (isset($like)){
            $prdId = $like;
            if (isset($params["product"]) && is_numeric($params["product"])){
                $favoriteDAO=new FavouriteDAO();
                $favoriteDAO->deleteFromFavourites($params["product"] , $_SESSION["logged_user_id"]);
                header("Location: /product/".$prdId);
            }else{
                $this->show();
                throw new NotFoundException("Can't add Invalid Product to Favourites");
            }
        }
        else{
            if (isset($params["product"]) && is_numeric($params["product"])){


                $favoriteDAO=new FavouriteDAO();
                $favoriteDAO->deleteFromFavourites($params["product"] , $_SESSION["logged_user_id"]);
                $this->show();

            }else{
                $this->show();
                throw new NotFoundException("Can't add Invalid Product to Favourites");
            }
        }




    }
}