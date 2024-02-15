$(document).ready(function(){
    $('.attendEmp').on('click', function(){
        const Id = $(this).attr('id');

        $.ajax({
            url: 'viewAttendance.php',
            method: 'post',
            data:{
                Id: 1,
                empID: Id
            },
            success:function(response){

                if(response.length > 0) {
                    // Assuming the first object in the array contains the employee's name
                    var employee = response[0];
            
                    // Display employee's name
                    $('#viewName').html(employee.lastname + ', ' + employee.firstname + ' ' + employee.middlename);
            
                    // Iterate over attendance records
                    response.forEach(function(attendance) {
                        // Create a new table row
                        var newRow = $('<tr>');
        
                        // Populate the row with attendance data
                        newRow.append('<td>' + attendance.date + '</td>');
                        newRow.append('<td>' + attendance.in_time + '</td>');
                        newRow.append('<td>' + attendance.out_time + '</td>');
                        newRow.append('<td>' + attendance.in_status + '</td>');
                        newRow.append('<td>' + attendance.out_status + '</td>');
        
                        // Append the new row to the table body of the attendance table
                        $('#attendanceTable tbody').append(newRow);
                    });
                }else{

                }

               
            }


        });


    });

    document.getElementById('reloadButton').addEventListener('click', function() {
        location.reload();
    });
    document.getElementById('reloadButton1').addEventListener('click', function() {
        location.reload();
    });



});