$(document).ready(function() {
    $('#changePasswordBtn').click(function() {
        // Get the values of newPassword and renewpassword fields
        var newPassword = $('#newPassword').val();
        var renewpassword = $('#renewPassword').val();

        // AJAX request to changepassword.php
        $.ajax({
            url: 'changepassword.php',
            method: 'post',
            data: {
                newPassword: newPassword,
                renewpassword: renewpassword
            },
            success: function(response) {
                let result = JSON.parse(response);
                if (result.success) {
                    // Password change was successful, display success message
                    $('#alertmsg').html('<div class="alert alert-success" role="alert">' + result.success + '</div>');
                    setTimeout(function(){
                        location.href = '../signout.php'; // Reload the page after 2 seconds
                    }, 2000);
                } else {
                    // Password change failed, display error message
                    $('#alertmsg').html('<div class="alert alert-danger" role="alert">' + result.failed + '</div>');
                }
            },
      
        });
    });
});
