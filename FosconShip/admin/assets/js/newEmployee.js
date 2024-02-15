$(document).ready(function(){

    $('#uploadnewemployee').on('submit', function(event){
        event.preventDefault();

        const lname = $('#lname').val();
        const fname = $('#fname').val();
        const mname = $('#mname').val();
        const birthday = $('#birthday').val();
        const address = $('#address').val();
        const email = $('#email').val();
        const contact = $('#contact').val();
        const gender = $('#gender').val();
        const hireddate = $('#hireddate').val();
        const Roleemp = $('#Roleemp').val();
        const deptID = $('#deptID').val();
        const empuser = $('#empuser').val();
        const emppass = $('#emppass').val();
        const salaryEmp = $('#salaryEmp').val();

    
        let datas = new FormData();
        datas.append('lname', lname);
        datas.append('fname', fname);
        datas.append('mname', mname);
        datas.append('birthday', birthday);
        datas.append('address', address);
        datas.append('email', email);
        datas.append('contact', contact);
        datas.append('gender', gender);
        datas.append('hireddate', hireddate);
        datas.append('Roleemp', Roleemp);
        datas.append('deptID', deptID);
        datas.append('empuser', empuser);
        datas.append('emppass', emppass);
        datas.append('salaryEmp', salaryEmp);
    
        let img = $('#empImage')[0].files;
    
        datas.append('empImage', img[0]);

        $.ajax({
            url: 'addEmployee.php',
            method: 'post',
            contentType: false,
            processData: false,
            data: datas,
            success:function(response){
                var result = JSON.parse(response);
                if(result.success){
                    // Display success alert
                    $('#alert').removeClass('d-none alert-danger').addClass('alert-success').text(result.success).show();

                    setTimeout(function(){
                        $('#alert').hide();
                        location.reload(); // Reload the page after 2 seconds
                    }, 2000);
                } else {
                    // Display error alert
                    $('#alert').removeClass('d-none').addClass('alert-danger').text(result.failed).show();
                }

            }
    
        });


    });
    

  
});