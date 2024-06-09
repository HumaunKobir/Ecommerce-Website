$(document).ready(function(){
    //Check Admin Pasword is correct or not
    $("#current_password").keyup(function(){
        var current_password= $("#current_password").val();
        // alert(current_password);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type:'post',
            url:'/admin/check-current-password',
            data:{current_password:current_password},
            success:function(resp){
                if(resp=="true"){
                    $("#check_password").html("<font color='green'>Current Password is Correct!</font>");
                }else if(resp=="false"){
                    $("#check_password").html("<font color='red'>Current Password is Incorrect</font>");
                } 
            },error:function(){
                alert('Error');
            }
        });
    })
});