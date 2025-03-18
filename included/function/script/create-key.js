var preventSign = 0;
var congregatie = ''
var createJsonFile = false

function getDataFormatata() {
    var data = new Date();

    var an = data.getFullYear();
    var luna = data.getMonth() 
    var zi = data.getDate()
    var ora = data.getHours();
    var min = data.getMinutes()
    var sec = data.getSeconds()

    return an + '' + luna + '' + zi+ '' + ora + '' + min + '' + sec;
}
function copyDivText() {
    var divElement = document.getElementById("code-source");
    var range = document.createRange();
    range.selectNode(divElement);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand("copy");
    window.getSelection().removeAllRanges();
}
function createJson(congregatie){
    if(createJsonFile){
        var jsonText = JSON.stringify(congregatie);
        var blob = new Blob([jsonText], { type: 'application/json' });
        var link = document.createElement('a');
    
        $('#loading-id').removeClass('dis-none')
        document.getElementById('code-source').innerHTML = jsonText 
        document.getElementById('name-file').innerHTML = getDataFormatata()
        document.getElementById('vestitori').classList.add('dis-none')
        document.getElementById('send-to-upload').classList.remove('dis-none')
    
        link.href = window.URL.createObjectURL(blob);
        link.download = getDataFormatata() + '.json';
        link.click();
        window.URL.revokeObjectURL(link.href);
    
        setTimeout(function() {
            $('#loading-id').addClass('dis-none');
        }, 1000);
    }
}
function uploadFile(preventSign){
    if(preventSign == 0){
        $('#loading-id').removeClass('dis-none')
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../../included/function/exe/create-key.php",
            data: {send:'ok'},
            success: function (result) {
                if (result == 'ok') {
                    window.location.href = "../upload-key/";
                }
            }
        })
    }
}
function readSession(result){
    $('#tabel-locatie').html('')
    congregatie = JSON.parse(result)
    var countArray = congregatie['vestitori'].length
    if(countArray >= 2){
        $('#finish').addClass('ready')
        createJsonFile = true
    }
    for (let index = 0; index < countArray; index++) {
        var idV = congregatie['vestitori'][index]['id']
        var numeV = congregatie['vestitori'][index]['nume']
        var privV = congregatie['vestitori'][index]['privilegiu']
        var genV = congregatie['vestitori'][index]['gen']
        var tabel = '<tr><td>' + idV + '</td><td>' + numeV + '</td><td>' + privV + '</td><td>' + genV + '</td></tr>'
        $('#tabel-locatie').append(tabel)
    }
}
function cathSession(preventSign){
    if(preventSign == 0){
        $('#loading-id').removeClass('dis-none')
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../../included/function/exe/create-key.php",
            data: {read:'read'},
            success: function (result) {
                var verficare = result.split(' - ')
                if(verficare[0] !== 'Atentie'){
                    $('#name-full').val('')
                    readSession(result)
                    preventSign = 0
                    setTimeout(function() {
                        $('#loading-id').addClass('dis-none');
                    }, 1000);
                }
                else if(verficare[0] == 'Atentie'){
                    $('#name-full').val('')
                    $('#display-message').text(result);
                    preventSign = 0
                    setTimeout(function() {
                        $('#loading-id').addClass('dis-none');
                    }, 1000);
                }
            }
        })
    }
}
$(document).ready(function () {
    cathSession(preventSign)
    $('#vestitori').submit(function (event) {
        if(preventSign == 0){
            $('#loading-id').removeClass('dis-none')
            preventSign = 1
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "../../../../included/function/exe/create-key.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    var verficare = result.split(' - ')
                    if(verficare[0] !== 'Atentie'){
                        $('#name-full').val('')
                        readSession(result)
                        preventSign = 0
                        setTimeout(function() {
                            $('#loading-id').addClass('dis-none');
                        }, 1000);
                    }
                    else if(verficare[0] == 'Atentie'){
                        $('#name-full').val('')
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