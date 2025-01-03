<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Agendamentos</h1>
<div id="calendar"></div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '/appointments/getAppointments',
        eventClick: function(info) {
            window.location.href = '/appointments/edit/' + info.event.id;
        },
        locale: 'pt-BR'
    });
    calendar.render();
});
</script>
<?= $this->endSection() ?>
