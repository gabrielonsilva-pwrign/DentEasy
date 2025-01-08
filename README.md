# Aplicativo de gerenciamento de clínica odontológica

Este aplicativo é um sistema de gerenciamento abrangente para clínicas odontológicas, criado usando o framework CodeIgniter 4 PHP. Ele inclui vários módulos para lidar com diferentes aspectos do gerenciamento da clínica.

## Instalação

Para instalar essa aplicação, utilize o **docker-compose-example.yaml** disponível em *docker*

O compose foi criado para trabalhar com SWARM. Adapte conforme necessário caso não seja SWARM.

### Variáveis ENV Aplicação

- **admin_name**: "${Admin_Name}" # Nome do usuário Administrador
- **admin_email**: "${Admin_Email}" # Email do usuário Administrador
- **admin_password**: "${Admin_Passowrd}" # Senha do Usuário Administrador
- **database_default_password**: ${DB_Password} # Senha do Banco de Dados (Mesma de Env Database)
- **app_baseURL**: "${App_URL}" # URL da plataforma (https://exemplo.com.br)

### Variáveis ENV Database
- **MYSQL_ROOT_PASSWORD**: ${DB_Password} # Senha do Banco de Dados (Mesma de Env Aplicação)

## Módulos

### 1. Autenticação

- **Login**: permite que os usuários façam login no sistema.
- **Logout**: permite que os usuários façam logout do sistema.

### 2. Usuários

- **Operações CRUD**: criar, ler, atualizar e excluir usuários.
- **Associação com grupos**: os usuários são associados a grupos específicos para controle de acesso baseado em função.

### 3. Grupos

- **Operações CRUD**: criar, ler, atualizar e excluir grupos de usuários.
- **Gerenciamento de permissões**: definir permissões para diferentes módulos e ações.

### 4. Pacientes

- **Operações CRUD**: criar, ler, atualizar e excluir registros de pacientes.
- **Pesquisar**: pesquisar pacientes por nome, e-mail ou CPF.
- **Histórico de Tratamento**: Veja o histórico de tratamento de cada paciente.

### 5. Agendamentos

- **Operações CRUD**: Crie, Leia, Atualize e Exclua agendamentos.
- **Visualização de Calendário**: Veja agendamentos em um formato de calendário.
- **Visualização de Lista**: Veja agendamentos em um formato de lista.
- **Arraste e Solte**: Atualize os horários dos agendamentos por meio de arrastar e soltar na visualização de calendário.

### 6. Tratamentos

- **Operações CRUD**: Criar, Ler, Atualizar e Excluir registros de tratamento.
- **Upload de arquivo**: Capacidade de carregar arquivos associados a tratamentos.
- **Uso de inventário**: Rastrear itens de inventário usados ​​em tratamentos.

### 7. Inventário

- **Operações CRUD**: Criar, Ler, Atualizar e Excluir itens de inventário.
- **Gerenciamento de estoque**: Rastrear níveis de estoque e receber alertas de estoque baixo.
- **Preço de compra**: Registrar preço de compra para cada item de inventário.

### 8. Painel

- **Métricas principais**: Exibir métricas principais como valor total de tratamentos, consultas futuras e gastos de inventário.
- **Filtro de intervalo de datas**: Filtrar dados do painel por intervalo de datas.
- **Cache**: Implementado cache para melhorar o desempenho do painel.

### 9. API

- **Gerenciamento de webhook**: Gerenciar configurações de webhook para integrações externas.
- **Notificações de compromissos**: envie notificações de webhook para novos compromissos.

## Principais funções e métodos

### Autenticação

- `Auth::login()`: Exibir formulário de login.
- `Auth::attemptLogin()`: Processar tentativa de login.
- `Auth::logout()`: Processar logout do usuário.

### Usuários

- `Users::index()`: Listar todos os usuários.
- `Users::new()`: Exibir formulário para criar um novo usuário.
- `Users::create()`: Processar criação de novo usuário.
- `Users::edit($id)`: Exibir formulário para editar um usuário.
- `Users::update($id)`: Processar atualização do usuário.
- `Users::delete($id)`: Excluir um usuário.
- `UsersModel::getPermissions($id)`: Localiza as permissões do usuário.

### Grupos

- `Groups::index()`: Listar todos os grupos.
- `Groups::new()`: Exibir formulário para criar um novo grupo.
- `Groups::create()`: Processa a criação de um novo grupo.
- `Groups::edit($id)`: Exibe o formulário para editar um grupo.
- `Groups::update($id)`: Processa a atualização do grupo.
- `Groups::delete($id)`: Exclui um grupo.

### Pacientes

- `Pacientes::index()`: Lista todos os pacientes.
- `Pacientes::new()`: Exibe o formulário para criar um novo paciente.
- `Pacientes::create()`: Processa a criação de um novo paciente.
- `Pacientes::edit($id)`: Exibe o formulário para editar um paciente.
- `Pacientes::update($id)`: Processa a atualização do paciente.
- `Pacientes::delete($id)`: Exclui um paciente.
- `Pacientes::view($id)`: Exibe os detalhes do paciente.
- `Patients::treatmentHistory($id)`: Visualizar histórico de tratamento do paciente.
- `PatientModel::search()`: Pesquisar pacientes.
- `PatientModel::getAge()`: Calcular a idade do paciente.

### Agendamentos

- `Appointments::index()`: Exibe agendamentos (calendário ou visualização de lista).
- `Appointments::getAppointments()`: Obtém agendamentos para visualização de calendário.
- `Appointments::new()`: Exibe formulário para criar um novo agendamento.
- `Appointments::create()`: Processa a criação de um novo agendamento.
- `Appointments::edit($id)`: Exibe formulário para editar um agendamento.
- `Appointments::update($id)`: Processa atualização de agendamento.
- `Appointments::delete($id)`: Exclui um agendamento.

### Tratamentos

- `Treatments::index()`: Lista todos os tratamentos.
- `Treatments::new()`: Exibe formulário para criar um novo tratamento.
- `Treatments::create()`: Processa a criação de um novo tratamento.
- `Treatments::edit($id)`: Exibe formulário para editar um tratamento.
- `Treatments::update($id)`: Processa atualização do tratamento.
- `Treatments::delete($id)`: Exclui um tratamento.
- `Treatments::view($id)`: Exibe detalhes do tratamento.
- `Treatments::deleteFile($id)`: Exclui um arquivo associado a um tratamento.

### Inventário

- `Inventory::index()`: Lista todos os itens de inventário.
- `Inventory::new()`: Exibe formulário para criar um novo item de inventário.
- `Inventory::create()`: Processa criação de novo item de inventário.
- `Inventory::edit($id)`: Exibe formulário para editar um item de inventário.
- `Inventory::update($id)`: Processa atualização do item de inventário.
- `Inventory::delete($id)`: Exclui um item de inventário.
- `Inventory::history($id)`: Visualizar histórico de um item de inventário.
- `InventoryModel::search()`: Pesquisar itens de inventário.
- `InventoryModel::getLowStockItems()`: Obter itens com estoque baixo.

### Painel

- `Dashboard::index()`: Exibir painel com métricas-chave.
- `Dashboard::clearCache()`: Limpar cache do painel.
- `DashboardModel::getTreatmentsSum()`: Obter soma de valores de tratamentos para um intervalo de datas.
- `DashboardModel::getWeekAppointments()`: Obter agendamentos para a próxima semana.
- `DashboardModel::getInventoryAdditionsSum()`: Obter soma de valores de inventário para um intervalo de datas.

### API

- `Api::index()`: Lista todas as configurações de webhook.
- `Api::new()`: Exibe formulário para criar um novo webhook.
- `Api::create()`: Processa a criação de um novo webhook.
- `Api::edit($id)`: Exibe formulário para editar um webhook.
- `Api::update($id)`: Processa atualização de webhook.
- `Api::delete($id)`: Exclui um webhook.
- `Api::sendWebhook($data)`: Envia notificação de webhook.

## Recursos adicionais

- **Mascaramento de entrada**: Campos de CPF e telefone celular usam máscaras de entrada para entrada de dados consistente.
- **Paginação**: As visualizações de lista incluem paginação para melhor desempenho e experiência do usuário.
- **Classificação**: As visualizações de lista permitem a classificação por colunas diferentes.
- **Uploads de arquivo**: O módulo de tratamentos permite uploads e gerenciamento de arquivos.
- **Controle de acesso baseado em função**: as permissões do usuário são gerenciadas por meio de atribuições de grupo.
- **Cache**: o Dashboard implementa o cache para melhorar o desempenho.

## Dependências

- CodeIgniter 4
- Banco de dados MySQL
- Inputmask (para mascaramento de entrada)
- FullCalendar (para visualização do calendário de compromissos)
- JQuery
- Boostrap