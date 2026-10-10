<?php
require_once __DIR__ . '/../app/views/common/head.php';
?>

<div class="bg-gray-900 text-white">
    <!-- Cabeçalho -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid px-0">
                    <a class="navbar-brand d-flex align-items-center" href="#">
                        <i class="bi bi-scissors me-2 text-warning"></i>
                        <span class="fw-bold">Mustache Barbearia</span>
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#navbarNav" aria-controls="navbarNav" 
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="bi bi-house-door me-1"></i> Início</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#servicos"><i class="bi bi-list-check me-1"></i> Serviços</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#agendamento"><i class="bi bi-calendar-check me-1"></i> Agendar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/login.php?force_login=1"><i class="bi bi-person-lock me-1"></i>Admin</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="py-5 py-md-6 bg-dark text-white">
        <div class="container text-center py-4">
            <h2 class="display-4 fw-bold mb-3">Mustache Barbearia</h2>
            <p class="lead mb-4 mx-auto" style="max-width: 700px;">O melhor estilo para seu cabelo e barba. 
               Agende seu horário e venha nos conhecer!</p>
            <a href="#agendamento" class="btn btn-warning btn-lg px-4 fw-bold">
                <i class="bi bi-calendar-plus me-2"></i>Agendar Agora
            </a>
        </div>
    </section>

    <!-- Serviços -->
    <section id="servicos" class="py-5 bg-dark bg-opacity-75">
        <div class="container">
            <h2 class="text-center text-white fw-bold mb-5">
                <i class="bi bi-stars me-2 text-warning"></i>Nossos Serviços
            </h2>
            
            <div class="row g-4">
                <!-- Serviço 1 -->
                <div class="col-md-4">
                    <div class="card h-100 bg-dark text-white border-secondary hover-card">
                        <div class="card-body">
                            <h3 class="card-title fs-4 mb-3">
                                <i class="bi bi-scissors me-2 text-warning"></i>Corte Clássico
                            </h3>
                            <p class="card-text text-light mb-4">Corte tradicional, mantendo as linhas clássicas e elegantes.</p>
                            <p class="fs-4 fw-bold text-warning">R$ 35,00</p>
                        </div>
                    </div>
                </div>
                
                <!-- Serviço 2 -->
                <div class="col-md-4">
                    <div class="card h-100 bg-dark text-white border-secondary hover-card">
                        <div class="card-body">
                            <h3 class="card-title fs-4 mb-3">
                                <i class="bi bi-layers me-2 text-warning"></i>Fade
                            </h3>
                            <p class="card-text text-light mb-4">Degradê perfeito, com transição suave e acabamento impecável.</p>
                            <p class="fs-4 fw-bold text-warning">R$ 45,00</p>
                        </div>
                    </div>
                </div>
                
                <!-- Serviço 3 -->
                <div class="col-md-4">
                    <div class="card h-100 bg-dark text-white border-secondary hover-card">
                        <div class="card-body">
                            <h3 class="card-title fs-4 mb-3">
                                <i class="bi bi-droplet-half me-2 text-warning"></i>Barba Completa
                            </h3>
                            <p class="card-text text-light mb-4">Modelagem, hidratação e finalização para uma barba perfeita.</p>
                            <p class="fs-4 fw-bold text-warning">R$ 25,00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Agendamento -->
    <section id="agendamento" class="py-5 bg-dark">
        <div class="container">
            <h2 class="text-center text-white fw-bold mb-5">
                <i class="bi bi-calendar2-week me-2 text-warning"></i>Agende seu Horário
            </h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card bg-dark bg-opacity-50 border border-secondary">
                        <div class="card-body p-4">
                            <form id="agendamento-form" action="../api/endpoints/agendamento.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-white">
                                                <i class="bi bi-person me-2"></i>Nome Completo
                                            </label>
                                            <input type="text" name="nome_cliente" class="form-control bg-dark text-white border-secondary" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-white">
                                                <i class="bi bi-telephone me-2"></i>Telefone
                                            </label>
                                            <input type="text" name="telefone" id="telefone" class="form-control bg-dark text-white border-secondary" placeholder="(00) 00000-0000" required>
                                            <small class="text-info">Use o DDD correto para receber confirmação via WhatsApp</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-white">
                                                <i class="bi bi-calendar3 me-2"></i>Data
                                            </label>
                                            <input type="date" name="data" id="data" class="form-control bg-dark text-white border-secondary" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-t'); ?>">
                                            <small class="text-warning">Só é possível agendar até o final do mês atual</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-white">
                                                <i class="bi bi-clock me-2"></i>Horário
                                            </label>
                                            <select name="hora" id="hora" class="form-select bg-dark text-white border-secondary" required disabled>
                                                <option value="">Selecione uma data primeiro...</option>
                                            </select>
                                            <small class="text-warning" id="horario-info">Selecione uma data para ver os horários disponíveis</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-white">
                                        <i class="bi bi-scissors me-2"></i>Serviço
                                    </label>
                                    <select name="servico" class="form-select bg-dark text-white border-secondary" required>
                                        <option value="">Selecione...</option>
                                        <option value="Corte Clássico" data-valor="35">Corte Clássico - R$ 35,00</option>
                                        <option value="Low Fade" data-valor="45">Low Fade - R$ 45,00</option>
                                        <option value="High Fade" data-valor="45">High Fade - R$ 45,00</option>
                                        <option value="Militar" data-valor="30">Militar - R$ 30,00</option>
                                        <option value="Social" data-valor="35">Social - R$ 35,00</option>
                                        <option value="Mullet" data-valor="50">Mullet - R$ 50,00</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="barba" value="Sim" id="barbaCheck">
                                        <label class="form-check-label text-white" for="barbaCheck">
                                            <i class="bi bi-droplet me-2"></i>Incluir barba (+R$ 25,00)
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-warning btn-lg px-5">
                                        <i class="bi bi-calendar-check me-2"></i>Agendar Agora
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('agendamento-form');
        const dataInput = document.getElementById('data');
        const horaSelect = document.getElementById('hora');
        const barbaCheck = document.getElementById('barbaCheck');
        const horarioInfo = document.getElementById('horario-info');
        
        // Função para carregar horários disponíveis quando uma data é selecionada
        if (dataInput && horaSelect) {
            dataInput.addEventListener('change', function() {
                const selectedDate = this.value;
                if (!selectedDate) {
                    // Se não houver data selecionada, desabilitar o select de horário
                    horaSelect.disabled = true;
                    horaSelect.innerHTML = '<option value="">Selecione uma data primeiro...</option>';
                    horarioInfo.textContent = 'Selecione uma data para ver os horários disponíveis';
                    return;
                }
                
                // Mostrar mensagem de carregamento
                horaSelect.disabled = true;
                horaSelect.innerHTML = '<option value="">Carregando horários...</option>';
                horarioInfo.textContent = 'Carregando horários disponíveis...';
                
                // Buscar horários disponíveis via API
                fetch(`../api/endpoints/agendamento.php/horarios-disponiveis?data=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        horaSelect.innerHTML = '';
                        
                        // Verificar se houve erro
                        if (data.mensagem) {
                            horaSelect.innerHTML = '<option value="">' + data.mensagem + '</option>';
                            horarioInfo.textContent = data.mensagem;
                            horaSelect.disabled = true;
                            return;
                        }
                        
                        // Verificar se há horários disponíveis
                        if (data.horarios_disponiveis && data.horarios_disponiveis.length > 0) {
                            horaSelect.innerHTML = '<option value="">Selecione um horário...</option>';
                            
                            // Adicionar cada horário disponível
                            data.horarios_disponiveis.forEach(horario => {
                                const option = document.createElement('option');
                                option.value = horario;
                                option.textContent = horario;
                                horaSelect.appendChild(option);
                            });
                            
                            horaSelect.disabled = false;
                            horarioInfo.textContent = `${data.horarios_disponiveis.length} horários disponíveis`;
                            horarioInfo.className = 'text-success';
                            
                            // Se a barba estiver selecionada, filtrar apenas horários que podem comportar barba
                            if (barbaCheck && barbaCheck.checked) {
                                filterHorariosByBarba();
                            }
                        } else {
                            horaSelect.innerHTML = '<option value="">Sem horários disponíveis</option>';
                            horarioInfo.textContent = 'Não há horários disponíveis para esta data';
                            horarioInfo.className = 'text-danger';
                            horaSelect.disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        horaSelect.innerHTML = '<option value="">Erro ao carregar horários</option>';
                        horarioInfo.textContent = 'Erro ao carregar horários. Tente novamente.';
                        horaSelect.disabled = true;
                    });
            });
        }
        
        // Filtrar horários quando a opção de barba é alterada
        function filterHorariosByBarba() {
            if (barbaCheck.checked && !horaSelect.disabled && horaSelect.options.length > 1) {
                const options = Array.from(horaSelect.options).slice(1); // Ignorar o primeiro (placeholder)
                
                // Para cada horário, verificar se existe o próximo slot consecutivo
                options.forEach(option => {
                    const horario = option.value;
                    const [hora, minuto] = horario.split(':').map(Number);
                    
                    // Calcular o próximo slot de 30 minutos
                    let proximaHora = hora;
                    let proximoMinuto = minuto + 30;
                    
                    if (proximoMinuto >= 60) {
                        proximaHora += 1;
                        proximoMinuto = 0;
                    }
                    
                    const proximoSlot = `${proximaHora.toString().padStart(2, '0')}:${proximoMinuto.toString().padStart(2, '0')}`;
                    
                    // Verificar se o próximo slot está entre as opções disponíveis
                    const temProximoSlot = Array.from(options).some(opt => opt.value === proximoSlot);
                    
                    // Se não tiver o próximo slot disponível, desabilitar esta opção
                    option.disabled = !temProximoSlot;
                    
                    if (!temProximoSlot) {
                        option.textContent = `${horario} (indisponível com barba)`;
                    } else {
                        option.textContent = horario;
                    }
                });
                
                // Verificar se o horário atualmente selecionado está desabilitado
                if (horaSelect.selectedIndex > 0 && horaSelect.options[horaSelect.selectedIndex].disabled) {
                    horaSelect.selectedIndex = 0; // Resetar para a opção placeholder
                }
                
                horarioInfo.textContent = 'Com barba, o serviço dura aproximadamente 1 hora (alguns horários indisponíveis)';
                horarioInfo.className = 'text-warning';
            } else if (!horaSelect.disabled && horaSelect.options.length > 1) {
                // Remover as desabilitações e textos extras
                Array.from(horaSelect.options).slice(1).forEach(option => {
                    option.disabled = false;
                    option.textContent = option.value;
                });
                
                horarioInfo.textContent = 'Sem barba, o serviço dura aproximadamente 30 minutos';
                horarioInfo.className = 'text-info';
            }
        }
        
        // Adicionar evento para a checkbox de barba
        if (barbaCheck) {
            barbaCheck.addEventListener('change', filterHorariosByBarba);
        }
        
        if (form) {
            // Adicionar formatação automática para o telefone
            const telefoneInput = document.getElementById('telefone');
            if (telefoneInput) {
                telefoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        // Aplicar formatação (99) 99999-9999
                        if (value.length <= 2) {
                            value = `(${value}`;
                        } else if (value.length <= 7) {
                            value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
                        } else if (value.length <= 11) {
                            value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
                        } else {
                            value = `(${value.substring(0, 2)}) ${value.substring(2, 3)} ${value.substring(3, 7)}-${value.substring(7, 11)}`;
                        }
                    }
                    e.target.value = value;
                });
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Verificar se o horário é válido (pode estar desabilitado por causa da barba)
                if (horaSelect.selectedIndex > 0 && horaSelect.options[horaSelect.selectedIndex].disabled) {
                    // Criar alerta de erro
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3';
                    alertDiv.setAttribute('role', 'alert');
                    alertDiv.style.zIndex = '9999';
                    alertDiv.innerHTML = `
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Este horário não está disponível com a opção de barba.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    
                    document.body.appendChild(alertDiv);
                    
                    // Remover o alerta após 5 segundos
                    setTimeout(() => {
                        alertDiv.classList.remove('show');
                        setTimeout(() => alertDiv.remove(), 300);
                    }, 5000);
                    return;
                }
                
                const formData = {
                    nome_cliente: form.nome_cliente.value,
                    telefone: form.telefone.value,
                    data: form.data.value,
                    hora: form.hora.value,
                    servico: form.servico.value,
                    valor: form.servico.options[form.servico.selectedIndex].dataset.valor,
                    barba: form.barba && form.barba.checked ? 'Sim' : 'Não'
                };
                
                fetch('../api/endpoints/agendamento.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.mensagem) {
                        // Verificar se é uma mensagem de sucesso ou erro
                        const isSuccess = data.id ? true : false;
                        
                        // Usar alert do Bootstrap em vez de alert nativo
                        const alertDiv = document.createElement('div');
                        alertDiv.className = `alert alert-${isSuccess ? 'success' : 'danger'} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
                        alertDiv.setAttribute('role', 'alert');
                        alertDiv.style.zIndex = '9999';
                        alertDiv.innerHTML = `
                            <i class="bi bi-${isSuccess ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${data.mensagem}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        
                        // Adicionar o alerta ao body
                        document.body.appendChild(alertDiv);
                        
                        if (data.id) {
                            form.reset();
                            
                            // Resetar o select de horários
                            horaSelect.disabled = true;
                            horaSelect.innerHTML = '<option value="">Selecione uma data primeiro...</option>';
                            horarioInfo.textContent = 'Selecione uma data para ver os horários disponíveis';
                            horarioInfo.className = 'text-warning';
                        }
                        
                        // Remover o alerta após 5 segundos
                        setTimeout(() => {
                            alertDiv.classList.remove('show');
                            setTimeout(() => alertDiv.remove(), 300);
                        }, 5000);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    
                    // Criar alerta de erro com Bootstrap
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3';
                    alertDiv.setAttribute('role', 'alert');
                    alertDiv.style.zIndex = '9999';
                    alertDiv.innerHTML = `
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Erro ao realizar agendamento. Tente novamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    
                    document.body.appendChild(alertDiv);
                    
                    // Remover o alerta após 5 segundos
                    setTimeout(() => {
                        alertDiv.classList.remove('show');
                        setTimeout(() => alertDiv.remove(), 300);
                    }, 5000);
                });
            });
        }
    });
</script>

<?php
require_once __DIR__ . '/../app/views/common/footer.php';
?> 