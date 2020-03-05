<?php
namespace model;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Search{

    public  $search;
    private $products;
    private $categories;
    private $types;

    function __construct($search)
    {
        $searchDAO=new SearchDAO();
        $this->search = $search;
        try{
            $this->products = $searchDAO->searchProduct($this->search);
            $this->categories = $searchDAO->searchCategorie($this->search);
            $this->types = $searchDAO->searchType($this->search);

        }catch (\PDOException $e){
            include_once "view/header.php";
            echo "Oops, error 500!";
        }

    }

    public function render(){
        if (isset($_POST["search"])){
            $search = new Search($_POST["search"]);
                $search->renderProducts();
                $search->renderCategories();
                $search->renderTypes();
        }
    }

    private function renderProducts()
    {
        if ($this->products)
            {
            echo "<h1>Products </h1>";
            }
        foreach ($this->products as $product)
            {
                ?>
                <h3><a href="index.php?target=product&action=show&prdId=<?=$product["id"]?>"> <?= $product["name"] ?></a></h3>
                <?php
            }
    }

    private function renderCategories(){
        if ($this->categories) {

            echo "<h1>Categories</h1>";
            foreach ($this->categories as $category) {
                ?>
                <h3><a href="index.php?target=product&action=show&ctgId=<?=$category["id"]?>"> <?= $category["name"] ?></a></h3>
                <?php
            }
        }
    }

    private function renderTypes(){
        if ($this->types) {

            echo "<h1>Type</h1>";
            foreach ($this->types as $type) {
                ?>
                <h3><a href="index.php?target=product&action=show&typId=<?= $type["id"] ?>" ><?= $type["name"] ?></a></h3>
                <?php
            }
        }
    }
}