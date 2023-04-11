$(document).ready(function() {
    $('.child-checkbox').change(function() {
        var childId = $(this).attr('name').replace('active_status[', '').replace(']', '');
        var activeStatus = $(this).is(':checked') ? 1 : 0;

        // Send the child ID and new active status to the PHP script via AJAX
        $.ajax({
            url: 'index.php?page=provider&action=updateActiveStatus',
            type: 'POST',
            data: { childId: childId, activeStatus: activeStatus },
            success: function(response) {
                // Handle the response from the PHP script here
                if (response.success) {
                    console.log('Active status updated successfully!');
                } else {
                    console.error('Error updating active status: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle errors here
                console.error('AJAX error: ' + error);
            }
        });
    });
});