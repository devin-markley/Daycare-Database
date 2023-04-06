// Add child form submit handler
$(document).ready(function () {
    /**
     * Handle the submit event of the add child form.
     * 
     * @param {Event} event - The submit event object.
     */
    $("#add_child_form").on("submit", function (event) {
        event.preventDefault();

        // Get form data and add a "add_child" field to the data object
        const formData = $(this).serializeArray();
        formData.push({ name: "add_child", value: "1" });

        // Send an AJAX request to the server to add the child
        $.ajax({
            url: "index.php?page=provider_page&provider_id=" + $("#add_child_form input[name='provider_id']").val(),
            type: "POST",
            data: formData,
            success: function (response) {
                console.log("Child added successfully");
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error("Error adding child:");
                console.error("Status: " + status);
                console.error("Error message: " + error);
                console.error("Response text: " + xhr.responseText);
            },
            complete: function () {
                // Reset the form after AJAX request is complete
                $("#add_child_form").trigger("reset");
            }
        });
    });
});

// Alter child status button click handler
$(document).ready(function() {
    /**
     * Handle the click event of the alter child status button.
     * 
     * @param {Event} event - The click event object.
     */
    $("#alter-status-btn").click(function(event) {
        event.preventDefault(); // Prevent the default form submission behavior

        // Send an AJAX request to the server to alter the child's status
        $.ajax({
            type: "POST",
            url: $("#child-form").attr("action"),
            data: $("#child-form").serialize(),
            success: function(response) {
                // Handle the response from the server
                console.log(response);
                // Reload the page
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle the error
                console.log(xhr.responseText);
            }
        });
    });
});
