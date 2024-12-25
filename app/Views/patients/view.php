<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Detalhes do Paciente</h1>
<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $patient['name'] ?></h5>
        <p class="card-text"><strong>Email:</strong> <?= $patient['email'] ?></p>
        <p class="card-text"><strong>CPF:</strong> <?= $patient['cpf'] ?></p>
        <p class="card-text"><strong>Genero:</strong> <?= ucfirst($patient['gender']) ?></p>
        <p class="card-text"><strong>Idade:</strong> <?= $age ?></p>
        <p class="card-text"><strong>Celular:</strong> <?= $patient['mobile_phone'] ?></p>
        <p class="card-text"><strong>Endereço:</strong> <?= $patient['address'] ?></p>
        <p class="card-text"><strong>Histórico Médico:</strong> <?= $patient['medical_history'] ?></p>
    </div>
</div>
<a href="/patients/edit/<?= $patient['id'] ?>" class="btn btn-warning mt-3">Editar</a>
<a href="/patients/treatmentHistory/<?= $patient['id'] ?>" class="btn btn-info mt-3">Histórico de Tratamento</a>
<a href="/patients" class="btn btn-secondary mt-3">Voltar</a>
<?= $this->endSection() ?>
