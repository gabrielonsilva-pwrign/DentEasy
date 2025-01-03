<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Editar Usuário</h1>
<form action="/users/update/<?= $user['id'] ?>" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Senha (deixe em branco para manter a mesma)</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="mb-3">
        <label for="group_id" class="form-label">Grupo</label>
        <select class="form-control" id="group_id" name="group_id" required>
            <?php foreach ($groups as $group): ?>
                <option value="<?= $group['id'] ?>" <?= $user['group_id'] == $group['id'] ? 'selected' : '' ?>><?= $group['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Atualizar Usuário</button>
</form>
<?= $this->endSection() ?>
