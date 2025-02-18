<?= $this->extend('layout/patient_portal') ?>

<?= $this->section('content') ?>
<h1>Histórico de Tratamento</h1>
<table class="table">
    <thead>
        <tr>
            <th>Data</th>
            <th>Tratamento</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($treatments as $treatment): ?>
        <tr>
            <td><?= esc($treatment['created_at']) ?></td>
            <td><?= esc($treatment['title']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
