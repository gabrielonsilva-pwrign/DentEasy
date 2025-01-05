<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Grupos</h1>
<?php                                 
                    $userModel = new \App\Models\UserModel();
                    $permissions = $userModel->getPermissions(session()->get('user_id'));
                ?>
                <?php
                if (isset($permissions['groups']) && in_array('add', $permissions['groups'])):
            ?>
<a href="/groups/new" class="btn btn-primary mb-3">Adicionar novo Grupo</a>
<?php endif ;?>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($groups as $group): ?>
        <tr>
            <td><?= $group['id'] ?></td>
            <td><?= $group['name'] ?></td>
            <td>
            <?php
                if (isset($permissions['groups']) && in_array('edit', $permissions['groups'])):
            ?>
                <a href="/groups/edit/<?= $group['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <?php endif ;?>
                <?php
                if (isset($permissions['groups']) && in_array('delete', $permissions['groups'])):
            ?>
                <a href="/groups/delete/<?= $group['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            <?php endif ;?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
