<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Webhooks</h1>
<a href="/api/new" class="btn btn-primary mb-3">Adicionar novo Webhook</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>URL</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($webhooks as $webhook): ?>
        <tr>
            <td><?= $webhook['id'] ?></td>
            <td><?= $webhook['url'] ?></td>
            <td><?= $webhook['is_active'] ? 'Ativo' : 'Inativo' ?></td>
            <td>
                <a href="/api/edit/<?= $webhook['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="/api/delete/<?= $webhook['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
