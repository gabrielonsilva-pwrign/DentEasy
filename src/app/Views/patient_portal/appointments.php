<?= $this->extend('layout/patient_portal') ?>

<?= $this->section('content') ?>
<h1>Próximas Consultas</h1>
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
<?= $this->endSection() ?>
