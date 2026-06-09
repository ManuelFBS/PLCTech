                </div> <!-- Cierra .container -->
        </main>
    
        <footer class="footer mt-5">
                <div class="content has-text-centered">
                        <p>
                                <strong><?php echo $_ENV['APP_NAME']; ?></strong> &copy; <?php echo date('Y'); ?> - Sistema de Gestión
                                <br>
                                <small>Desarrollado con <i class="fas fa-heart has-text-danger"></i> para PLCTech</small>
                        </p>
                </div>
        </footer>
    
        <script>
                // * Cerrar mensajes automáticamente después de 5 segundos...
                setTimeout(function() {
                        const notifications = document.querySelectorAll('.notification');
                        notifications.forEach(function(notification) {
                                setTimeout(function() {
                                notification.style.opacity = '0';
                                setTimeout(function() {
                                        if (notification.parentElement) {
                                        notification.remove();
                                        }
                                }, 300);
                                }, 5000);
                        });
                }, 100);
                
                // * Funcionalidad del navbar burger (menú responsive)...
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
        </script>
</body>
</html>