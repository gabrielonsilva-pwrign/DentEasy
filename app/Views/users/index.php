<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Usuários</h1>
<a href="/users/new" class="btn btn-primary mb-3">Adicionar Novo Usuário</a>
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
            <td><?= $user['group_id'] ?></td>
            <td>
                <a href="/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="/users/delete/<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
