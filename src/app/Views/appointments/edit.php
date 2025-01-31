<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Editar Agendamento</h1>
<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
<form action="/appointments/update/<?= $appointment['id'] ?>" method="post">
    <div class="mb-3">
        <label for="patient_id" class="form-label">Paciente</label>
        <select class="form-control" id="patient_id" name="patient_id" required>
            <option value="">Selecione um Paciente</option>
            <?php foreach ($patients as $patient): ?>
                <option value="<?= $patient['id'] ?>" <?= $patient['id'] == $appointment['patient_id'] ? 'selected' : '' ?>><?= $patient['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="title" value="<?= $appointment['title'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label">Hora de Início</label>
        <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?= date('Y-m-d\TH:i', strtotime($appointment['start_time'])) ?>" required>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">Hora de Fim</label>
        <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="<?= date('Y-m-d\TH:i', strtotime($appointment['end_time'])) ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea class="form-control tinymce" id="description" name="description"><?= $appointment['description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status" required>
            <option value="Agendado" <?= $appointment['status'] == 'Agendado' ? 'selected' : '' ?>>Agendado</option>
            <option value="Completo" <?= $appointment['status'] == 'Completo' ? 'selected' : '' ?>>Completo</option>
            <option value="Cancelado" <?= $appointment['status'] == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Atualizar Agendamento</button>
</form>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="/scripts/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.tinymce',
        license_key: 'gpl',
        language: 'pt_BR',
        height: 300,
        menubar: false,
        plugins: 'advlist autolink lists link charmap preview searchreplace visualblocks code fullscreen table help wordcount',
        toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    });
</script>
<?= $this->endSection() ?>