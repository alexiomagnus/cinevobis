<?php
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php', 'film_db.php'];

$isAdminPage = in_array($currentPage, $adminPages);
?>

<footer class="border-top px-3 px-lg-4 py-3">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">

        <!-- Brand + copyright -->
        <div class="d-flex align-items-center gap-2">
            <span lass="fw-bold text-dark">Cinevobis</span>
            <span class="text-secondary small">
                © <?= date("Y") ?>
                <?= $isAdminPage ? '— Area admin' : '' ?>
            </span>
        </div>

        <!-- Link -->
        <nav class="d-flex align-items-center gap-1" aria-label="Footer">
            <?php if ($isAdminPage): ?>
                <a href="/" class="btn btn-sm btn-link text-secondary text-decoration-none p-1 px-2" style="font-size: 0.75rem;">
                    Torna al sito
                </a>
            <?php else: ?>
                <a href="/pages/public/terms.php" class="btn btn-sm btn-link text-secondary text-decoration-none p-1 px-2" style="font-size: 0.75rem;">Termini di servizio</a>
                <span class="text-secondary" style="font-size: 0.75rem;">·</span>
                <a href="/pages/public/privacy.php" class="btn btn-sm btn-link text-secondary text-decoration-none p-1 px-2" style="font-size: 0.75rem;">Privacy</a>
                <span class="text-secondary" style="font-size: 0.75rem;">·</span>
                <a href="/actions/contact.php" class="btn btn-sm btn-link text-secondary text-decoration-none p-1 px-2" style="font-size: 0.75rem;">Contattaci</a>
            <?php endif; ?>
        </nav>

    </div>
</footer>