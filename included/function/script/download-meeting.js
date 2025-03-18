var preventSign = 0;

function filtrareCaiet(caiet){
    var sectiune = 0
    var indiceParagraf = -1
    var intruniri = {
        introducere:{
            data:'',
            capitole:'',
            cantarea:''
        },
        comori:[],
        predicare:[],
        crestin:[]
        }
    $('#filtrare').html(caiet);
    intruniri.introducere['data'] = $('#filtrare header h1').text()
    intruniri.introducere['capitole'] = $('#filtrare header h2').text()

    $('#filtrare').html($('#filtrare').children().eq(1).children().eq(1));
    $("#filtrare *").each(function(){
        var valoare = $(this).text()
        // AI ADAUGAT && sectiune < 3 IN CAZ DE EROARE
        if(this.nodeType === 1 && this.tagName === 'H2' && sectiune < 3){
            sectiune++
            indiceParagraf = -1
        }
        else if(this.nodeType === 1 && this.tagName === 'H3'){
            if(sectiune == 0){
                intruniri.introducere['cantarea'] = valoare
            }
            else if(sectiune == 1){
                intruniri['comori'].push({title:valoare,paragraf:''}) 
                indiceParagraf++
            }
            else if(sectiune == 2){
                intruniri['predicare'].push({title:valoare,paragraf:''}) 
                indiceParagraf++
            }
            else if(sectiune == 3){
                intruniri['crestin'].push({title:valoare,paragraf:''}) 
                indiceParagraf++
            }
        }
        else if(this.nodeType === 1 && this.tagName === 'P' && indiceParagraf != -1){
            if(sectiune == 1){
                intruniri['comori'][indiceParagraf].paragraf += valoare
            }
            else if(sectiune == 2){
                intruniri['predicare'][indiceParagraf].paragraf += valoare
            }
            else if(sectiune == 3){
                intruniri['crestin'][indiceParagraf].paragraf += valoare
            }
        }
    });
    return JSON.stringify(intruniri);
}
$(document).ready(function () {
    $('#download-meeting').submit(function (event) {
        if(preventSign == 0){
            $('#loading-id').removeClass('dis-none')
            preventSign = 1
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "../../../included/function/exe/download-meeting.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    var check_file = result.split('|sep|')
                    var count = check_file.length
                    if(count <= 1){
                        mess = 'Caietul aferent datei nu exista!'
                        $('#display-message').text(mess);
                        preventSign = 0
                        setTimeout(function() {
                            $('#loading-id').addClass('dis-none');
                        }, 1000);
                    }
                    else{
                        var lastElement = JSON.parse(check_file[check_file.length - 1])
                        count = count - 1
                        for (let i = 0; i < count; i++) {
                            var caiet = filtrareCaiet(check_file[i]) + '||' + lastElement[i]
                            $.ajax({
                                type: "POST",
                                url: "../../../included/function/exe/download-meeting.php",
                                data:{
                                    'meetings':caiet,
                                    'year':formData.get('meeting-year'),
                                },
                                success: function (result) {
                                }
                            })
                        }
                        mess = 'Descarcare completa!'
                        $('#display-message').text(mess);
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