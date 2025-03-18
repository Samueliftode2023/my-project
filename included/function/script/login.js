var preventSign = 0;

$(document).ready(function () {
    $('#login').submit(function (event) {
        if(preventSign == 0){
            $('#loading-id').removeClass('dis-none')
            preventSign = 1
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "../../included/function/exe/login.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    if(result === 'ok'){
                        window.location.href = "../../main/";
                    }
                    else{
                        grecaptcha.reset();
                        $('#display-message').text(result);
                        preventSign = 0
                        setTimeout(function() {
                            $('#loading-id').addClass('dis-none');
                        }, 1000);
                    }
                }
            })
        }
    })
})