<?php
// Session_start() deve essere chiamato all'inizio di ogni files
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php'];

$isAdminPage = in_array($currentPage, $adminPages);
?>
<footer class="text-center py-4 border-top">
    <div class="mb-2">
        <span class="text-secondary small">
            © <?= date("Y"); ?> <span class="fw-bold text-dark">Cinevobis</span>
        </span>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <a href="/pages/public/terms.php" class="text-secondary small text-decoration-none">Termini di servizio</a>
        <a href="/pages/public/privacy.php" class="text-secondary small text-decoration-none">Informativa sulla privacy</a>

        <?php if(!$isAdminPage): ?>
            <a href="/actions/contact.php" class="text-secondary small text-decoration-none">Contattaci</a>
        <?php endif; ?>
    </div>
</footer>