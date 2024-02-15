$(document).ready(function(){

    $('#adchange').on('click', function(event){
        event.preventDefault(); // Corrected typo here
        const newPassword = $('#newPassword').val();
        const renewPassword = $('#renewPassword').val();


        $.ajax({
            url: 'changepasswords.php',
            method: 'post',
            data:{
                change: 1,
                newPassword: newPassword,
                renewPassword: renewPassword
            },
            success:function(response){
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
            }


        });


    });


})
