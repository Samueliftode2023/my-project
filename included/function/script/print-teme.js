var preventSign = 0;

function printPage(){
    editor = false
    window.print()
}

function readCaiet() {
    if(preventSign == 0){
        $('#pagina').html('')
        $('#loading-id').removeClass('dis-none')
        preventSign = 1
        var dataFrom = new FormData($('#print-teme')[0])

        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/print-teme.php",
            data: dataFrom,
            contentType: false, 
            processData: false,
            success: function (result) {
                try{
                    var teme = JSON.parse(result)
                    for (let index = 0; index < teme.length; index++) {
                        for (let second = 0; second < teme[index].length; second++) {                                
                            if(teme[index][second]['tema'] != 'asistent'){
                                var idTeme = teme[index][second]['year'] + teme[index][second]['week'] + teme[index][second]['range_pagina'];
                                idTeme = idTeme.replace(/\s+/g, '');
                                var rectificare = teme[index][second]['tema'].replace(/_/g, ' ');

                                var dataTemei = teme[index][second]['week']
                                if(dataTemei.length > 28){
                                    dataTemei = dataTemei.replaceAll(2025, "")
                                    dataTemei = dataTemei.replaceAll(2024, "")
                                    dataTemei = dataTemei.replaceAll(2026, "")
                                    dataTemei = dataTemei.replaceAll(2023, "")
                                }

                                var tema = "<div class='tema'>"
                                tema += '<h4>TEMĂ LA ÎNTRUNIREA <br>"VIAŢA CREŞTINĂ ŞI PREDICAREA"</h4>'
                                tema += "<div class='detalii'>"
                                tema += "<span>Nume:</span>"
                                tema += "<p contenteditable='true'>" + teme[index][second]['vestitor_id'] + "</p>"
                                tema += "</div>"
                                tema += "<div class='detalii'>"
                                tema += "<span>Partener:</span>"
                                tema += "<p id='" + idTeme + "' contenteditable='true'></p>"
                                tema += "</div>"
                                tema += "<div class='detalii'>"
                                tema += "<span>Data:</span>"
                                tema += "<p class='scade' contenteditable='true'>" + dataTemei + "</p>"
                                tema += "</div>"
                                tema += "<div class='detalii'>"
                                tema += "<span>Tema nr.:</span>"
                                tema += "<p class='creste' contenteditable='true'> " + teme[index][second]['range_pagina'] + ". " + rectificare + "</p>"
                                tema += "</div>"
                                var htmlContent = `
                                    <div class='sala-locatie'>
                                        <span>Se va ţine în:</span>
                                        <label>
                                            <input type='checkbox' checked><span>Sala principala</span>
                                        </label>
                                        <label>
                                            <input type='checkbox'><span>Prima sala secundara</span>
                                        </label>
                                        <label>
                                            <input type='checkbox'><span>A doua sala secundara</span>
                                        </label>
                                    </div>
                                    <div class='nota'>
                                        <b>Notă pentru cursant:</b> Materialul și lecția pentru tema repartizată sunt 
                                        indicate în Caietul pentru întrunirea <i>„Viața creștină și predicarea”</i>. 
                                        Când îți pregătești tema, te rugăm să ții cont de Instrucțiunile pentru întrunirea <i>
                                        „Viața creștină și predicarea”</i> (S-38).
                                    </div>
                                    <div class='file-code'>
                                        S-89-M 11/23
                                    </div>
                                `;
                                tema += htmlContent
                                tema += "</div>"
                                $('#pagina').append(tema)
                            }
                        }
                    }
                    for (let index = 0; index < teme.length; index++) {
                        for (let second = 0; second < teme[index].length; second++) { 
                            if(teme[index][second]['tema'] == 'asistent'){
                                var rangeSplit = teme[index][second]['range_pagina'].split('asistent-')
                                var idAsistent = teme[index][second]['year'] + teme[index][second]['week'] + rangeSplit[1]
                                idAsistent = idAsistent.replace(/\s+/g, '');
                                $('#' + idAsistent).html(teme[index][second]['vestitor_id'])
                            }
                        }
                    }
                }
                catch(e){
                    alert('Atentie - A aparut o eroare!')
                }
                preventSign = 0
                setTimeout(function() {
                    $('#loading-id').addClass('dis-none');
                }, 300);
            }
        })
    }
}

$(document).ready(function () {
    readCaiet()

    $('#print-teme').submit(function (event) {
        event.preventDefault();
    })
    $('#month, #year').change(readCaiet)
})
