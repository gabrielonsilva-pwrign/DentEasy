<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Adicionar Novo Grupo</h1>
<form action="/groups/create" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Nome do Grupo</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <h3>Permissões</h3>
    <div class="row">
        <div class="col-md-6">
    <?php
    $actions = [
        ['Name' => 'Visualizar', 'Action' => 'view'],
        ['Name' => 'Editar', 'Action' => 'edit'],
        ['Name' => 'Adicionar', 'Action' => 'add'],
        ['Name' => 'Excluir', 'Action' => 'delete']
    ];
    $modules = [ 
        ['Name' => 'Tratamentos', 'Module' => 'treatments'],
        ['Name' => 'Agendamentos', 'Module' => 'appointments'],
        ['Name' => 'Estoque', 'Module' => 'inventory'],
        ['Name' => 'Grupos', 'Module' => 'groups'],
        ['Name' => 'Usuários', 'Module' => 'users'],
        ['Name' => 'Dashboard', 'Module' => 'dashboard'],
        ['Name' => 'Webhooks', 'Module' => 'api'],
        ['Name' => 'Pacientes', 'Module' => 'patients'],
        ['Name' => 'Backups', 'Module' => 'backup']
    ];
    foreach ($modules as $index => $module):
        if ($index % 2 == 0 && $index != 0) echo '</div><div class="col-md-6">';
    ?>
        <h4><?= ucfirst($module['Name']) ?></h4>
        <?php foreach ($actions as $action): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[<?= $module['Module'] ?>][]" value="<?= $action['Action'] ?>" id="<?= $module['Module'] ?>_<?= $action['Action'] ?>"
                <label class="form-check-label" for="<?= $module['Name'] ?>_<?= $action['Action'] ?>">
                <?= ucfirst($action['Name']) ?>
                </label>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
        </div></div>
    <button type="submit" class="btn btn-primary mt-3">Adicionar Grupo</button>
</form>
<?= $this->endSection() ?>
