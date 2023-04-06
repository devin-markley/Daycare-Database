<?php
/**
 * This file contains a set of functions for managing providers, children, meals, and attendance.
 * Throughout the file, the $db parameter represents a PostgreSQL database connection resource.
 */

 /**
 * Retrieves all providers from the database.
 *
 * @return array An array of providers, or an empty array if none are found.
 */

function get_providers($db) {
    $sql_providers = "SELECT * FROM provider";
    $result_providers = pg_query($db, $sql_providers);
    if ($result_providers) {
        $providers = pg_fetch_all($result_providers);
        return $providers;
    }
    return array();
}

/**
 * Retrieves children associated with the given provider.
 *
 * @param int $provider_id The ID of the provider.
 * @return array An array of children, or an error message if an issue occurs.
 */
function get_children_by_provider($db, $provider_id) {
    $provider_children = "SELECT * FROM child WHERE provider_id = $1";
    $result = pg_query_params($db, $provider_children, array($provider_id));
    if (!$result) {
        die("Error: " . pg_last_error($db));
    }
    $children = pg_fetch_all($result);
    // if (!$children) {
    //     die("Error: No children found for provider_id $provider_id");
    // }
    return $children;
}

/**
 * Retrieves active children associated with the given provider.
 *
 * @param int $provider_id The ID of the provider.
 * @return array An array of active children, or an empty array if none are found.
 */
function get_active_children($db, $provider_id) {
    $sql = "SELECT child_id, first_name, last_name FROM child WHERE provider_id = $1 AND active_status = 't'";
    $result = pg_query_params($db, $sql, array($provider_id));
    if ($result) {
        $active_children = pg_fetch_all($result);
        return $active_children;
    } else {
        var_dump(pg_last_error($db));
    }
    return array();
}

/**
 * Adds a child to the database.
 *
 * @param int $provider_id The ID of the provider.
 * @param string $first_name The first name of the child.
 * @param string $last_name The last name of the child.
 * @param string $starting_date The starting date of the child in 'YYYY-MM-DD' format.
 * @param string $active_status The active status of the child ('t' for true, 'f' for false).
 */
function add_child_to_database($db, $provider_id, $first_name, $last_name, $starting_date, $active_status) {
    $sql_insert = "INSERT INTO child (provider_id, first_name, last_name, starting_date, active_status)
                 VALUES ($1, $2, $3, $4, $5)";
    $result_insert = pg_query_params($db, $sql_insert, array($provider_id, $first_name, $last_name, $starting_date, $active_status));
}

/**
 * Adds a meal and attendance information to the database.
 *
 * @param string $date_served The date the meal was served in 'YYYY-MM-DD' format.
 * @param bool $fruit Whether or not fruit were served (true or false).
 * @param bool $vegetables Whether or not vegetables were served (true or false).
 * @param array $child_ids An array of child IDs who attended the meal.
 * @param int $provider_id The ID of the provider
 */
function add_meal_and_attendance($db, $date_served, $fruit, $vegetable, $child_ids, $provider_id) {
    // Insert meal content into meal_content table
    $sql_insert_meal_content = "INSERT INTO meal_content (fruit, vegetables) 
                                VALUES ($1, $2)
                                RETURNING meal_content_id";
    $result_insert_meal_content = pg_query_params($db, $sql_insert_meal_content, array($fruit, $vegetable));
    if (!$result_insert_meal_content) {
        die("Error: " . pg_last_error($db));
    }
    $meal_content_id_row = pg_fetch_row($result_insert_meal_content);
    $meal_content_id = $meal_content_id_row[0];

    // Insert meal into meal table
    $sql_insert_meal = "INSERT INTO meal (meal_content_id, date_served) 
                        VALUES ($1, $2)
                        RETURNING meal_id";
    $result_insert_meal = pg_query_params($db, $sql_insert_meal, array($meal_content_id, $date_served));
    if (!$result_insert_meal) {
        die("Error: " . pg_last_error($db));
    }
    $meal_id_row = pg_fetch_row($result_insert_meal);
    $meal_id = $meal_id_row[0];

    // Update attendance table with new meal_id and provider_id values
    foreach ($child_ids as $child_id) {
        $sql_update_attendance = "INSERT INTO attendance (meal_id, provider_id, child_id) 
                                  VALUES ($1,$2,$3)"; 
        $result_update_attendance = pg_query_params($db, $sql_update_attendance, array($meal_id, $provider_id, $child_id));
        if (!$result_update_attendance) {
            die("Error: " . pg_last_error($db));
        }
    }
}

/**
 * Updates the active status of children in the database.
 *
 * @param array $children An array of children with 'child_id' and 'active_status' keys.
 */
function update_children_status($children, $db) {
    $query = "UPDATE child SET active_status = $1 WHERE child_id = $2";
    $stmt = pg_prepare($db, "", $query);
    foreach ($children as $child) {
        $child_id = $child['child_id'];
        $active_status = $child['active_status'];
        pg_execute($db, "", array($active_status, $child_id));
    }
}

/**
 * Generates an individual attendance report for a specific child.
 *
 * @param int $provider_id The ID of the provider.
 * @param int $child_id The ID of the child.
 * @param string $report_month The report month in 'YYYY-MM' format.
 * @return array An array containing attendance information, or an empty array if no data is found.
 */
function individual_attendance_report($db, $provider_id, $child_id, $report_month) {
    $query = "
        SELECT
            c.first_name,
            c.last_name,
            COUNT(a.meal_id) AS meals_attended,
            SUM(CASE WHEN mc.fruit AND mc.vegetables THEN 1 ELSE 0 END) AS fruit_vegetables_meals
    FROM
        child c
        JOIN attendance a ON c.child_id = a.child_id
        JOIN meal m ON a.meal_id = m.meal_id
        JOIN meal_content mc ON m.meal_content_id = mc.meal_content_id
    WHERE
        c.provider_id = $1
        AND c.child_id = $2
        AND EXTRACT(MONTH FROM m.date_served) = $3
        AND c.active_status = TRUE
    GROUP BY
        c.child_id
    ";

    $report_month_num = date('n', strtotime($report_month));
    $stmt = pg_prepare($db, "individual_attendance_report", $query);
    if (!$stmt) {
        var_dump(pg_last_error($db));
    }
    $stmt = pg_execute($db, "individual_attendance_report", array($provider_id, (int) $child_id, $report_month_num));

    $result = pg_fetch_assoc($stmt);
    if($result){
        return $result;
    } else {
        var_dump(pg_last_error($db)); 
    }
    return array();
}

/**
 * Generates a monthly attendance report for a specific provider.
 *
 * @param resource $db The PostgreSQL database connection.
 * @param string $report_month The report month in 'YYYY-MM' format.
 * @param int $provider_id The ID of the provider.
 * @return array An array containing attendance information, or an empty array if no data is found.
 */
function monthly_attendance_report($db, $report_month, $provider_id) {
    $sql = "SELECT COUNT(DISTINCT m.meal_id) as total_meals,
            COUNT(a.child_id) as total_children,
            CASE WHEN COUNT(m.meal_id) = 0 THEN null
                 ELSE CAST((COUNT(CASE WHEN mc.fruit OR mc.vegetables THEN 1 END) * 100) / COUNT(m.meal_id) AS FLOAT)
            END as percent_veggie_meals
            FROM meal m
            LEFT JOIN attendance a ON m.meal_id = a.meal_id
            LEFT JOIN child c ON a.child_id = c.child_id
            LEFT JOIN meal_content mc ON m.meal_content_id = mc.meal_content_id
            WHERE EXTRACT(MONTH FROM m.date_served) = EXTRACT(MONTH FROM $1::DATE)
            AND EXTRACT(YEAR FROM m.date_served) = EXTRACT(YEAR FROM $1::DATE)
            AND c.provider_id = $2";
    
    $result = pg_query_params($db, $sql, array($report_month, $provider_id));
    if ($result) {
        $report = pg_fetch_all($result);
        return $report;
    } else {
        var_dump(pg_last_error($db));
    }
}

?>