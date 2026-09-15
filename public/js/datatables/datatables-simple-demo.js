window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple && typeof simpleDatatables !== 'undefined') {
        new simpleDatatables.DataTable(datatablesSimple);
    }
});
