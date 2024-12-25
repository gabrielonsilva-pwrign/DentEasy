<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Histórico de Tratamento: <?= $patient['name'] ?></h1>

<table class="table">
    <thead>
        <tr>
            <th>Data</th>
            <th>Tratamento</th>
            <th>Valor</th>
            <th>Método de Pagamento</th>
            <th>Observações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($treatments as $treatment): ?>
        <tr>
            <td><?= $treatment['created_at'] ?></td>
            <td><?= $treatment['title'] ?></td>
            <td>R$ <?= number_format($treatment['value'], 2, ',','.') ?></td>
            <td><?= ucfirst(str_replace('_', ' ', $treatment['payment_method'])) ?></td>
            <td><?= $treatment['notes'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="/patients" class="btn btn-secondary">Voltar</a>
<?= $this->endSection() ?>
