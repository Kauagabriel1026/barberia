<?php
/**
 * Arquivo de rodapé comum para as páginas
 * 
 * Inclui os scripts JavaScript e outros elementos comuns do rodapé
 */
?>
    <footer class="bg-dark text-white py-4 mt-auto border-top border-secondary">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-scissors text-warning me-2 fs-5"></i>
                        <span class="fw-bold fs-5">Mustache Barbearia</span>
                    </div>
                    <p class="text-muted small">© <?php echo date('Y'); ?> Todos os direitos reservados</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0 text-md-center">
                    <div class="d-flex justify-content-md-center">
                        <a href="#" class="text-secondary mx-2 fs-5 hover-icon"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-secondary mx-2 fs-5 hover-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-secondary mx-2 fs-5 hover-icon"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i> Rua das Tesouras, 123 - Centro</p>
                    <p class="text-muted small"><i class="bi bi-clock me-1"></i> Seg - Sáb: 9h às 20h</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts JavaScript comuns -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script JavaScript opcional específico da página -->
    <?php if (isset($pageSpecificJS)): ?>
        <script>
            <?php echo $pageSpecificJS; ?>
        </script>
    <?php endif; ?>
    
    <!-- JS personalizado carregado condicionalmente -->
    <?php if (isset($pageScripts) && is_array($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 