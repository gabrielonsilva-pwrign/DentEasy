<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Editar Grupo</h1>
<form action="/groups/update/<?= $group['id'] ?>" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Nome do Grupo</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $group['name'] ?>" required>
    </div>
    <h3>Permissões</h3>
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
    foreach ($modules as $module):
    ?>
        <h4><?= ucfirst($module['Name']) ?></h4>
        <?php foreach ($actions as $action): ?>
            <div class="form-check">
                 <input class="form-check-input" type="checkbox" name="permissions[<?= $module['Module'] ?>][]" value="<?= $action['Action'] ?>" id="<?= $module['Module'] ?>_<?= $action['Action'] ?>"
                    <?= in_array($action['Action'], $group['permissions'][$module['Module']] ?? []) ? 'checked' : '' ?>>
                <label class="form-check-label" for="<?= $module['Name'] ?>_<?= $action['Action'] ?>">
                    <?= ucfirst($action['Name']) ?>
                </label>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary mt-3">Atualizar Grupo</button>
</form>
<?= $this->endSection() ?>
