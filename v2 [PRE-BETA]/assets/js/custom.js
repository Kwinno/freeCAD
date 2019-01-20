function pleaseWaitMsg() {
    toastr.warning('Please wait...')
}
$(document).ready(function () {
    document.oncontextmenu = document.body.oncontextmenu = function () { return false; }
});