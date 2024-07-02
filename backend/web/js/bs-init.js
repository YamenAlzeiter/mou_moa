document.addEventListener('DOMContentLoaded', function() {
    initializeTooltips();

});

document.addEventListener('pjax:end', function() {
    initializeTooltips();

});

function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

