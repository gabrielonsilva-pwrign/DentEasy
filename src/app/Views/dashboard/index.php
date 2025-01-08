<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1>Dashboard</h1>

<form action="/dashboard" method="get" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <label for="start_date" class="form-label">Data de Início</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $startDate ?>">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">Data de Fim</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $endDate ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end justify-content-start gap-2">
            <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
            <a href="/dashboard/clearCache" class="btn btn-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Entrada de Caixa</h5>
                <p class="card-text">R$ <?= number_format($treatmentsSum, 2,',','.') ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Saída de Estoque</h5>
                <p class="card-text">R$ <?= number_format($inventoryAdditionsSum, 2, ',','.') ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Balanço do Período</h5>
                <p class="card-text">R$ <?= number_format($treatmentsSum-$inventoryAdditionsSum, 2,',','.') ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Atendimentos nesta semana</h5>
                <p class="card-text"><?= count($weekAppointments) ?></p>
            </div>
        </div>
    </div>
</div>

<h2 class="mt-4">Próximos Atendimentos</h2>
<table class="table">
    <thead>
        <tr>
            <th>Data</th>
            <th>Paciente</th>
            <th>Atendimento</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($weekAppointments as $appointment): ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($appointment->start_time)) ?> - <?= date('H:i', strtotime($appointment->start_time)) ?></td>
            <td><?= $appointment->patient_name ?></td>
            <td><?= $appointment->title ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
