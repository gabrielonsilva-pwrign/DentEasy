<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php                                 
                    $userModel = new \App\Models\UserModel();
                    $permissions = $userModel->getPermissions(session()->get('user_id'));
                ?>
<h1>Pacientes</h1>
<?php
                if (isset($permissions['patients']) && in_array('add', $permissions['patients'])):
            ?>
<a href="/patients/new" class="btn btn-primary mb-3">Adicionar Novo Paciente</a>
<?php endif; ?>

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
            <?php
                if (isset($permissions['patients']) && in_array('view', $permissions['patients'])):
            ?>
                <a href="/patients/view/<?= $patient['id'] ?>" class="btn btn-sm btn-info">Ver</a>
            <?php endif; ?>
            <?php
                if (isset($permissions['patients']) && in_array('edit', $permissions['patients'])):
            ?>
                <a href="/patients/edit/<?= $patient['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <?php endif; ?>
            <?php
                if (isset($permissions['patients']) && in_array('delete', $permissions['patients'])):
            ?>
                <a href="/patients/delete/<?= $patient['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            <?php endif; ?>
            <?php
                if (isset($permissions['patients']) && in_array('view', $permissions['patients'])):
            ?>
                <a href="/patients/treatmentHistory/<?= $patient['id'] ?>" class="btn btn-sm btn-secondary">Histórico</a>
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $pager->links() ?>
<?= $this->endSection() ?>
