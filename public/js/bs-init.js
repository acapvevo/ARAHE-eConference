document.addEventListener('DOMContentLoaded', function () {

    var charts = document.querySelectorAll('[data-bss-chart]');

    for (var chart of charts) {
        chart.chart = new Chart(chart, JSON.parse(chart.dataset.bssChart));
    }

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}, false);
