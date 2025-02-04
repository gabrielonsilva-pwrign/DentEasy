<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php                                 
                    $userModel = new \App\Models\UserModel();
                    $permissions = $userModel->getPermissions(session()->get('user_id'));
                ?>
<h1>Usuários</h1>
<?php
                if (isset($permissions['users']) && in_array('add', $permissions['users'])):
            ?>
<a href="/users/new" class="btn btn-primary mb-3">Adicionar Novo Usuário</a>
<?php endif; ?>
<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Grupo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['group_name'] ?></td>
            <td>
            <?php
                if (isset($permissions['users']) && in_array('edit', $permissions['users'])):
            ?>
                <a href="/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <?php endif; ?>
                <?php
                if (isset($permissions['users']) && in_array('delete', $permissions['users'])):
            ?>
                <a href="/users/delete/<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
                </div>
<?= $this->endSection() ?>
