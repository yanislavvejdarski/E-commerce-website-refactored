<?php
namespace model;
class Type{
    public $id;
    public $name;
    public $categorie;
    public function __construct($id,$name,$categorie)
    {
        $this->id = $id;
        $this->name = $name;
        $this->categorie = $categorie;
    }
    public function show(){
        ?><h3><a href="index.php?target=product&action=show&typId=<?= $this->id ?>" ><?= $this->name?></a></h3> <?php
    }
}