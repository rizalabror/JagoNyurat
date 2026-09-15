// For more details see: https://getbootstrap.com/docs/5.0/components/toasts/#usage

window.addEventListener('DOMContentLoaded', event => {

    const toastBasicEl = document.getElementById('toastBasic');
    const toastNoAutohideEl = document.getElementById('toastNoAutohide');
    
    let toastBasic, toastNoAutohide;
    if (toastBasicEl && typeof bootstrap !== 'undefined') toastBasic = new bootstrap.Toast(toastBasicEl);
    if (toastNoAutohideEl && typeof bootstrap !== 'undefined') toastNoAutohide = new bootstrap.Toast(toastNoAutohideEl);

    const toastBasicTrigger = document.getElementById('toastBasicTrigger');
    if (toastBasicTrigger && toastBasic) {
        toastBasicTrigger.addEventListener('click', event => {
            console.log('asd');
            toastBasic.show();
        });
    }

    const toastNoAutohideTrigger = document.getElementById('toastNoAutohideTrigger');
    if (toastNoAutohideTrigger && toastNoAutohide) {
        toastNoAutohideTrigger.addEventListener('click', event => {
            toastNoAutohide.show();
        });
    }

})
