var preventSign = 0

function changeDataConn(dateForm){
    if(preventSign == 0){
        $('#loading-id').removeClass('dis-none')
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/change-data-connect.php",
            data: dateForm,
            contentType: false,
            processData: false,
            success: function (result) {
                result = result.trim()
                if(result == 'ok'){
                    location.reload();
                }
                else{
                    alert(result)
                }
                preventSign = 0
                setTimeout(function() {
                    $('#loading-id').addClass('dis-none');
                }, 300);
            }
        })
    }
}

$(document).ready(function() {
    $('#username-form, #password-form, #cheie-form').submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        changeDataConn(formData)
    })
});