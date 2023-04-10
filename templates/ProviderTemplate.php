<div class='provider-template'>
    <div class='left-column'>
        <h1>Children</h1>
        <form action="index.php?page=provider&action=updateActiveStatus" method="post">
            <table>
                <thead>
                    <tr>
                        <th>Child ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Starting Date</th>
                        <th>Active Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_data['children'] as $child): ?>
                        <tr>
                            <td>
                                <?php echo $child['child_id']; ?>
                            </td>
                            <td>
                                <?php echo $child['first_name']; ?>
                            </td>
                            <td>
                                <?php echo $child['last_name']; ?>
                            </td>
                            <td>
                                <?php echo $child['starting_date']; ?>
                            </td>
                            <td>
                                <input type="checkbox" name="active_status[<?php echo $child['child_id']; ?>]" value="1" class="child-checkbox" <?php echo $child['active_status'] ? 'checked' : ''; ?>>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="submit" value="Submit">
        </form>


        <h1>Add a Child</h1>
        <form action="index.php?page=provider&action=addChild" method="post">
            <label for="first-name">First Name:</label>
            <input type="text" id="first_name" name="first_name">

            <label for="last-name">Last Name:</label>
            <input type="text" id="last_name" name="last_name">

            <label for="starting-date">Starting Date:</label>
            <input type="date" id="starting-date" name="starting_date">

            <label for="active_status">Active Status:</label>
            <select id="active_status" name="active_status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <input type="submit" value="Submit">
        </form>
    </div>
    <div class='right-column'>
        <h1>Record Attendance</h1>
        <form action="index.php?page=provider&action=recordAttendance" method="post">
            <label for="meal-date">Meal Date:</label>
            <input type="date" id="meal-date" name="meal_date">

            <label for="meal-time">Meal Time:</label>
            <input type="time" id="meal-time" name="meal_time">

            <label for="contains-fruit">Contains Fruit:</label>
            <select id="contains-fruit" name="contains_fruit">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>

            <label for="contains-vegetables">Contains Vegetables:</label>
            <select id="contains-vegetables" name="contains_vegetables">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            <?php foreach ($_data['active_children'] as $activeChild): ?>
                <label>
                    <input type="checkbox" name="children[]" value="<?= $activeChild['child_id'] ?>">
                    <?= htmlspecialchars($activeChild['first_name']) . ' ' . htmlspecialchars($activeChild['last_name']) ?>
                </label><br>
            <?php endforeach; ?>

            <input type="submit" value="Submit">
        </form>

        <form action="index.php?page=provider&action=attendanceSummaryPDF" method="post">
            <label for="report-start-date">Report Start Date:</label>
            <input type="date" id="report-start-date" name="report_date">
            <input type="submit" value="Submit">
        </form>

        <form action="index.php?page=provider&action=childAttendanceSummaryPDF" method="post">

            <label for="report-start-date">Report Start Date:</label>
            <input type="date" id="report-start-date" name="report_date">

            <label for="child-id">Child ID:</label>
            <input type="text" id="child-id" name="child_id">

            <input type="submit" value="Submit">
        </form>
    </div>
</div>