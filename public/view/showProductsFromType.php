<?php
namespace view;


use model\ProductDAO;

try{
    ?>
    <script src="../view/filter.js"></script>


    <?php
}catch (\PDOException $e){
    include_once "view/header.php";
    echo "Oops, error 500!";

}
