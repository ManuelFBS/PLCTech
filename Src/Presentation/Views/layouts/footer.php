<?php

use \PLCTech\Helpers\UrlHelper;

?>

</div> <!-- Cierra .container -->
    </div> <!-- Cierra el div de contenido -->
    <div style="margin-top: 100px;">
    <footer style="background-color: transparent; padding: 0.75rem; text-align: center; margin-top: 5; border-top: 0px solid #ddd;">
        <p style="margin: 0;">
            <strong><?php echo $_ENV['APP_NAME']; ?></strong> &copy; <?php echo date('Y'); ?> - 
            <small>
                <i class="fas fa-user"></i> <?php echo ucfirst(strtolower($_SESSION['role'])); ?> | 
                <i class="fas fa-clock"></i> <?php echo date('H:i:s'); ?>
            </small>
        </p>
    </footer>
    </div>
    
    <script>
        // * Definir URLs base al inicio del script...
        const baseUrl = "<?= UrlHelper::url('/employees') ?>";
        const baseDeleteUrl = "<?= UrlHelper::url('/employees/delete') ?>";

        // * Cerrar mensajes automáticamente...
        setTimeout(function() {
            document.querySelectorAll('.notification').forEach(function(notification) {
                notification.style.transition = 'opacity 0.3s';
                notification.style.opacity = '0';
                setTimeout(function() {
                    if (notification.parentElement) notification.remove();
                }, 300);
            });
        }, 4000);
        
        // * Funcionalidad del navbar burger...
        document.addEventListener('DOMContentLoaded', function() {
            const navbarBurger = document.querySelector('.navbar-burger');
            const navbarMenu = document.querySelector('#navbarMenu');
            
            if (navbarBurger && navbarMenu) {
                navbarBurger.addEventListener('click', function() {
                    navbarBurger.classList.toggle('is-active');
                    navbarMenu.classList.toggle('is-active');
                });
            }
        });
        
        function searchEmployee() {
            const searchTerm = document.getElementById('searchEmployee')?.value;
            if (searchTerm) {
                window.location.href = baseUrl + '?search=' + encodeURIComponent(searchTerm);
            } else {
                window.location.href = baseUrl;
            }
        }
        
        // * Eliminar scroll innecesario - forzar que no haya espacio extra...
        window.addEventListener('load', function() {
            document.body.style.margin = '0';
            document.body.style.padding = '0';
            document.documentElement.style.margin = '0';
            document.documentElement.style.padding = '0';
            
            // > Si el contenido no llena la pantalla, no fuerza altura...
            var contentDiv = 
                document.querySelector('.main-content') || 
                    document.querySelector('body > div:not(.navbar)');
            if (contentDiv) {
                contentDiv.style.minHeight = 'auto';
            }
        });
    </script>
</body>
</html>