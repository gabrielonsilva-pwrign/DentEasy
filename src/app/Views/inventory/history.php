<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Histórico de Estoque: <?= $item['name'] ?></h1>
<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>Data</th>
            <th>Ação</th>
            <th>Quantidade</th>
            <th>Notas</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($history as $record): ?>
        <tr>
            <td><?= $record['created_at'] ?></td>
            <td><?= ucfirst($record['action']) ?></td>
            <td><?= $record['quantity'] ?></td>
            <td><?= $record['notes'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </div>
<a href="/inventory" class="btn btn-secondary">Voltar para Estoque</a>
<?= $this->endSection() ?>
