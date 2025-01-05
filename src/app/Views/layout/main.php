<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DentEasy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">DentEasy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <?php if(session()->get('user_id')) { ?>
                <?php                                 
                    $userModel = new \App\Models\UserModel();
                    $permissions = $userModel->getPermissions(session()->get('user_id'));
                ?>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                <?php
                                if (isset($permissions['dashboard']) && in_array('view', $permissions['dashboard'])):
                            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <?php endif; ?>
                    <?php
                                if (isset($permissions['patients']) && in_array('view', $permissions['patients'])):
                            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/patients">Pacientes</a>
                    </li>
                    <?php endif; ?>
                    <?php
                                if (isset($permissions['appointments']) && in_array('view', $permissions['appointments'])):
                            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/appointments">Agendamentos</a>
                    </li>
                    <?php endif; ?>
                    <?php
                                if (isset($permissions['treatments']) && in_array('view', $permissions['treatments'])):
                            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/treatments">Tratamentos</a>
                    </li>
                    <?php endif; ?>
                    <?php
                                if (isset($permissions['inventory']) && in_array('view', $permissions['inventory'])):
                            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/inventory">Estoque</a>
                    </li>
                    <?php endif; ?>

                    <?php
                                if (
                                    (isset($permissions['users']) && in_array('view', $permissions['users'])) || 
                                    (isset($permissions['groups']) && in_array('view', $permissions['groups'])) || 
                                    (isset($permissions['api']) && in_array('view', $permissions['api']))
                                    ):
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Administração</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                                if (isset($permissions['users']) && in_array('view', $permissions['users'])):
                            ?>
                            <a class="dropdown-item" href="/users">Usuários</a>
                            <?php endif; ?>
                            <?php
                                if (isset($permissions['groups']) && in_array('view', $permissions['groups'])):
                            ?>
                            <a class="dropdown-item" href="/groups">Grupos</a>
                            <?php endif; ?>
                            <?php
                                if (isset($permissions['api']) && in_array('view', $permissions['api'])):
                            ?>
                            <a class="dropdown-item" href="/api">API</a>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Sair</a>
                    </li>
                </ul>
            </div>
            <?php } ?>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
