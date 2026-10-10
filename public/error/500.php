<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro interno do servidor | Mustache Barbearia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        .error-container {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 2rem;
            text-align: center;
        }
        .error-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            color: #FFD166;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Cabeçalho -->
    <header class="header flex items-center justify-between p-4">
        <div class="flex items-center">
            <i class="fas fa-cut text-amber-500 text-2xl mr-2"></i>
            <h1 class="text-xl font-semibold">Mustache Barbearia</h1>
        </div>
        <div class="flex items-center">
            <a href="/public/agendamento.php" class="bg-amber-500 text-gray-900 px-4 py-2 rounded-md font-medium hover:bg-amber-600 transition-colors">
                Agendar Horário
            </a>
            <a href="/admin/login.php" class="ml-4 text-sm text-gray-300 hover:text-white">
                <i class="fas fa-user-cog mr-1"></i> Admin
            </a>
        </div>
    </header>

    <!-- Conteúdo da página de erro -->
    <main class="bg-gray-900 flex-grow">
        <div class="error-container">
            <i class="fas fa-exclamation-triangle error-icon"></i>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">500</h1>
            <h2 class="text-2xl md:text-3xl font-semibold text-amber-500 mb-4">Erro interno do servidor</h2>
            <p class="text-gray-300 mb-8 max-w-lg">
                Desculpe, ocorreu um erro interno em nosso servidor. Nossa equipe técnica já foi notificada e está trabalhando para resolver o problema.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/public/index.php" class="bg-amber-500 text-gray-900 px-6 py-3 rounded-md font-bold hover:bg-amber-600 transition-colors">
                    <i class="fas fa-home mr-2"></i> Voltar à página inicial
                </a>
                <a href="javascript:location.reload()" class="border border-amber-500 text-amber-500 px-6 py-3 rounded-md font-bold hover:bg-gray-800 transition-colors">
                    <i class="fas fa-redo-alt mr-2"></i> Tentar novamente
                </a>
            </div>
        </div>
    </main>
    
    <!-- Rodapé -->
    <footer class="bg-gray-900 py-8 px-4 mt-auto border-t border-gray-800">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <i class="fas fa-cut text-amber-500 text-xl mr-2"></i>
                    <span class="text-lg font-semibold">Mustache Barbearia</span>
                </div>
                <p class="text-gray-400 text-sm mt-2">© <?php echo date('Y'); ?> Todos os direitos reservados</p>
            </div>
            <div class="mt-4 md:mt-0">
                <p class="text-gray-400 text-sm">Rua das Tesouras, 123 - Centro</p>
                <p class="text-gray-400 text-sm">Seg - Sáb: 9h às 19h</p>
            </div>
        </div>
    </footer>
</body>
</html> 