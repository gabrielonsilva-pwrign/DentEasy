<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Editar Webhook</h1>
<form action="/api/update/<?= $webhook['id'] ?>" method="post">
    <div class="mb-3">
        <label for="url" class="form-label">Webhook URL</label>
        <input type="url" class="form-control" id="url" name="url" value="<?= $webhook['url'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="is_active" class="form-label">Status</label>
        <select class="form-control" id="is_active" name="is_active">
            <option value="1" <?= $webhook['is_active'] ? 'selected' : '' ?>>Ativo</option>
            <option value="0" <?= !$webhook['is_active'] ? 'selected' : '' ?>>Inativo</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Atualizar Webhook</button>
</form>
<?= $this->endSection() ?>
