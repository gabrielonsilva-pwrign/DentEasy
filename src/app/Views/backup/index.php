<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Backups</h1>

<button id="instantBackup" class="btn btn-primary">Backup Instantâneo</button>

<h2>Histórico</h2>
<table class="table">
    <thead>
        <tr>
            <th>Arquivo</th>
            <th>Criado em</th>
            <th>Status</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($backups as $backup): ?>
        <tr>
            <td><?= $backup['filename'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($backup['created_at'])) ?></td>
            <td><?= $backup['status'] ?></td>
            <td><?= $backup['type'] ?></td>
            <td>
                <?php if ($backup['status'] === 'Completo'): ?>
                    <a href="/backup/download/<?= $backup['id'] ?>" class="btn btn-sm btn-info">Download</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Importar Backup</h2>
<form action="/backup/importBackup" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="backup_file" class="form-label">Backup File</label>
        <input type="file" class="form-control" id="backup_file" name="backup_file" required>
    </div>
    <button type="submit" class="btn btn-warning">Importar Backup</button>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('instantBackup').addEventListener('click', function() {
    fetch('/backup/instantBackup', {method: 'POST'})
        .then(response => response.json())
        .then(data => alert(data.message))
        .catch(error => console.error('Error:', error));
});
</script>
<?= $this->endSection() ?>
