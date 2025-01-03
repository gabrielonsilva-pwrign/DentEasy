<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Editar Tratamento</h1>
<form action="/treatments/update/<?= $treatment['id'] ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="appointment_id" class="form-label">Agendamento</label>
        <select class="form-control" id="appointment_id" name="appointment_id" required>
            <?php foreach ($appointments as $appointment): ?>
                <option value="<?= $appointment['id'] ?>" <?= $appointment['id'] == $treatment['appointment_id'] ? 'selected' : '' ?>>
                    <?= $appointment['patient_name'] . ' - ' . date('m/d/Y H:i', strtotime($appointment['start_time'])) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="value" class="form-label">Preço</label>
        <input type="number" step="0.01" class="form-control" id="value" name="value" value="<?= $treatment['value'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="payment_method" class="form-label">Método de Pagamento</label>
        <select class="form-control" id="payment_method" name="payment_method" required>
            <option value="Dinheiro" <?= $treatment['payment_method'] == 'cash' ? 'selected' : '' ?>>Dinheiro</option>
            <option value="Credito" <?= $treatment['payment_method'] == 'credit_card' ? 'selected' : '' ?>>Cartão de Crédito</option>
            <option value="Debito" <?= $treatment['payment_method'] == 'debit_card' ? 'selected' : '' ?>>Cartão de Débito</option>
            <option value="Convenio" <?= $treatment['payment_method'] == 'insurance' ? 'selected' : '' ?>>Convênio</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label">Notas</label>
        <textarea class="form-control" id="notes" name="notes"><?= $treatment['notes'] ?></textarea>
    </div>
    <h3>Itens Usados</h3>
    <?php foreach ($inventory_items as $item): ?>
    <div class="mb-3">
        <label for="inventory_<?= $item['id'] ?>" class="form-label"><?= $item['name'] ?></label>
        <input type="number" class="form-control" id="inventory_<?= $item['id'] ?>" name="inventory[<?= $item['id'] ?>]" 
               value="<?= isset($treatment_items[$item['id']]) ? $treatment_items[$item['id']]['quantity'] : 0 ?>" 
               min="0" max="<?= $item['quantity'] + (isset($treatment_items[$item['id']]) ? $treatment_items[$item['id']]['quantity'] : 0) ?>">
        <small class="form-text text-muted">Available: <?= $item['quantity'] + (isset($treatment_items[$item['id']]) ? $treatment_items[$item['id']]['quantity'] : 0) ?></small>
    </div>
    <?php endforeach; ?>
    <div class="mb-3">
        <label for="treatment_files" class="form-label">Novos Arquivos</label>
        <input type="file" class="form-control" id="treatment_files" name="treatment_files[]" multiple>
    </div>
    <h3>Arquivos</h3>
    <ul>
        <?php foreach ($treatment_files as $file): ?>
            <li>
                <?= $file['original_name'] ?>
                <a href="/treatments/deleteFile/<?= $file['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir esse arquivo?')">Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <button type="submit" class="btn btn-primary">Atualizar Tratamento</button>
</form>
<?= $this->endSection() ?>
