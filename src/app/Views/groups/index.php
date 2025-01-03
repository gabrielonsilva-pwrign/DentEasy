<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Grupos</h1>
<a href="/groups/new" class="btn btn-primary mb-3">Adicionar novo Grupo</a>
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
                <a href="/groups/edit/<?= $group['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="/groups/delete/<?= $group['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
