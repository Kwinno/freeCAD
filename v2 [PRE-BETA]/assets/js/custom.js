function pleaseWaitMsg() {
    toastr.warning('Please wait...')
}
$(document).ready(function () {
    document.oncontextmenu = document.body.oncontextmenu = function () { return false; }

    $("input[type='submit']").attr("disabled", false);

    $("form").submit(function(){
        $("input[type='submit']").attr("disabled", true);
        setTimeout(function(){ $("input[type='submit']").attr("disabled", false); }, 10000);
        return true;
        })
        $("button[type='submit']").attr("disabled", false);

    $("form").submit(function(){
        $("button[type='submit']").attr("disabled", true);
        setTimeout(function(){ $("button[type='submit']").attr("disabled", false); }, 10000);
        return true;
    })
});