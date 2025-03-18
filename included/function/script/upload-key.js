var preventSign = 0;

function showUpload(el){
    var nameFile = event.target.files[0]
    $('#upload-label').addClass('active-upload')
    $('#upload-label').text(nameFile.name)
}

$(document).ready(function () {
    $('#upload-key').submit(function (event) {
        if(preventSign == 0){
            $('#loading-id').removeClass('dis-none')
            preventSign = 1
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "../../../../included/function/exe/upload-key.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    if(result === 'ok'){
                        window.location.href = "../../../dashboard/";
                    }
                    else{
                        alert(result)
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