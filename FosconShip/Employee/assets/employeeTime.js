$(document).ready(function(){

    $('#empTime').click(function(){
        $.ajax({
            url: 'employeeTime.php',
            method: 'post',
            data:{
                timeIn: 1
            },
            success:function(response){
                let result = JSON.parse(response);
                if(result.success){
                    alert(result.success);
                    location.reload();
                }else{
                    alert(result.failed);
                }
            }

        })
    });

    $('.empoutTime').click(function(){
        const Id = $(this).attr('id');
        $.ajax({
            url: 'employeeTime.php',
            method: 'post',
            data:{
                timeOut: 1,
                empID: Id
            },
            success:function(response){
                let result = JSON.parse(response);
                if(result.success){
                    alert(result.success);
                    location.reload();
                }else{
                    alert(result.failed);
                }
            }

        })
    });


});