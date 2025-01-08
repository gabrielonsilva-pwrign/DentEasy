<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Detalhes</h1>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Tratamento #<?= $treatment['id'] ?></h5>
        <p class="card-text"><strong>Paciente:</strong> <?= $treatment['patient_name'] ?></p>
        <p class="card-text"><strong>Data:</strong> <?= date('Y-m-d H:i', strtotime($treatment['appointment_date'])) ?></p>
        <p class="card-text"><strong>Preço:</strong> R$ <?= number_format($treatment['value'], 2, ',','.') ?></p>
        <p class="card-text"><strong>Método de Pagamento:</strong> <?= ucfirst(str_replace('_', ' ', $treatment['payment_method'])) ?></p>
        <p class="card-text"><strong>Notas:</strong> <?= $treatment['notes'] ?></p>
    </div>
</div>

<h2 class="mt-4">Itens Usados</h2>
<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>Item</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($treatment_items as $item): ?>
        <tr>
            <td><?= $item['name'] ?></td>
            <td><?= $item['quantity'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </div>
<h2 class="mt-4">Arquivos</h2>
<ul>
    <?php foreach ($treatment_files as $file): ?>
        <li>
            <a href="/uploads/treatments/<?= $file['file_name'] ?>" target="_blank"><?= $file['original_name'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="/treatments/edit/<?= $treatment['id'] ?>" class="btn btn-warning mt-3">Editar Tratamento</a>
<a href="/treatments" class="btn btn-secondary mt-3">Voltar</a>
<?= $this->endSection() ?>
