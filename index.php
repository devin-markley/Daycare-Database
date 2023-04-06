<?php

/**
 * Set error reporting settings.
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

/**
 * Connect to the database and check for successful database connection.
 */
$db_connection = pg_connect("host=localhost dbname=pr8w3j user=pr8w3j password=five%PercentIsGood");
if (!$db_connection) {
    die("Error connecting to the database");
}

/**
 * Set the default page to 'homepage' if not specified.
 */
$current_page = isset($_GET['page']) ? $_GET['page'] : 'homepage';

/**
 * Include the necessary model and view files.
 */
require "model.php";
require "view.php";

/**
 * Render the homepage with provider buttons.
 */
if ($current_page === 'homepage') {
    $providers = get_providers($db_connection);
    $provider_buttons = '';
    foreach ($providers as $provider) {
        $provider_buttons .= "<button class='providerbutt' data-provider-id='{$provider['provider_id']}'>{$provider['provider_name']}</button>";
    }
    render('homepage', [
        'provider_buttons' => $provider_buttons
    ]);
} 
/**
 * Render the provider_page if for the specified provider.
 */
elseif ($current_page === 'provider_page') {
    // Get the provider_id and check if it is set
    $provider_id = isset($_GET['provider_id']) ? (int) $_GET['provider_id'] : null;
    if (!$provider_id) {
        die("Error: Provider ID not specified");
    }

    // Get the children associated with the provider and render the provider_page
    $children = get_children_by_provider($db_connection, $provider_id);
    $children_table_html = build_children_table_html($children, $provider_id);
    $active_children_list = get_active_children($db_connection, $provider_id);
    $attendance_form_html = attendance_form($active_children_list);
    render('provider_page', [
        'attendance_form_html' => $attendance_form_html,
        'children_table_html' => $children_table_html,
        'provider_id' => $provider_id
    ]);
}

/**
 * Handle form submissions.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_child'])){
        /**
         * Add child to the database.
         */
        $provider_id = isset($_GET['provider_id']) ? (int) $_GET['provider_id'] : null;
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : null;
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : null;
        $starting_date = isset($_POST['starting_date']) ? $_POST['starting_date'] : null;
        $active_status = isset($_POST['active_status']) ? (int) $_POST['active_status'] : null;
        $result = add_child_to_database($db_connection, $provider_id, $first_name, $last_name, $starting_date, $active_status);
        exit();
    } elseif (isset($_POST['attendance_form'])) {
        /**
        * Add meal and attendance to the database.
        */
        $provider_id = isset($_GET['provider_id']) ? (int) $_GET['provider_id'] : null;
        $date_served = isset($_POST['date_served']) ? $_POST['date_served'] : null;
        $fruit = isset($_POST['fruit']) ? (int) $_POST['fruit'] : null;
        $vegetables = isset($_POST['vegetable']) ? (int) $_POST['vegetables'] : null;
        $child_ids = isset($_POST['attendance']) ? $_POST['attendance'] : null;
        add_meal_and_attendance($db_connection, $date_served, $fruit, $vegetables, $child_ids, $provider_id);

    } elseif (isset($_POST['display_attendance'])) {
        /**
        * Display monthly attendance report.
        */
        $provider_id = isset($_GET['provider_id']) ? (int) $_GET['provider_id'] : null;
        $report_month  = isset($_POST['report_start_date']) ? $_POST['report_start_date'] : null;
        $attendance_data = monthly_attendance_report($db_connection, $report_month, $provider_id);
        $attendance_table = attendance_summary_table($attendance_data);
        render('attendance_report', [
            'attendance_table' => $attendance_table
        ]);

    } elseif (isset($_POST['active_status'])) {
        /**
        * Update children's active status.
        */
        foreach ($children as &$child) {
            $child_id = $child['child_id'];
            $child['active_status'] = in_array($child_id, $_POST['active_status']) ? 't' : 'f';
        }
        update_children_status($children, $db_connection);
        exit();
    } elseif (isset($_POST['individual_attendance'])) {
        /**
        * Display individual attendance report.
        */
        $provider_id = isset($_GET['provider_id']) ? (int) $_GET['provider_id'] : null;
        $report_month  = isset($_POST['report_start_date']) ? $_POST['report_start_date'] : null;
        $child_id = isset($_POST['child_id']) ? $_POST['child_id'] :null;
        $attendance_report = individual_attendance_report($db_connection, $provider_id, $child_id, $report_month);
        $attendance_report_table = build_individual_attendance_report($attendance_report);
        render('individual_attendance_report', [
            'attendance_report_table' => $attendance_report_table
        ]);
    }
} else {
    die();
}

/**
 * Close the database connection.
 */
pg_close($$db_connection);
?>