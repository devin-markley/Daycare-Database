<?php
require_once 'BaseModel.php';

class HomeModel extends BaseModel {
    public function getAllProviders(){
        $sql ="SELECT * FROM provider";
        $result = $this->query($sql)->fetchAll();
        return $result;
    }
}

?>