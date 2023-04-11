<?php
/**
 * HomeModel class represents the model for the home page of the application.
 *
 * @package     App\Models
 */
require_once 'BaseModel.php';

class HomeModel extends BaseModel {
    /**
     * Get all the providers from the database.
     *
     * @return array The array of providers.
     */
    public function getAllProviders(){
        $sql ="SELECT * FROM provider";
        $result = $this->query($sql)->fetchAll();
        return $result;
    }
}
?>