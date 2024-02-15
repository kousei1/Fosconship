$(document).ready(function(){

    $('.resignEmp').on('click', function(){
        
        if (confirm('Please double check before you may resign this employee')) {
            // Save it!
            const Id = $(this).attr('id');
            $.ajax({
                url: 'employeeResign.php',
                method: 'post',
                data:{
                    resign: 1,
                    IDReign: Id
                },
                success:function(response){
                    let res = JSON.parse(response);

                    if(res.success){
                        alert(res.success);
                        location.reload();
                    }else{
                        alert(res.failed);
                        location.reload();
                    }

                }

            });
            console.log('Thing was saved to the database.');
          } else {
            // Do nothing!
          }

    });
    

});