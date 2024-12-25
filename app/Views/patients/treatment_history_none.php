<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Histórico de Tratamento: <?= $patient['name'] ?></h1>

<table class="table">
    <thead>
        <tr>
            <th>Data</th>
            <th>Tratamento</th>
            <th>Valor</th>
            <th>Método de Pagamento</th>
            <th>Observações</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5">Nada para exibir</td>
        </tr>
    </tbody>
</table>

<a href="/patients" class="btn btn-secondary">Voltar</a>
<?= $this->endSection() ?>
