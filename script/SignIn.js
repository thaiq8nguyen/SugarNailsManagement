$(document).ready(function(){
    var $signin = $('#signinForm');
    var $errorMsg = $('#ErrorMsg');



    $signin.submit(function(event){

        event.preventDefault();
        if(validation()){

            $.post('/php/UserAuthentication.php',$signin.serializeArray(),function(response){
                console.log(response);
                if(response === 'pass'){

                    window.location.href = 'home.php';
                }
                else{
                    $errorMsg.removeClass().addClass('alert alert-danger').text("Username or password is not correct");
                }
            });
        }
    });

    function validation(){

        var isValid = true;
        if($('#username').val().length == 0 || $('#password').val().length == 0){
            $errorMsg.removeClass().addClass('alert alert-danger').text('Complete username and password');
            isValid = false;
        }

        return isValid;
    }
});




