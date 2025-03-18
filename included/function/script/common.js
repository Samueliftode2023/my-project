var preventSign = 0;

function logOut(ids){
    var idul = ids.id
    var root = idul.split('||')
    root = root[1]
    if(preventSign == 0){
        preventSign = 1
        $.ajax({
            type: "POST",
            url: root + "included/function/exe/logout.php",
            data: {
                'logout':'1'
            },
            success: function (result) {
                location.reload();
            }
        })
    }
}