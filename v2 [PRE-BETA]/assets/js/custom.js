function pleaseWaitMsg() {
    toastr.warning('Please wait...')
}
function update10codeID(id) {
    var i = id.id;
    alert(i);
}
function changeSignal() {
    $.ajax({
        url: "inc/backend/user/dispatch/signal100.php",
        cache: false,
        success: function(result) {
            toastr.info('Please wait...', 'System:', {
                timeOut: 10000
            })
        }
    });
}
$(document).ready(function () {
    document.oncontextmenu = document.body.oncontextmenu = function () { return false; }
});

function disableClick() {
    $("input[type='submit']").attr("disabled", false);

    $("form").submit(function(){
        $("input[type='submit']").attr("disabled", true);
        setTimeout(function(){ $("input[type='submit']").attr("disabled", false); }, 5000);
        return true;
    })

    $("button[type='submit']").attr("disabled", false);
    $("form").submit(function(){
        $("button[type='submit']").attr("disabled", true);
        setTimeout(function(){ $("button[type='submit']").attr("disabled", false); }, 5000);
        return true;
    })
}
