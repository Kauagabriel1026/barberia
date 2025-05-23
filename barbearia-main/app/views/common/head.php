<?php
/**
 * Arquivo de cabeçalho comum para as páginas
 * 
 * Inclui as tags meta, links para CSS e outros elementos comuns do cabeçalho
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Agendamentos para Barbearia - Agende seu horário de forma rápida e fácil">
    <title><?php echo $pageTitle ?? 'Mustache Barbearia'; ?></title>
    
    <!-- CSS do Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="/public/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    
    <!-- CSS opcional específico da página -->
    <?php if (isset($pageSpecificCSS)): ?>
        <style>
            <?php echo $pageSpecificCSS; ?>
        </style>
    <?php endif; ?>
</head>
<body class="bg-dark text-white d-flex flex-column min-vh-100">
<?php
// Início do corpo da página
?> 