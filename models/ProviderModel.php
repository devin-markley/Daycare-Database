<?php
require_once 'BaseModel.php';

/**
 * The ProviderModel class is responsible for managing the provider data.
 */
class ProviderModel extends BaseModel
{
    /**
     * Initializes the ProviderModel instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves all children of a provider.
     *
     * @param int $providerId The ID of the provider.
     *
     * @return array An array of child records.
     */
    public function getAllChildrenByProvider($providerId)
    {
        $sql = "SELECT * FROM child WHERE provider_id = :providerId";
        $result = $this->query($sql, [':providerId' => $providerId])->fetchAll();
        return $result;
    }

    /**
     * Updates the active status of a child.
     *
     * @param int $childId The ID of the child.
     * @param bool $activeStatus The new active status of the child.
     * 
     * @return void
     */
    public function updateChildActiveStatus($childId, $activeStatus)
    {
        $sql = "UPDATE child SET active_status = :active_status WHERE child_id = :child_id";
        $this->query($sql, [
            ':child_id' => $childId,
            ':active_status' => $activeStatus,
        ]);
    }

    /**
     * Inserts a new child record.
     *
     * @param string $firstName The first name of the child.
     * @param string $lastName The last name of the child.
     * @param string $startingDate The starting date of the child.
     * @param bool $activeStatus The active status of the child.
     * @param int $providerId The ID of the provider.
     * 
     * @return void
     */
    public function insertChild($firstName, $lastName, $startingDate, $activeStatus, $providerId)
    {
        $sql = "INSERT INTO child (first_name, last_name, starting_date, active_status, provider_id) VALUES (:first_name, :last_name, :starting_date, :active_status, :provider_id)";
        $result = $this->query($sql, [
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':starting_date' => $startingDate,
            ':active_status' => $activeStatus,
            ':provider_id' => $providerId,
        ]);
    }

    /**
     * Inserts a new attendance record.
     *
     * @param int $providerId The ID of the provider.
     * @param string $mealDate The date of the meal.
     * @param string $mealTime The time of the meal.
     * @param bool $containsFruit Whether the meal contains fruit.
     * @param bool $containsVegetables Whether the meal contains vegetables.
     * @param array $selectedChildren The IDs of the children in attendance.
     * 
     * @return void
     */
    public function insertAttendance($providerId, $mealDate, $mealTime, $containsFruit, $containsVegetables, $selectedChildren)
    {
        // Insert meal_content
        $sql = "INSERT INTO meal_content (fruit, vegetables) VALUES (:contains_fruit, :contains_vegetables)";
        $this->query($sql, [
            ':contains_fruit' => $containsFruit,
            ':contains_vegetables' => $containsVegetables,
        ]);

        $mealContentId = $this->lastInsertId();

        // Insert meal
        $sql = "INSERT INTO meal (meal_content_id, date_served) VALUES (:meal_content_id, :date_served)";
        $this->query($sql, [
            ':meal_content_id' => $mealContentId,
            ':date_served' => $mealDate . ' ' . $mealTime,
        ]);

        $mealId = $this->lastInsertId();

        // Insert attendance for each child
        foreach ($selectedChildren as $childId) {
            $sql = "INSERT INTO attendance (child_id, meal_id, provider_id) VALUES (:child_id, :meal_id, :provider_id)";
            $this->query($sql, [
                ':child_id' => $childId,
                ':meal_id' => $mealId,
                ':provider_id' => $providerId,
            ]);
        }
    }
    /**
     * Retrieves all active children of a provider.
     *
     * @param int $providerId The ID of the provider.
     *
     * @return array An array of child records.
     */
    public function getAllActiveChildrenByProvider($providerId)
    {
        $sql = "SELECT * FROM child WHERE provider_id = :providerId AND active_status = TRUE";
        $result = $this->query($sql, [
            ':providerId' => $providerId,
        ])->fetchAll();
        return $result;
    }

    /**
     * Retrieves the attendance summary for an individual child.
     *
     * @param int $childId The ID of the child.
     * @param string $reportStartDate The start date of the report.
     *
     * @return array An array of attendance records.
     */
    public function getIndividualAttendanceSummary($childId, $reportStartDate)
    {
        $sql = "SELECT m.date_served, m.meal_id, mc.fruit, mc.vegetables
    FROM attendance AS a
    JOIN meal AS m ON a.meal_id = m.meal_id
    JOIN meal_content AS mc ON m.meal_content_id = mc.meal_content_id
    WHERE a.child_id = :child_id
    AND m.date_served >= :report_start_date
    AND m.date_served < :report_start_date + interval '1 month'";
        $result = $this->query($sql, [
            ':child_id' => $childId,
            ':report_start_date' => $reportStartDate,
        ])->fetchAll();

        return $result;
    }

    /**
     * Retrieves the attendance summary for a provider.
     *
     * @param int $providerId The ID of the provider.
     * @param string $reportStartDate The start date of the report.
     *
     * @return array An array of attendance records.
     */
    public function getProviderAttendanceSummary($providerId, $reportStartDate)
    {
        $sql = "SELECT COUNT(child.child_id) AS total_children_fed, 
                COUNT(DISTINCT meal.meal_id) AS total_distinct_meals, 
                ROUND(100.0 * COUNT(DISTINCT CASE WHEN CAST(meal_content.fruit AS INTEGER) = 1 THEN meal.meal_id END) / NULLIF(COUNT(DISTINCT meal.meal_id), 0), 2) AS fruit_percentage,
                ROUND(100.0 * COUNT(DISTINCT CASE WHEN CAST(meal_content.vegetables AS INTEGER) = 1 THEN meal.meal_id END) / NULLIF(COUNT(DISTINCT meal.meal_id), 0), 2) AS vegetable_percentage
        FROM attendance
        JOIN child ON attendance.child_id = child.child_id
        JOIN meal ON attendance.meal_id = meal.meal_id
        JOIN meal_content ON meal.meal_content_id = meal_content.meal_content_id
        JOIN provider ON attendance.provider_id = provider.provider_id
        WHERE provider.provider_id = :provider_id
        AND meal.date_served >= :report_start_date
        AND meal.date_served < DATE_TRUNC('month', :report_start_date) + INTERVAL '1 month'";


        $result = $this->query($sql, [
            ':provider_id' => $providerId,
            ':report_start_date' => $reportStartDate,
        ])->fetchAll();

        return $result;
    }

    /**
     * Retrieves the name of a provider.
     *
     * @param int $providerId The ID of the provider.
     *
     * @return array An array containing the provider name.
     */
    public function getProviderName($providerId)
    {
        $sql = "SELECT provider_name FROM provider WHERE provider_id = :provider_id";
        $result = $this->query($sql, [
            ':provider_id' => $providerId,
        ])->fetchAll();

        return $result;
    }

    /**
     * Retrieves the meal table for a provider.
     *
     * @param int $providerID The ID of the provider.
     * @param string $reportStartDate The start date of the report.
     *
     * @return array An array containing the meal table records.
     */
    public function getMealTable($providerID, $reportStartDate)
    {
        $sql = "
        SELECT mc.fruit, mc.vegetables, m.date_served, COUNT(DISTINCT a.child_id) AS num_children
        FROM meal m
        JOIN meal_content mc ON m.meal_content_id = mc.meal_content_id
        JOIN attendance a ON m.meal_id = a.meal_id
        JOIN child c ON a.child_id = c.child_id
        WHERE c.provider_id = :provider_id
        AND m.date_served >= :report_start_date
        AND m.date_served < date_trunc('month', :report_start_date) + INTERVAL '1 month'
        GROUP BY mc.fruit, mc.vegetables, m.date_served
    ";

        $result = $this->query($sql, [
            ':provider_id' => $providerID,
            ':report_start_date' => $reportStartDate,
        ])->fetchAll();

        return $result;
    }


}