<?php
// Pagina termini di servizio
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termini di Servizio - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>
    
    <main class="container flex-grow-1 py-5">
        <div class="card shadow-sm border-0 p-4 p-md-5">
            <h1 class="fw-bold mb-4">Termini di Servizio</h1>
            
            <p class="text-muted">Ultimo aggiornamento: 12 Maggio 2026</p>
            
            <section class="mb-4 mt-4">
                <h2 class="h4 fw-bold">1. Accettazione dei Termini</h2>
                <p>Creando un account su Cinevobis o utilizzando il nostro servizio, accetti di essere vincolato dai presenti Termini di Servizio. Se non accetti queste condizioni, ti invitiamo a non utilizzare la piattaforma.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">2. Account Utente</h2>
                <p>Dichiari di avere almeno 16 anni per utilizzare il servizio. Sei responsabile della sicurezza del tuo account e di tutte le attività svolte tramite esso. Ti impegni a fornire informazioni accurate e aggiornate.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">3. Contenuti e Comportamento</h2>
                <p>Gli utenti possono inserire recensioni e valutazioni. È vietato pubblicare contenuti offensivi, illegali, diffamatori o che violano i diritti di terzi, inclusi i diritti di copyright. Cinevobis si riserva il diritto di rimuovere tali contenuti e sospendere gli account responsabili.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">4. Modalità Tester e Funzionalità Sperimentali</h2>
                
                <p>Alcune funzionalità della piattaforma possono essere rese disponibili in modalità sperimentale ("Tester"). Tali funzionalità sono fornite esclusivamente a scopo di test e miglioramento del servizio.</p>

                <p>Cinevobis agisce come piattaforma informativa e di aggregazione e non ospita direttamente contenuti protetti da copyright né ne promuove la distribuzione illegale.</p>

                <p>L’utente riconosce e accetta che:</p>
                <ul>
                    <li>l’utilizzo delle funzionalità sperimentali avviene a proprio rischio;</li>
                    <li>è l’unico responsabile delle azioni effettuate tramite il servizio;</li>
                    <li>si impegna a rispettare tutte le normative applicabili, inclusi i diritti di proprietà intellettuale.</li>
                </ul>

                <p>Cinevobis non è responsabile per l’uso improprio della piattaforma da parte degli utenti. In caso di segnalazioni relative a contenuti o utilizzi illeciti, ci riserviamo il diritto di intervenire tempestivamente, inclusa la rimozione dei contenuti e la sospensione degli account.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">5. Modifiche al Servizio</h2>
                <p>Ci riserviamo il diritto di modificare, sospendere o interrompere il servizio in qualsiasi momento, con o senza preavviso. Non saremo responsabili verso di te o terze parti per eventuali modifiche o interruzioni.</p>
            </section>
        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>