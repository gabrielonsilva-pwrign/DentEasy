<?= $this->extend('layout/patient_portal') ?>

<?= $this->section('content') ?>
<h1>Meus Dados</h1>
<table class="table">
    <tr>
        <th>Nome</th>
        <td><?= esc($patient['name']) ?></td>
    </tr>
    <tr>
        <th>E-mail</th>
        <td><?= esc($patient['email']) ?></td>
    </tr>
    <tr>
        <th>Celular</th>
        <td><?= esc($patient['mobile_phone']) ?></td>
    </tr>
    <tr>
        <th>Endereço</th>
        <td><?= esc($patient['address']) ?></td>
    </tr>
</table>
<?= $this->endSection() ?>
