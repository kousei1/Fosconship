$(document).ready(function(){
    $('#updateemployee').on('submit', function(event){
        event.preventDefault();

        const viewEmpID = $('#viewEmpID').val();
        const viewlname = $('#viewlname').val();
        const viewfname = $('#viewfname').val();
        const viewmname = $('#viewmname').val();
        const viewbirthday = $('#viewbirthday').val();
        const viewgender = $('#viewgender').val();
        const viewaddress = $('#viewaddress').val();
        const viewemail = $('#viewemail').val();
        const viewcontact = $('#viewcontact').val();
        const viewhireddate = $('#viewhireddate').val();
        const viewdeptID = $('#viewdeptID').val();
        const viewsalaryEmp = $('#viewsalaryEmp').val();
        
        let formData = new FormData();
        formData.append('viewEmpID', viewEmpID);
        formData.append('viewlname', viewlname);
        formData.append('viewfname', viewfname);
        formData.append('viewmname', viewmname);
        formData.append('viewbirthday', viewbirthday);
        formData.append('viewgender', viewgender);
        formData.append('viewaddress', viewaddress);
        formData.append('viewemail', viewemail);
        formData.append('viewcontact', viewcontact);
        formData.append('viewhireddate', viewhireddate);
        formData.append('viewdeptID', viewdeptID);
        formData.append('viewsalaryEmp', viewsalaryEmp);
    
        let img = $('#NewImage')[0].files;
    
        formData.append('NewImage', img[0]);
        
        $.ajax({
            url: 'updates.php',
            method: 'post',
            contentType: false,
            processData: false,
            data: formData,
            success:function(response){
                let res = JSON.parse(response);

                if(res.success){
                    // Display success alert
                    $('#editalert').removeClass('d-none alert-danger').addClass('alert-success').text(res.success).show();

                    setTimeout(function(){
                        location.reload(); // Reload the page after 2 seconds
                    }, 2000);
                } else {
                    // Display error alert
                    $('#editalert').removeClass('d-none').addClass('alert-danger').text(res.failed).show();
                }
            }
    
    
        });


    });
   


});