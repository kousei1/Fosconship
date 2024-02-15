$(document).ready(function(){
$('.editemp').on('click', function(){
const Id = $(this).attr('id');

    $.ajax({
        url: 'displayemployee.php',
        method: 'POST',
        data:{
            getdata: 1,
            employee: Id

        },
        dataType: 'json',
        success:function(response){
            // var result = JSON.parse(response);

            if(response.length > 0){
                $('#viewEmpID').val(response[0].empID);
                $('#viewfname').val(response[0].firstname);
                $('#viewlname').val(response[0].lastname);
                $('#viewmname').val(response[0].middlename);
                $('#viewemail').val(response[0].email);
                $('#viewaddress').val(response[0].address);
                $('#viewbirthday').val(response[0].birthday);
                if (response && response.length > 0 && response[0].image) {
                    $('#Employeeimg').html('<img src="data:image/jpeg;base64,' + response[0].image + '" style="width: 150px; height: 130px;">');
                } 
                $('#viewgender').val(response[0].Gender);
                $('#viewcontact').val(response[0].contact);
                $('#viewhireddate').val(response[0].hired_date);
                $('#viewdeptID').val(response[0].name);
                $('#viewsalaryEmp').val(response[0].salaryEmp);
            }else{

            }

        },
    

    });




});



});