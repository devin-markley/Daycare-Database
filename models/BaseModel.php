<?php
/**
 * BaseModel class represents the base model of the application and provides a database connection instance.
 *
 * @package     App\Models
 */
require_once 'config/database.php';

abstract class BaseModel
{
    /**
     * The PDO instance.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * Create a new BaseModel instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pdo = setupdb();
    }

    /**
     * Execute a query with the given SQL statement and parameters.
     *
     * @param string $sql The SQL statement to execute.
     * @param array $params The array of parameters to bind to the statement.
     * @return PDOStatement The PDO statement object.
     */
    public function query($sql, $params = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    /**
     * Get the ID of the last inserted row.
     *
     * @return string The ID of the last inserted row.
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
?>