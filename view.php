<?php
/**
 * Builds an HTML table displaying children's information based on their provider ID.
 * Allows the active_status of the children within the form to be edited
 * 
 * @param array $children The array of children's data.
 * @param int $provider_id The provider's ID.
 * @return string The HTML table.
 */
function build_children_table_html($children, $provider_id) {
    $action_url = 'index.php?page=provider_page&provider_id=' . $provider_id;
    $html = '<form method="POST" action="' . $action_url . '" id="child-form">';
    $html .= '<table>';
    $html .= '<thead><tr><th>child_id</th><th>First Name</th><th>Last Name</th><th>Starting Date</th><th>Active Status</th></tr></thead>';
    $html .= '<tbody>';
    foreach ($children as $child) {
        $checked = $child['active_status'] == 't' ? 'checked' : '';
        $html .= "<tr>";
        $html .= "<td>" . $child['child_id'] . "</td>";
        $html .= "<td>" . $child['first_name'] . "</td>";
        $html .= "<td>" . $child['last_name'] . "</td>";
        $html .= "<td>" . $child['starting_date'] . "</td>";
        $html .= "<td><input type='checkbox' name='active_status[]' value='" . $child['child_id'] . "' " . $checked . "></td>";
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<button type="submit" id="alter-status-btn">Alter Active Status</button>';
    $html .= '</form>';
    return $html;
}
/**
 * Generates an HTML attendance form that allows providers to add active children to be added to the attendance record.
 *
 * @param array $children The array of children's data.
 * @return string The HTML attendance form.
 */
function attendance_form($children) {
    $attendance_form_html = '<div>';
    foreach ($children as $child) {
    $attendance_form_html .= '<div>';
    $attendance_form_html .= '<label>';
    $attendance_form_html .= '<input type="checkbox" name="attendance[]" value="' . $child['child_id'] . '">';
    $attendance_form_html .= $child['first_name'] . ' ' . $child['last_name'];
    $attendance_form_html .= '</label>';
    $attendance_form_html .= '</div>';
    }
    $attendance_form_html .= '</div>';
    return $attendance_form_html;
}

/**
 * Generates an HTML table summarizing attendance information.
 *
 * @param array $attendance The array of attendance data.
 * @return string The HTML attendance table.
 */
function attendance_summary_table($attendance) {
    $attendance_table = '<div>';
    $attendance_table .= '<h1>All students attendance</h1>';
    $attendance_table .= '<table>';
    $attendance_table .= '<thead><tr><th>Percent of meals containing fruits or vegetables</th><th>Total meals served</th><th>Total children served</th></tr></thead>';
    $attendance_table .= '<tbody>';
    foreach ($attendance as $record) {
        $attendance_table .= "<tr>";
        $attendance_table .= "<td>" . $record['percent_veggie_meals'] . "</td>";
        $attendance_table .= "<td>" . $record['total_meals'] . "</td>";
        $attendance_table .= "<td>" . $record['total_children'] . "</td>";
        $attendance_table .= "</tr>";
    }
    $attendance_table .= '</tbody>';
    $attendance_table .= '</table>';
    $attendance_table .= '</div>';
    return $attendance_table;
}

/**
 * Builds an individual attendance report in HTML table format.
 *
 * @param array $attendance_report The array of individual attendance data.
 * @return string The HTML individual attendance report table.
 */
function build_individual_attendance_report($attendance_report){
    if (!is_array(reset($attendance_report))) {
        $attendance_report = array($attendance_report);
    }
    $individual_attendance_report = '<div>';
    $individual_attendance_report .= '<h1>Indivual Attendance Report</h1>'; 
    $individual_attendance_report .= '<table>';
    $individual_attendance_report .= '<thead><tr><th>First Name</th><th>Last Name</th><th>Meals Attended</th><th>Meals with fruits or vegetables</th></tr></thead>';
    $individual_attendance_report .= '<tbody>';
    foreach ($attendance_report as $record) {
        $individual_attendance_report .= "<tr>";
        $individual_attendance_report .= "<td>" . $record['first_name'] . "</td>";
        $individual_attendance_report .= "<td>" . $record['last_name'] . "</td>";
        $individual_attendance_report .= "<td>" . $record['meals_attended'] . "</td>";
        $individual_attendance_report .= "<td>" . $record['fruit_vegetables_meals'] . "</td>";
        $individual_attendance_report .= "</tr>";
    }
    $individual_attendance_report .= '</tbody>';
    $individual_attendance_report .= '</table>';
    $individual_attendance_report .= '</div>';
    return $individual_attendance_report;
}


/**
 * Renders an HTML template with the provided parameters.
 *
 * @param string $template The name of the template file.
 * @param array $params The parameters to pass to the template.
 */
function render($template, $params) {
    $template_path = "templates/$template.php";
    if (!file_exists($template_path)) {
        die("Error: Template file not found");
    }
    extract($params);
    include $template_path;
}
?>