<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php                                 
                    $userModel = new \App\Models\UserModel();
                    $permissions = $userModel->getPermissions(session()->get('user_id'));
                ?>
<h1>Webhooks</h1>
<?php
                if (isset($permissions['api']) && in_array('add', $permissions['api'])):
            ?>
<a href="/api/new" class="btn btn-primary mb-3">Adicionar novo Webhook</a>
<?php endif ;?>
<div class="table-responsive">
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
            <?php
                if (isset($permissions['api']) && in_array('edit', $permissions['api'])):
            ?>
                <a href="/api/edit/<?= $webhook['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <?php endif; ?>
                <?php
                if (isset($permissions['api']) && in_array('delete', $permissions['api'])):
            ?>
                <a href="/api/delete/<?= $webhook['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
                </div>
<?= $this->endSection() ?>
