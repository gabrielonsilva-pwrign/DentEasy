<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php                                 
                    $userModel = new \App\Models\UserModel();
                    $permissions = $userModel->getPermissions(session()->get('user_id'));
                ?>
<h1>Tratamentos</h1>
<?php
                if (isset($permissions['treatments']) && in_array('add', $permissions['treatments'])):
            ?>
<a href="/treatments/new" class="btn btn-primary mb-3">Adicionar Novo Tratamento</a>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Paciente</th>
            <th>Data</th>
            <th>Preço</th>
            <th>Método de Pagamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($treatments as $treatment): ?>
        <tr>
            <td><?= $treatment['id'] ?></td>
            <td><?= $treatment['patient_name'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($treatment['appointment_date'])) ?></td>
            <td>R$ <?= number_format($treatment['value'], 2,',','.') ?></td>
            <td><?= ucfirst(str_replace('_', ' ', $treatment['payment_method'])) ?></td>
            <td>
            <?php
                if (isset($permissions['treatments']) && in_array('view', $permissions['treatments'])):
            ?>
                <a href="/treatments/view/<?= $treatment['id'] ?>" class="btn btn-sm btn-info">Ver</a>
                <?php endif; ?>
                <?php
                if (isset($permissions['treatments']) && in_array('edit', $permissions['treatments'])):
            ?>
                <a href="/treatments/edit/<?= $treatment['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <?php endif; ?>
                    <?php
                if (isset($permissions['treatments']) && in_array('delete', $permissions['treatments'])):
            ?>
                <a href="/treatments/delete/<?= $treatment['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $pager->links() ?>
<?= $this->endSection() ?>
