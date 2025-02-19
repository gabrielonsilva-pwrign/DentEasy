<?= $this->extend('layout/patient_portal') ?>

<?= $this->section('content') ?>
<h1>Olá, <?= esc($patient['name']) ?></h1>
<div class="row">
    <div class="col-md-6">
        <h2>Acesso Rápido</h2>
        <ul>
            <li><a href="/my/personal">Meus Dados</a></li>
            <li><a href="/my/history">Histórico de Atendimento</a></li>
            <li><a href="/my/appointments">Próximos Atendimentos</a></li>
        </ul>
    </div>
    <div class="col-md-6">
        <h2>Próximos Atendimentos</h2>
        <table class="table">
    <thead>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($appointments as $appointment): ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($appointment['start_time'])) ?> - <?= date('H:i', strtotime($appointment['start_time'])) ?></td>
            <td><?= esc($appointment['title']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>
</div>
<?= $this->endSection() ?>
