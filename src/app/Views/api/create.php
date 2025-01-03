<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Adicionar novo Webhook</h1>
<form action="/api/create" method="post">
    <div class="mb-3">
        <label for="url" class="form-label">Webhook URL</label>
        <input type="url" class="form-control" id="url" name="url" required>
    </div>
    <div class="mb-3">
        <label for="is_active" class="form-label">Status</label>
        <select class="form-control" id="is_active" name="is_active">
            <option value="1">Ativo</option>
            <option value="0">Inativo</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Adicionar Webhook</button>
</form>
<?= $this->endSection() ?>
