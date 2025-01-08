<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php                                 
                    $userModel = new \App\Models\UserModel();
                    $permissions = $userModel->getPermissions(session()->get('user_id'));
                ?>
<h1>Agendamentos</h1>
<div class="mb-3">
<?php
                if (isset($permissions['appointments']) && in_array('add', $permissions['appointments'])):
            ?>
    <a href="/appointments/new" class="btn btn-primary">Adicionar Novo Agendamento</a>
    <?php endif; ?>
    <div class="btn-group" role="group">
        <a href="/appointments?view=calendar" class="btn btn-outline-secondary <?= $viewMode === 'calendar' ? 'active' : '' ?>">Calendário</a>
        <a href="/appointments?view=list" class="btn btn-outline-secondary <?= $viewMode === 'list' ? 'active' : '' ?>">Lista</a>
    </div>
</div>

<?php if ($viewMode === 'calendar'): ?>
    <div id="calendar"></div>
<?php else: ?>
    <div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Paciente</th>
                <th>Título</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($appointment['start_time'])) ?> - <?= date('H:i', strtotime($appointment['start_time'])) ?> - <?= date('H:i', strtotime($appointment['end_time'])) ?></td>
                <td><?= $appointment['patient_name'] ?></td>
                <td><?= $appointment['title'] ?></td>
                <td><?= ucfirst($appointment['status']) ?></td>
                <td>
                <?php
                if (isset($permissions['appointments']) && in_array('edit', $permissions['appointments'])):
            ?>
                    <a href="/appointments/edit/<?= $appointment['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <?php endif; ?>
                    <?php
                if (isset($permissions['appointments']) && in_array('delete', $permissions['appointments'])):
            ?>
                    <a href="/appointments/delete/<?= $appointment['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
                <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
                </div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
        locale: 'pt-BR',
        editable: true,
        buttonText: {
            today: 'hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'dia'
        },
        timeZone: 'America/Sao_Paulo',
        eventDrop: function(info) {
            updateAppointment(info.event);
        },
        eventResize: function(info) {
            updateAppointment(info.event);
        },
        eventClick: function(info) {
            window.location.href = '/appointments/edit/' + info.event.id;
        }
    });
    calendar.render();

    function updateAppointment(event)   {
        $.ajax({
            url: "<?php echo base_url(); ?>/appointments/updateAjax/" + event.id,
            type: "POST",
            dataType: "JSON",
            async: "false",
            data: {"start_time": event.start.toISOString(), "end_time": event.end.toISOString()},
            success: function(data) {
                // Do Nothing
            },
            error: function(x,e)   {
                if (x.status==0) {
                    alert('You are offline!!\n Please Check Your Network.');
                } else if(x.status==404) {
                    alert('Requested URL not found.');
                } else if(x.status==500) {
                    alert('Internel Server Error.');
                } else if(e=='parsererror') {
                    alert('Error.\nParsing JSON Request failed.');
                } else if(e=='timeout'){
                    alert('Request Time out.');
                } else {
                    alert('Unknow Error.\n'+x.responseText);
                }
            }
        })
    };
});
</script>
<?= $this->endSection() ?>
