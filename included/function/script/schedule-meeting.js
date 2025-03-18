var preventSign = 0
var preventClick = 0
var suggestionMode = 'null'
var preventSuggestion = 0

function readInfo(event){
    if(preventClick == 0){
        $('#content-sugestii').html('')
        $('#get-sugestii').removeClass('dis-none')
        preventClick = 1
        var curentWeek = $('#week').val()
        var curentYear = $('#year').val()
        var numeSelect = $(this).attr('name');
        var numeColoana = numeSelect.split('-')
        var numeSectiune = numeSelect.split('-')
        numeSectiune = numeSectiune[0].replace(/_/g, " ");
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/schedule-meeting.php",
            data: {
                'curent-week':curentWeek,
                'curent-year':curentYear,
                'column':numeColoana[0]
            },
            success: function (result) {
                preventClick = 0
                $('#search-for').text('Istoric: ' + numeSectiune)
                $('#content-istoric').html(result)
                suggestionMode = numeColoana[0]
            }
        })
    }
}

function getSuggestion(){
    if(suggestionMode != 'null' && preventSuggestion == 0){
        $('#get-sugestii').addClass('dis-none')
        preventSuggestion = 1
        var curentWeek = $('#week').val()
        var curentYear = $('#year').val()
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/schedule-meeting.php",
            data: {
                'suggestion':'get',
                'curent-week':curentWeek,
                'curent-year':curentYear,
                'column':suggestionMode
            },
            success: function (result) {
                $('#content-sugestii').html(result)
                preventSuggestion = 0
            }
        })
    }
    else{
        
    }
}

function readSelect(event){
    if(preventSign == 0){
        preventSign = 1;
        $('select').prop('disabled', true);
        var numeSelect = $(this).attr('name');
        var idVestitor = $(this).val();
        numeSelect = numeSelect.split('-')
        var rangeTema = numeSelect[1] 
        var numeTema = numeSelect[0]
        if(numeSelect[0] == 'asistent'){
            rangeTema = 'asistent-' + rangeTema
        }
        var year = $('#year').val()
        var week = $('#week').val()

        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/schedule-meeting.php",
            data: {
                'year-meeting':year,
                'week':week,
                'tema':numeTema,
                'range':rangeTema,
                'id':idVestitor 
            },
            success: function (result) {
                preventSign = 0;
                $('select').prop('disabled', false);
            }
        })
    }
}

function readCaiet() {
    if(preventSign == 0){
        $('#search-for').text('Istoric:')
        $('#content-istoric').html('')
        $('#loading-id').removeClass('dis-none')
        $('#content-sugestii').html('')
        $('#get-sugestii').removeClass('dis-none')
        suggestionMode = 'null'
        
        preventSign = 1
        var year = $('#year').val()
        var week = $('#week').val()
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/schedule-meeting.php",
            data: {
                'year':year,
                'week':week
            },
            success: function (result) {
                var meesEror = result
                result = result.split('|sec|')
                var countResult = result.length
                if(countResult == 2){
                    $('#caiet-view').html(result[0])
                    $('#caiet-planificare').html(result[1])
                    $('#caiet-planificare select').change(readSelect);
                    $('#caiet-planificare select').click(readInfo)
                    $('#get-sugestii').click(getSuggestion)
                    $('#caiet-planificare select').change(markSelect)
                    markSelect()
                    preventSign = 0
                    setTimeout(function() {
                        $('#loading-id').addClass('dis-none');
                    }, 1000);
                }
                else{
                    $('#caiet-view').html(meesEror)
                    $('#caiet-planificare').html('')
                    preventSign = 0
                    setTimeout(function() {
                        $('#loading-id').addClass('dis-none');
                    }, 1000);
                }
            }
        })
    }
}

function togglePlanificari(){
    if($('#text-plan').hasClass('mode-planificare')){
        $('#text-plan').removeClass('mode-planificare')
        $('#edit-plan').addClass('mode-planificare')
        document.getElementById('caiet-planificare').style.zIndex = '3'
    }
    else{
        $('#edit-plan').removeClass('mode-planificare')
        $('#text-plan').addClass('mode-planificare')
        document.getElementById('caiet-planificare').style.zIndex = ''
    }
}

function markSelect() {
    var markColor = [
        "#FF5733", "#33FF57", "#3357FF", "#FF33A8", "#A833FF",
        "#33FFF5", "#FFD700", "#FF8C00", "#8B4513", "#00FA9A",
        "#DC143C", "#2E8B57", "#4682B4", "#800080"
    ];
    var idSelectGroup = [];
    var idForMark = [];
    var selects = $('#caiet-planificare select');
    selects.css('background', 'white'); 

    selects.each(function() {
        var idSelect = $(this).val();
        if (idSelect !== 'no') {
            if (!idSelectGroup.includes(idSelect)) {
                idSelectGroup.push(idSelect);
            } else if (!idForMark.includes(idSelect)) {
                idForMark.push(idSelect);
            }
        }
    });

    idForMark.forEach((id, index) => {
        selects.each(function() {
            if ($(this).val() === id) {
                $(this).css('background', markColor[index % markColor.length]); 
            }
        });
    });
}

$(document).ready(function () {
    readCaiet()
    $('#schedule-meeting').submit(function (event) {
        event.preventDefault();
    })
    $('#week, #year').change(readCaiet)
})
