<?php
// Diretório raiz do projeto
$root = __DIR__;

// Porta do servidor
$port = 8000;

// Host
$host = "localhost";

// Mensagem de inicialização
echo "Iniciando servidor em http://{$host}:{$port}" . PHP_EOL;
echo "Pressione Ctrl+C para encerrar o servidor" . PHP_EOL;
echo "Acesse a página inicial: http://{$host}:{$port}/" . PHP_EOL;
echo "Acesse o painel administrativo: http://{$host}:{$port}/admin/login.php" . PHP_EOL;
echo "  - Usuário: admin" . PHP_EOL;
echo "  - Senha: admin123" . PHP_EOL;

// Iniciar o servidor PHP
$command = sprintf('php -S %s:%d -t %s', $host, $port, escapeshellarg($root));
system($command);
?> 