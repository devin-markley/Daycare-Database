<?php
require_once 'config/database.php';

abstract class BaseModel
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = setupdb();
    }

    public function query($sql, $params = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}


?>