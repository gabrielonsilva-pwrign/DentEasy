<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Pacientes</h1>
<a href="/patients/new" class="btn btn-primary mb-3">Adicionar Novo Paciente</a>

<form action="/patients" method="get" class="mb-3">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Pesquisar paciente..." name="search" value="<?= $_GET['search'] ?? '' ?>">
        <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th><a href="?order_by=id&order_dir=<?= ($_GET['order_by'] ?? '') == 'id' && ($_GET['order_dir'] ?? '') == 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
            <th><a href="?order_by=name&order_dir=<?= ($_GET['order_by'] ?? '') == 'name' && ($_GET['order_dir'] ?? '') == 'asc' ? 'desc' : 'asc' ?>">Nome</a></th>
            <th>Email</th>
            <th>Genero</th>
            <th>Idade</th>
            <th>Celular</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($patients as $patient): ?>
        <tr>
            <td><?= $patient['id'] ?></td>
            <td><?= $patient['name'] ?></td>
            <td><?= $patient['email'] ?></td>
            <td><?= ucfirst($patient['gender']) ?></td>
            <td><?= (new DateTime($patient['birth_date']))->diff(new DateTime())->y ?></td>
            <td><?= $patient['mobile_phone'] ?></td>
            <td>
                <a href="/patients/view/<?= $patient['id'] ?>" class="btn btn-sm btn-info">Ver</a>
                <a href="/patients/edit/<?= $patient['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="/patients/delete/<?= $patient['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
                <a href="/patients/treatmentHistory/<?= $patient['id'] ?>" class="btn btn-sm btn-secondary">Histórico</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $pager->links() ?>
<?= $this->endSection() ?>
