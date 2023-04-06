<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Provider Page</title>
	<!-- Include jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- Include your script.js file -->
	<script src="script.js"></script>
    <link rel="stylesheet" href="templates/provider_page.css">
</head>
<body>
<div class="left-column">
    <!-- Display all children for the provider in the database -->
    <h1>Children</h1>
    <div>
        <?= $children_table_html ?>
    </div>
    <!-- Add a new Child -->
    <h1>Add a new child</h1>
    <form method="post" id="add_child_form">
        <!-- Input field for the child's starting date -->
        <div>
            <p>Starting Date</p>
            <input type="date" name="starting_date" required>
        </div>
        <!-- Input fields for the child's first and last name -->
        <div>
            <p>Name</p>
            <div>
                <input type="text" name="first_name" placeholder="First" required>
                <input type="text" name="last_name" placeholder="Last" required>
            </div>
        </div>
        <!-- Radio buttons for the child's active status -->
        <div>
            <p>Status</p>
            <div>
                <div>
                    <input type="radio" value="1" id="radio_1" name="active_status" required>
                    <label for="radio_1" class="radio"><span>Active</span></label>
                </div>
                <div>
                    <input type="radio" value="0" id="radio_2" name="active_status" required>
                    <label for="radio_2" class="radio"><span>Inactive</span></label>
                </div>
            </div>
        </div>
        <!-- Hidden input field for provider_id -->
        <input type="hidden" name="provider_id" value= <?php echo $provider_id ?>>
        <!-- Submit button to add child -->
        <div class="btn-block">
            <button type="submit" name="add_child">Add child</button>
        </div>
    </form>
</div>
<div class="right-column">
    <!-- Attendance Form -->
    <h1>Add a new meal</h1>
    <form method="post" action="index.php?page=provider_page&provider_id=<?= $provider_id ?>">
        <!-- Input field for date and time of the meal -->
        <div>
            <input type="datetime-local" name="date_served" required>
        </div>
        <!-- Radio buttons to indicate if the meal contains fruits and vegetables -->
        <p>Did the meal contain fruits?</p>
        <div class="question-answer">
            <div>
                <input type="radio" value="1" id="radio_1" name="fruit" required>
                <label for="radio_1" class="radio"><span>Yes</span></label>
            </div>
            <div>
                <input type="radio" value="0" id="radio_2" name="fruit" required>
                <label for="radio_2" class="radio"><span>No</span></label>
            </div>
        </div> 
        <p>Did the meal contain vegetables?</p>
        <div class="question-answer">
            <div>
                <input type="radio" value="1" id="radio_1" name="vegetable" required>
                <label for="radio_1" class="radio"><span>Yes</span></label>
            </div>
            <div>
                <input type="radio" value="0" id="radio_2" name="vegetable" required>
                <label for="radio_2" class="radio"><span>No</span></label>
            </div>
        </div>
        <!-- Hidden input field for provider_id -->
        <input type="hidden" name="provider_id" value= <?php echo $provider_id ?>>     
        <!-- Attendance checkboxes -->
        <p>Attendance</p>
        <?= $attendance_form_html ?>
        <!-- Submit button to record attendance -->
        <div>
            <button type="submit" name="attendance_form">Record Attendance</button>
    </form>
    <!-- Form to view attendance summary -->
	<form method="post" action="index.php?page=provider_page&provider_id=<?= $provider_id ?>">
    	<button type="submit" name="display_attendance">View Attendance Summary</button>
		<input type="hidden" name="provider_id" value= <?php echo $provider_id ?>>
		<input type="date" name="report_start_date" required>

	</form>
	<!-- Form to view and individuals attendance -->
	<form method="post" action="index.php?page=provider_page&provider_id=<?= $provider_id ?>">
    	
		<input type="hidden" name="provider_id" value= <?php echo $provider_id ?>>
		<input type="date" name="report_start_date" required>
		<input type="text" name="child_id" placeholder= "Child Id" required>
		<button type="submit" name="individual_attendance">View Individual Attendance</button> 
	</form>
</div>
</body>
</html>
