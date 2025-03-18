var editor = true
var preventSign = 0;
var saptamanileSelectate = [];
var filterActive = 'off'

function activeEditor() {
    if(editor){
        $('#switch-edit').addClass('activ')
        document.getElementById('switcher').style.left = '30px'
        document.getElementById('meetings').contentEditable = true
        editor = false
    }
    else{
        $('#switch-edit').removeClass('activ')
        document.getElementById('switcher').style.left = '0px'
        document.getElementById('meetings').contentEditable = false
        editor = true
    }
}

function printPage(){
    editor = false
    activeEditor()
    window.print()
}

function readCaiet() {
    if(preventSign == 0){
        preventSign = 1
        var year = $('#year').val()
        var month = $('#month').val()
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/print-meeting.php",
            data: {
                'year':year,
                'month':month
            },
            success: function (result) {
                $('#meetings').html(result)
                preventSign = 0
            }
        })
    }
}

function activeWeekTable(){
    if(preventSign == 0 && filterActive == 'off'){
        saptamanileSelectate = []
        preventSign = 1
        $('#blur-back-id').removeClass('dis-none')
        var year = $('#year').val()
        var month = $('#month').val()
        if (!$._data($('.select-saptamani div')[0], 'events')?.click) {
            $('.select-saptamani div').click(selectWeekDiv);
        }
        $('.select-saptamani div').removeClass('back-select')
        $('.select-saptamani div').removeClass('evidentiaza')
        $('#year-filter').val(year)

        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/print-meeting.php",
            data: {
                'year-fil':year,
                'month-fil':month
            },
            success: function (result) {
                try{
                    var weekMonth = JSON.parse(result)

                    for (let index = 0; index < weekMonth.length; index++) {
                        $('#' + 'week-' + year + '-' + weekMonth[index]).addClass('back-select')
                        var stringOb = '{"' + year + '":' + weekMonth[index] + '}'
                        stringOb = JSON.parse(stringOb)
                        saptamanileSelectate.push(stringOb)
                    }
                }
                catch(e){

                }
                var element = document.getElementById('week-' + year + '-1');
                for (let index = 1; index < 60; index++) {
                    $('#' + 'week-' + year + '-' + index).addClass('evidentiaza')
                }
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                preventSign = 0
            }
        })
    }
    else if(preventSign == 0 && filterActive == 'on'){
        $('#blur-back-id').removeClass('dis-none')
    }
}

function closeWeek(){
        $('#blur-back-id').addClass('dis-none')
        $('.select-saptamani div').removeClass('evidentiaza')
        $('.select-saptamani div').removeClass('back-select')
        saptamanileSelectate = [];
        filterActive = 'off'
        $('#year, #month').prop('disabled', false);
}

function upWeek(){
    var year = $('#year-filter').val()
    year = +year

    if(year > 2024){
        year = year - 1
    }
    $('#year-filter').val(year)
    getWeekAfter()
}

function downWeek(){
    var year = $('#year-filter').val()
    year = +year

    if(year < 2026){
        year = year + 1
    }
    $('#year-filter').val(year)
    getWeekAfter()
}

function getWeekAfter(){
    $('.select-saptamani div').removeClass('evidentiaza')
    var yearFil = $('#year-filter').val()

    var element = document.getElementById('week-' + yearFil + '-1');
    for (let index = 1; index < 60; index++) {
        $('#' + 'week-' + yearFil + '-' + index).addClass('evidentiaza')
    }
    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function selectWeekDiv() {

        var weekId = $(this).prop('id').split('-'); 
        var weekKey = weekId[1];
        var weekValue = weekId[2];  
    
        var foundIndex = -1; 
        for (let index = 0; index < saptamanileSelectate.length; index++) {
            if (saptamanileSelectate[index][weekKey] == weekValue) {
                foundIndex = index;  
                break; 
            }
        }
    
        if (foundIndex !== -1) {
            if(saptamanileSelectate.length > 1){
                saptamanileSelectate.splice(foundIndex, 1);  
                $(this).removeClass('back-select'); 
            }
            else{
                alert('Trebuie sa selectezi ce putin o saptamana!')
            } 
        } else {
            if(saptamanileSelectate.length < 10){
                var newWeekIn = {};
                newWeekIn[weekKey] = parseInt(weekValue); 
                saptamanileSelectate.push(newWeekIn); 
                $(this).addClass('back-select');  
            }   
            else{
                alert('Poti selecta maxim 10 saptamani!')
            } 
        }
}

function aplicaFiltrarea(){
    if(preventSign == 0){
        preventSign = 1
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/print-meeting.php",
            data: {
                'filter':saptamanileSelectate
            },
            success: function (result) {
                $('#meetings').html(result)
                filterActive = 'on'
                $('#blur-back-id').addClass('dis-none')
                $('#year, #month').prop('disabled', true);
                preventSign = 0
            }
        })
    }
}

$(document).ready(function () {
    readCaiet()
    $('#print-meeting').submit(function (event) {
        event.preventDefault();
    })
    $('#month, #year').change(readCaiet)
})