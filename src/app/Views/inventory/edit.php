<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Editar Item</h1>
<form action="/inventory/update/<?= $item['id'] ?>" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $item['name'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea class="form-control" id="description" name="description"><?= $item['description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">Quantidade</label>
        <input type="number" class="form-control" id="quantity" name="quantity" value="<?= $item['quantity'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="unit" class="form-label">Unidade</label>
        <input type="text" class="form-control" id="unit" name="unit" value="<?= $item['unit'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="purchase_price" class="form-label">Preço</label>
        <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="<?= $item['purchase_price'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="low_stock_threshold" class="form-label">Aviso de Baixo Estoque</label>
        <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="<?= $item['low_stock_threshold'] ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Atualizar Item</button>
</form>
<?= $this->endSection() ?>
