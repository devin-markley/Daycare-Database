<?php
require_once 'BaseModel.php';

class HomeModel extends BaseModel {
    public function getAllProviders(){
        $sql ="SELECT * FROM provider";
        var_dump($sql);
        $result = $this->query($sql)->fetchAll();
        return $result;
    }
}

?>