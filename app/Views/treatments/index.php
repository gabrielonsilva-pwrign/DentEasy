<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Tratamentos</h1>
<a href="/treatments/new" class="btn btn-primary mb-3">Adicionar Novo Tratamento</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Paciente</th>
            <th>Data</th>
            <th>Preço</th>
            <th>Método de Pagamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($treatments as $treatment): ?>
        <tr>
            <td><?= $treatment['id'] ?></td>
            <td><?= $treatment['patient_name'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($treatment['appointment_date'])) ?></td>
            <td>R$ <?= number_format($treatment['value'], 2,',','.') ?></td>
            <td><?= ucfirst(str_replace('_', ' ', $treatment['payment_method'])) ?></td>
            <td>
                <a href="/treatments/view/<?= $treatment['id'] ?>" class="btn btn-sm btn-info">Ver</a>
                <a href="/treatments/edit/<?= $treatment['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="/treatments/delete/<?= $treatment['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $pager->links() ?>
<?= $this->endSection() ?>
