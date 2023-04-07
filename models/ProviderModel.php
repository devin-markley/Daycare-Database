<?php
require_once 'BaseModel.php';

class ProviderModel extends BaseModel {
    public function getAllChildrenByProvider($providerId) {
        $sql = "SELECT * FROM child WHERE provider_id = :providerId";
        $result = $this->query($sql, [':providerId' => $providerId])->fetchAll();
        return $result;
    }
}