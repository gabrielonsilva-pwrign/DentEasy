<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Novo Tratamento</h1>
<form action="/treatments/create" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="appointment_id" class="form-label">Agendamento</label>
        <select class="form-control" id="appointment_id" name="appointment_id" required>
            <option value="">Selecione um Agendamento</option>
            <?php foreach ($appointments as $appointment): ?>
                <option value="<?= $appointment['id'] ?>"><?= $appointment['patient_name'] . ' - ' . date('d/m/Y - H:i', strtotime($appointment['start_time'])) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="value" class="form-label">Preço</label>
        <input type="number" step="0.01" class="form-control" id="value" name="value" required>
    </div>
    <div class="mb-3">
        <label for="payment_method" class="form-label">Método de Pagamento</label>
        <select class="form-control" id="payment_method" name="payment_method" required>
            <option value="Dinheiro">Dinheiro</option>
            <option value="Credito">Cartão de Crédito</option>
            <option value="Debito">Cartão de Débito</option>
            <option value="Convenio">Convênio</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label">Notas</label>
        <textarea class="form-control tinymce" id="notes" name="notes"></textarea>
    </div>
    <h3>Itens Usados</h3>
    <?php foreach ($inventory_items as $item): ?>
    <div class="mb-3">
        <label for="inventory_<?= $item['id'] ?>" class="form-label"><?= $item['name'] ?></label>
        <input type="number" class="form-control" id="inventory_<?= $item['id'] ?>" name="inventory[<?= $item['id'] ?>]" value="0" min="0" max="<?= $item['quantity'] ?>">
        <small class="form-text text-muted">Available: <?= $item['quantity'] ?></small>
    </div>
    <?php endforeach; ?>
    <div class="mb-3">
        <label for="treatment_files" class="form-label">Arquivos</label>
        <input type="file" class="form-control" id="treatment_files" name="treatment_files[]" multiple>
    </div>
    <button type="submit" class="btn btn-primary">Adicionar Tratamento</button>
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