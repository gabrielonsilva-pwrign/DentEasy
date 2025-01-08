<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php                                 
                    $userModel = new \App\Models\UserModel();
                    $permissions = $userModel->getPermissions(session()->get('user_id'));
                ?>
<h1>Estoque</h1>
<?php
                if (isset($permissions['inventory']) && in_array('add', $permissions['inventory'])):
            ?>
<a href="/inventory/new" class="btn btn-primary mb-3">Adicionar Novo Item</a>
<?php endif; ?>

<form action="/inventory" method="get" class="mb-3">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Pesquisar..." name="search" value="<?= $_GET['search'] ?? '' ?>">
        <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
    </div>
</form>

<?php if (!empty($lowStockItems)): ?>
<div class="alert alert-warning">
    <strong>Baixo Estoque:</strong>
    <?php foreach ($lowStockItems as $item): ?>
        <span class="badge bg-warning text-dark"><?= $item['name'] ?> (<?= $item['quantity'] ?>)</span>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th><a href="?order_by=id&order_dir=<?= ($_GET['order_by'] ?? '') == 'id' && ($_GET['order_dir'] ?? '') == 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
            <th><a href="?order_by=name&order_dir=<?= ($_GET['order_by'] ?? '') == 'name' && ($_GET['order_dir'] ?? '') == 'asc' ? 'desc' : 'asc' ?>">Nome</a></th>
            <th><a href="?order_by=quantity&order_dir=<?= ($_GET['order_by'] ?? '') == 'quantity' && ($_GET['order_dir'] ?? '') == 'asc' ? 'desc' : 'asc' ?>">Quantidade</a></th>
            <th><a href="?order_by=purchase_price&order_dir=<?= ($_GET['order_by'] ?? '') == 'purchase_price' && ($_GET['order_dir'] ?? '') == 'asc' ? 'desc' : 'asc' ?>">Preço</a></th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($inventory as $item): ?>
        <tr <?= $item['quantity'] <= $item['low_stock_threshold'] ? 'class="table-warning"' : '' ?>>
            <td><?= $item['id'] ?></td>
            <td><?= $item['name'] ?></td>
            <td><?= $item['quantity'] ?> <?= $item['unit'] ?></td>
            <td>R$ <?= number_format($item['purchase_price'], 2,",",".") ?></td>
            <td>
            <?php
                if (isset($permissions['inventory']) && in_array('edit', $permissions['inventory'])):
            ?>
                <a href="/inventory/edit/<?= $item['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <?php endif; ?>
                <?php
                if (isset($permissions['inventory']) && in_array('delete', $permissions['inventory'])):
            ?>
                <a href="/inventory/delete/<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Você tem certeza?')">Excluir</a>
                <?php endif; ?>
                <?php
                if (isset($permissions['inventory']) && in_array('view', $permissions['inventory'])):
            ?>
                <a href="/inventory/history/<?= $item['id'] ?>" class="btn btn-sm btn-info">Histórico</a>
                    <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
                </div>
<?= $pager->links() ?>
<?= $this->endSection() ?>
