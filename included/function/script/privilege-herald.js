var preventSign = 0;

function selectVestitori() {
    $('#vestitori-privilegii input[type="checkbox"]').each(function() {
        $(this).prop('checked', true);
    });
}

function deleselctVestitori() {
    $('#vestitori-privilegii input[type="checkbox"]').each(function() {
        $(this).prop('checked', false);
    });
}

function citireVestitori(){
    if(preventSign == 0){
        preventSign = 1
        $('#loading-id').removeClass('dis-none')
        var criteriu = $('#criterii').val()

        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/privilege-herald.php",
            data: {
                'filtru': criteriu
            },
            success: function (result) {
                    if(result.length == 4){
                        $('#vestitori-privilegii').html('<div class="mess">Momentan nu exista vestitori cu acest criteriu!</div>')
                    }
                    else{
                        $('#vestitori-privilegii').html(result)
                    }
                    preventSign = 0
                    setTimeout(function() {
                        $('#loading-id').addClass('dis-none');
                    }, 1000);
            }
        })
    }
}

function saveChanges(){
    if(preventSign == 0){
        preventSign = 1
        var vestitori = []
        var privilegii = []

        $('#loading-id').removeClass('dis-none')
        $('#vestitori-privilegii input[type="checkbox"]:checked').each(function() {
            vestitori.push($(this).val());
        });
        $('#vestitori-aprobari input[type="checkbox"]').each(function() {
            privilegii.push($(this).val());
        });

        vestitori = JSON.stringify(vestitori)
        privilegii = JSON.stringify(privilegii)

        if(vestitori.length > 0){
            $.ajax({
                type: "POST",
                url: "../../../included/function/exe/privilege-herald.php",
                data: {
                    'vestitori': vestitori,
                    'privilege': privilegii

                },
                success: function (result) {
                        preventSign = 0
                        setTimeout(function() {
                            $('#loading-id').addClass('dis-none');
                        }, 1000);
                }
            })
        }
        else{
            setTimeout(function() {
                $('#loading-id').addClass('dis-none');
            }, 1000);
            preventSign = 0
        }
    }
}

$(document).ready(function () {
    $('#vestitori-privilegii, #vestitori-aprobari').submit(function (event) {
        event.preventDefault();
    })
    $('#vestitori-aprobari input[type="checkbox"]').click(function(){
        var aprobari = $(this).val()
        if(aprobari == 'nu'){
            $(this).val('da')
        }
        else{
            $(this).val('nu')
        }
    })
    $('#select-vestitori').click(selectVestitori)
    $('#select-privilegii').click(deleselctVestitori)
    $('#criterii').change(citireVestitori)
    $('#save').click(saveChanges)
    citireVestitori()
})
