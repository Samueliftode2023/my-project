var preventSign = 0
var preventGenerate = 0
var newKeyCode;
var nameFileJs = 0
var pereventCopy = 0

function finishKey(dateForm){
    if(preventGenerate == 0){
        preventGenerate = 1
        $('#loading-id').removeClass('dis-none')
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: dateForm,
            contentType: false,
            processData: false,
            success: function (result) {
                if(result === 'ok'){
                    window.location.href = "../../dashboard/";
                }
                else{
                   $('body').html(result)
                }
                preventGenerate = 0
                setTimeout(function() {
                    $('#loading-id').addClass('dis-none');
                }, 1000);
            }
        })
    }
}

function sendData(dateForm){
    if(preventSign == 0){
        preventSign = 1
        $('#loading-id').removeClass('dis-none')
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: dateForm,
            contentType: false,
            processData: false,
            success: function (result) {
                try {
                    var obiectModificari = JSON.parse(result)
                    redaObiectul(obiectModificari[dateForm.get('lista')], dateForm.get('lista'))
                }
                catch(err) {
                    alert(result)
                }
                preventSign = 0
                checkSession()
            }
        })
    }
}

function checkSession(){
    if(preventSign == 0){
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: {"verificare-session-key":"true"},
            success: function (result) {
                if(result == 'activ'){
                    $('.bara-control button').removeClass('disabled-class')
                }
                else if(result == 'inactiv'){
                    $('.bara-control button').addClass('disabled-class')
                    $('#lista-vestitori-add, #lista-vestitori-edit, #lista-vestitori-delete').html('<div class="mesaj-lista">Momentan nu au fost facute modificari.</div>')
                }
                preventSign = 0
                listaVestitori('checkbox')
            }
        })
    }
}

function unsetNewKey(){
    if(preventSign == 0 && !$('#remove-new-key').hasClass('disabled-class')){
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: {"remove-new-key":"true"},
            success: function (result) {
                preventSign = 0
                checkSession()
            }
        })
    }
}

function listaVestitori(inputType){
    if(preventSign == 0){
        $('#loading-id').removeClass('dis-none')
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: {"type-input":inputType},
            success: function (result) {
                if(inputType == 'select'){
                    $('#vestitori-label').html('Alege vestitorul' + result)
                    $('#vestitori-label select').change(completeazaCampurile)
    
                    $('#new-name').val('')
                    $('#new-priv option, #new-gen option').eq(0).prop('selected', true);
                }
                else{
                    $('#sterge-vestitorii').html(result)
                }
                preventSign = 0
                setTimeout(function() {
                    $('#loading-id').addClass('dis-none');
                }, 300);
            }
        })
    }
}

function completeazaCampurile(){
    var idDeEditat = $(this).val()
    if(preventSign == 0){
        $('#loading-id').removeClass('dis-none')
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: {"get-date-vestitori":idDeEditat},
            success: function (result) {
                try{
                    var detaliiVestitor = JSON.parse(result)
                    if(detaliiVestitor != null){
                        $('#new-name').val(detaliiVestitor[0])

                        $('#new-priv option').filter(function() {
                            return $(this).text() === detaliiVestitor[1];
                        }).prop('selected', true);
                       
                        $('#new-gen option').filter(function() {
                            return $(this).text() === detaliiVestitor[2];
                        }).prop('selected', true);
                    }
                    else{
                        $('#new-name').val('')
                        $('#new-priv option, #new-gen option').eq(0).prop('selected', true);
                    }
                }
                catch(err){
                    $('#new-name').val('')
                    $('#new-priv option, #new-gen option').eq(0).prop('selected', true);
                }
                preventSign = 0
                setTimeout(function() {
                    $('#loading-id').addClass('dis-none');
                }, 1000);
            }
        })
    }
}

function get_list(lista){
    if(preventSign == 0){
        $('#loading-id').removeClass('dis-none')
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: {"obtine-lista":lista},
            success: function (result) {
                try{
                    var detaliiVestitor = JSON.parse(result)
                    redaObiectul(detaliiVestitor, lista)
                }
                catch(err){
                    $('#panouri-vizuale div').html(result)
                }
                preventSign = 0
                checkSession()
            }
        })
    }
}

function redaObiectul(detaliiVestitor, lista){
    $('#lista-vestitori-' + lista).html('')
    if(detaliiVestitor.length > 0){
        for (let index = 0; index < detaliiVestitor.length; index++) {
            var mark = ''
            var marcheaza = ''
            if(index % 2 !== 0){
                mark = 'class="marcheaza"'
                marcheaza = 'marcheaza'
            }
            var iconTrash = '<span id="'+ lista +'-'+ detaliiVestitor[index]['nume'] +'" onclick="anuleaza(this)" class="material-symbols-outlined remove-c ' + marcheaza + '">cancel</span>'
            var nume = '<div ' + mark + '>'+ detaliiVestitor[index]['nume'] + '</div>'
            var privilegiu = '<div ' + mark + '>' + detaliiVestitor[index]['privilegiu'] + '</div>'
            var gen = '<div ' + mark + '>' + detaliiVestitor[index]['gen'] + '</div>'
            var linie = nume + privilegiu + gen + iconTrash
            
            $('#lista-vestitori-' + lista).append(linie)
            $('#lista-vestitori-add, #lista-vestitori-edit, #lista-vestitori-delete').addClass('dis-none')
            $('#lista-vestitori-' + lista).removeClass('dis-none')
            $('#bara-pentru-lista div').removeClass('selectat')
            $('#vestitori-' + lista).addClass('selectat')
        }
    }
    else{
        $('#lista-vestitori-' + lista).html('<div class="mesaj-lista">Momentan nu au fost facute modificari.</div>')
    }
}

function anuleaza(el){
    if(preventSign == 0){
        $('#loading-id').removeClass('dis-none')
        preventSign = 1
        var separam = el.id.split('-')
        var lista = separam[0]
        var id = separam[1]

        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: {"anulare":lista,"nume-anulare":id},
            success: function (result) {
                try{
                    var detaliiVestitor = JSON.parse(result)
                    redaObiectul(detaliiVestitor[lista], lista)
                }
                catch(err){
                    $('#panouri-vizuale div').html(result)
                }
                preventSign = 0
                checkSession()
            }
        })
    }

}

$(document).ready(function() {
    get_list('add')
    
    $('#bara-pentru-selectat div, #bara-pentru-lista div').click(function() {
        let selectForm = $(this).attr('id');
        let parentId = $(this).parent().attr('id') 
        $('#' + parentId +' div').removeClass('selectat')
        $(this).addClass('selectat')

        if(parentId == 'bara-pentru-selectat'){
            $('#panou-editare form').addClass('dis-none')
            $('#' + selectForm + '-vestitor').removeClass('dis-none')
        }
        else{
            $('#lista-vestitori-add, #lista-vestitori-edit, #lista-vestitori-delete').addClass('dis-none')
            $('#lista-' + selectForm).removeClass('dis-none')
        }
    }); 

    $('#add-vestitor, #edit-vestitor, #delete-vestitor, #zona-finish').submit(function (event) {
        var getId = $(this).prop('id')
        event.preventDefault();
        var formData = new FormData(this);
        if(getId == 'zona-finish'){
            finishKey(formData)
        }
        else{        
            sendData(formData)
        }
    })
});

function generateKey(){
    if(preventGenerate == 0 && !$('#remove-new-key').hasClass('disabled-class')){
        $('#panel-download-key').removeClass('dis-none')
        $('#id-back-panel').removeClass('dis-none')
        $('#loading-id').removeClass('dis-none')
        $('#zona-finish').addClass('dis-none')
        $('#show-charge').html('Incarca noua cheie')
        $('#file-key').val('')
        preventSign = 1
        nameFileJs = 0
        preventGenerate = 1
        newKeyCode = null

        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/edit-herald.php",
            data: {"generate-key":'true'},
            success: function (result) {
                try{
                    var efectCopy = '<div class="dis-none" id="confrim-copy">Text copiat cu succes!</div>'
                    var newKey = JSON.parse(result)
                    newKeyCode = newKey 
                    $('#show-key').html(result + efectCopy)
                    $('#id-copy, #show-key, #butoane-gestionare-cheie').removeClass('dis-none')
                }
                catch(err){
                    alert(result)
                }
                preventGenerate = 0
                setTimeout(function() {
                    $('#loading-id').addClass('dis-none');
                }, 300);
            }
        })
    }
}

function copyCode(){
    if(pereventCopy == 0){
        pereventCopy = 1
        var copyText = document.getElementById("show-key");
        navigator.clipboard.writeText(copyText.innerHTML); 
        $('#confrim-copy').removeClass('dis-none')   
        setTimeout(function() {
            $('#confrim-copy').addClass('dis-none');
            pereventCopy = 0
        }, 1000);
    }
}

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

function downloadCode(){
    if(preventGenerate == 0){
        $('#loading-id, #zona-finish').removeClass('dis-none')
        preventGenerate = 1

        var jsonText = JSON.stringify(newKeyCode);
        var blob = new Blob([jsonText], { type: 'application/json' });
        var link = document.createElement('a');
        
        link.href = window.URL.createObjectURL(blob);
        link.download = getDataFormatata() + '.json';
        nameFileJs = getDataFormatata() + '.json';
        link.click();
        window.URL.revokeObjectURL(link.href);
    
        preventGenerate = 0
        setTimeout(function() {
            $('#loading-id').addClass('dis-none');
        }, 1000);
    }
}

function closePanel(){
    $('#panel-download-key, #id-back-panel, #zona-finish').addClass('dis-none')
    preventSign = 0
    nameFileJs = 0
    newKeyCode = null
    $('#show-key').html('')
    $('#id-copy, #show-key, #butoane-gestionare-cheie').addClass('dis-none')
    $('#show-charge').html('Incarca noua cheie')
    $('#file-key').val('')
}

function showUpload(){
    $('#show-charge').html('Fisierul este incarcat')
}

