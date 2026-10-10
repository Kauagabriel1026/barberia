<?php
require_once __DIR__ . '/../app/helpers/SessionHelper.php';
require_once __DIR__ . '/../app/controllers/AgendamentoController.php';

// Verificar se o usuário está logado
SessionHelper::verificarLogin();

// Definir parâmetros de paginação
$itensPorPagina = 6;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaAtual < 1) $paginaAtual = 1;

// Buscar todos os agendamentos
$controller = new AgendamentoController();
$todosAgendamentos = $controller->listarTodos();

// Calcular totais
$totalAgendamentos = count($todosAgendamentos);
$totalHoje = 0;
$receitaTotal = 0;
$totalPaginas = ceil($totalAgendamentos / $itensPorPagina);

// Garantir que a página atual não seja maior que o total de páginas
if ($paginaAtual > $totalPaginas && $totalPaginas > 0) {
    $paginaAtual = $totalPaginas;
}

// Calcular índices para a página atual
$indiceInicio = ($paginaAtual - 1) * $itensPorPagina;
$agendamentos = array_slice($todosAgendamentos, $indiceInicio, $itensPorPagina);

foreach ($todosAgendamentos as $agendamento) {
    $receitaTotal += $agendamento['valor_total'];
    // Verificar se é para hoje
    if ($agendamento['data'] == date('Y-m-d')) {
        $totalHoje++;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #212529;
            --card-border-primary: #4361ee;
            --card-border-success: #2ecc71;
            --card-border-info: #3498db;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 1px 6px rgba(0,0,0,0.1);
        }
        
        .container-main {
            padding: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            font-size: calc(1.2rem + 0.6vw);
        }
        
        .summary-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            height: 100%;
            position: relative;
            overflow: hidden;
            border-left: 3px solid;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        
        .summary-card h6 {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .summary-card h2 {
            font-weight: 700;
            margin-bottom: 0;
            font-size: calc(1.5rem + 1vw);
        }
        
        .border-primary-custom {
            border-color: var(--card-border-primary);
        }
        
        .text-primary-custom {
            color: var(--card-border-primary);
        }
        
        .border-success-custom {
            border-color: var(--card-border-success);
        }
        
        .text-success-custom {
            color: var(--card-border-success);
        }
        
        .border-info-custom {
            border-color: var(--card-border-info);
        }
        
        .text-info-custom {
            color: var(--card-border-info);
        }
        
        .card-icon {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            font-size: 1.5rem;
            opacity: 0.8;
        }
        
        .data-table {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            margin-top: 1.5rem;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        @media (max-width: 576px) {
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .search-actions {
                width: 100%;
            }
        }
        
        .table-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .table-title i {
            margin-right: 0.5rem;
        }
        
        .search-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            white-space: nowrap;
        }
        
        .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
        
        .avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        
        .user-info {
            min-width: 0;
        }
        
        .user-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-info small {
            color: #6c757d;
            font-size: 0.8rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .badge-sim {
            background-color: #2ecc71;
            color: white;
            font-weight: 500;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        
        .badge-nao {
            background-color: #6c757d;
            color: white;
            font-weight: 500;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            margin-left: 0.25rem;
        }
        
        .search-box {
            position: relative;
            flex: 1;
            max-width: 300px;
        }
        
        .search-box input {
            padding-left: 2.5rem;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            height: 38px;
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }
        
        .no-data {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .no-data i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }
        
        .no-data p {
            color: #6c757d;
            margin: 0;
        }
        
        @media (max-width: 767.98px) {
            .container-main {
                padding: 1rem;
            }
            
            .summary-card {
                padding: 1.25rem;
                margin-bottom: 1rem;
            }
            
            .card-icon {
                font-size: 1.25rem;
                right: 1rem;
                top: 1rem;
            }
            
            .table-responsive {
                border-radius: 8px;
            }
        }
        
        .table-responsive {
            border-radius: 0 0 8px 8px;
        }
    </style>
</head>
<body>
    <!-- Navbar superior -->
    <nav class="navbar navbar-expand navbar-dark">
        <div class="container-fluid px-5">
            <a class="navbar-brand" href="#"><b>Painel de Controle</b></a>
            <div class="ms-auto d-flex align-items-center text-white">
                <span class="me-3">Bem-vindo, <?php echo htmlspecialchars(SessionHelper::get('admin_username')); ?></span>
                <a href="../api/endpoints/auth.php?action=logout" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right me-1"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container-main">
        
        <!-- Cards de resumo -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="summary-card border-primary-custom">
                    <h6>Total de Agendamentos</h6>
                    <h2 class="text-primary-custom"><?php echo $totalAgendamentos; ?></h2>
                    <i class="bi bi-calendar-check card-icon text-primary-custom"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card border-success-custom">
                    <h6>Para Hoje</h6>
                    <h2 class="text-success-custom"><?php echo $totalHoje; ?></h2>
                    <i class="bi bi-calendar2-day card-icon text-success-custom"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card border-info-custom">
                    <h6>Receita Total</h6>
                    <h2 class="text-info-custom">R$ <?php echo number_format($receitaTotal, 2, ',', '.'); ?></h2>
                    <i class="bi bi-cash card-icon text-info-custom"></i>
                </div>
            </div>
        </div>
        
        <!-- Tabela de agendamentos -->
        <div class="data-table">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="bi bi-calendar-check"></i> Agendamentos Recentes
                </h5>
                <div class="search-actions">
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="form-control form-control-sm" placeholder="Buscar...">
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Valor</th>
                            <th>Barba</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($agendamentos) > 0) : ?>
                            <?php foreach ($agendamentos as $agendamento): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        <div class="user-info">
                                            <h6><?php echo htmlspecialchars($agendamento['nome_cliente']); ?></h6>
                                            <small>
                                                <?php echo htmlspecialchars($agendamento['telefone']); ?>
                                                <a href="https://api.whatsapp.com/send?phone=55<?php echo preg_replace('/[^0-9]/', '', $agendamento['telefone']); ?>" 
                                                   target="_blank" class="ms-2 text-success" title="Enviar WhatsApp">
                                                    <i class="bi bi-whatsapp"></i>
                                                </a>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($agendamento['servico']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($agendamento['data'])); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['hora']); ?></td>
                                <td>R$ <?php echo number_format($agendamento['valor_total'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php if ($agendamento['incluir_barba']) : ?>
                                        <span class="badge-sim">Sim</span>
                                    <?php else : ?>
                                        <span class="badge-nao">Não</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-outline-primary btn-action" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" 
                                            onclick="enviarMensagem('<?php echo htmlspecialchars($agendamento['nome_cliente']); ?>', 
                                                                  '<?php echo htmlspecialchars($agendamento['telefone']); ?>', 
                                                                  '<?php echo date('d/m/Y', strtotime($agendamento['data'])); ?>', 
                                                                  '<?php echo htmlspecialchars($agendamento['hora']); ?>')" 
                                            title="Enviar Mensagem">
                                        <i class="bi bi-chat-dots"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-action" onclick="excluirAgendamento(<?php echo $agendamento['id']; ?>)" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">
                                    <div class="no-data">
                                        <i class="bi bi-calendar-x"></i>
                                        <p>Nenhum agendamento encontrado</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <?php if ($totalPaginas > 1): ?>
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Navegação de página">
                    <ul class="pagination">
                        <!-- Botão Anterior -->
                        <li class="page-item <?php echo ($paginaAtual <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $paginaAtual - 1; ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        
                        <!-- Números de Página -->
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                                <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <!-- Botão Próximo -->
                        <li class="page-item <?php echo ($paginaAtual >= $totalPaginas) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $paginaAtual + 1; ?>" aria-label="Próximo">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de confirmação de exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Tem certeza que deseja excluir este agendamento?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-danger" id="confirmDelete">Excluir</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para enviar mensagem ao cliente -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Enviar mensagem ao cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="messageForm">
                        <div class="mb-3">
                            <label for="clienteNome" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="clienteNome" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="clienteTelefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="clienteTelefone" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="dataAgendamento" class="form-label">Data</label>
                            <input type="text" class="form-control" id="dataAgendamento" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="horaAgendamento" class="form-label">Hora</label>
                            <input type="text" class="form-control" id="horaAgendamento" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="mensagemTexto" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="mensagemTexto" rows="4">Olá! Seu agendamento na Mustache Barbearia foi confirmado para o dia {DATA} às {HORA}. Obrigado pela preferência!</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" class="btn btn-success" id="enviarWhatsApp" target="_blank">
                        <i class="bi bi-whatsapp me-2"></i>Enviar via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variável para armazenar o ID do agendamento a ser excluído
        let agendamentoIdToDelete = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
        
        function excluirAgendamento(id) {
            agendamentoIdToDelete = id;
            deleteModal.show();
        }
        
        function enviarMensagem(nome, telefone, data, hora) {
            // Preencher o formulário do modal com os dados corretos deste agendamento específico
            document.getElementById('clienteNome').value = nome;
            document.getElementById('clienteTelefone').value = telefone;
            document.getElementById('dataAgendamento').value = data;
            document.getElementById('horaAgendamento').value = hora;
            
            // Preencher o template da mensagem com os dados específicos deste agendamento
            atualizarMensagem();
            
            // Mostrar o modal
            messageModal.show();
        }
        
        function atualizarMensagem() {
            // Obter os valores atuais
            const nome = document.getElementById('clienteNome').value;
            const data = document.getElementById('dataAgendamento').value;
            const hora = document.getElementById('horaAgendamento').value;
            
            // Template da mensagem
            let mensagemTemplate = "Olá! Seu agendamento na Mustache Barbearia foi confirmado para o dia {DATA} às {HORA}. Obrigado pela preferência!";
            
            // Substituir placeholders
            let mensagem = mensagemTemplate.replace('{DATA}', data).replace('{HORA}', hora);
            document.getElementById('mensagemTexto').value = mensagem;
            
            // Atualizar link do WhatsApp
            const telefone = document.getElementById('clienteTelefone').value;
            const telefoneNumerico = '55' + telefone.replace(/[^0-9]/g, '');
            const whatsappLink = `https://api.whatsapp.com/send?phone=${telefoneNumerico}&text=${encodeURIComponent(mensagem)}`;
            document.getElementById('enviarWhatsApp').href = whatsappLink;
        }
        
        // Atualizar mensagem quando qualquer campo mudar
        document.getElementById('mensagemTexto').addEventListener('input', function() {
            const telefone = document.getElementById('clienteTelefone').value;
            const telefoneNumerico = '55' + telefone.replace(/[^0-9]/g, '');
            const mensagem = this.value;
            document.getElementById('enviarWhatsApp').href = `https://api.whatsapp.com/send?phone=${telefoneNumerico}&text=${encodeURIComponent(mensagem)}`;
        });
        
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (agendamentoIdToDelete) {
                fetch('../api/endpoints/agendamento.php/' + agendamentoIdToDelete, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.mensagem) {
                        deleteModal.hide();
                        
                        // Criar notificação de sucesso
                        const toast = document.createElement('div');
                        toast.className = 'position-fixed bottom-0 end-0 p-3';
                        toast.style.zIndex = 1070;
                        toast.innerHTML = `
                            <div class="toast show bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header bg-success text-white">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <strong class="me-auto">Sucesso</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Fechar"></button>
                                </div>
                                <div class="toast-body">
                                    ${data.mensagem}
                                </div>
                            </div>
                        `;
                        
                        document.body.appendChild(toast);
                        
                        // Remover toast após 3 segundos
                        setTimeout(() => {
                            document.body.removeChild(toast);
                            location.reload();
                        }, 3000);
                    } else {
                        alert('Erro ao excluir agendamento');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao excluir agendamento');
                });
            }
        });
    </script>
</body>
</html> 