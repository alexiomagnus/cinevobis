// --- Logica di tracciamento Referer ---
document.addEventListener("DOMContentLoaded", function() {
    const currentPage = window.location.pathname;
    const blacklist = ['login.php', 'signup.php'];
    
    // Controlla se l'utente è atterrato su una pagina di accesso
    const isAuthPage = blacklist.some(p => currentPage.includes(p));

    if (isAuthPage) {
        // Prendiamo l'URL da cui proviene l'utente
        const referrer = document.referrer;
        
        console.log('Current Page:', currentPage);
        console.log('Referrer:', referrer);

        if (referrer && !sessionStorage.getItem('origin_url')) {
            sessionStorage.setItem('origin_url', referrer);
            console.log('Origin URL set to:', referrer);
        }
    }
});

// --- Funzione per il tasto "X" ---
function closeAndRedirect() {
    const destination = sessionStorage.getItem('origin_url');
    
    console.log('Destination from session storage:', destination);

    if (destination) {
        window.location.href = destination;
        sessionStorage.removeItem('origin_url');
    } else {
        window.location.href = '/index.php';
    }
}

// --- Funzione per vedere la password ---
document.addEventListener("DOMContentLoaded", function() {
    // Seleziona tutte le icone con classe toggle-icon
    const toggleIcons = document.querySelectorAll('.toggle-icon');

    toggleIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            // Trova l'input collegato tramite l'ID o il target data
            const targetId = this.getAttribute('data-target') || 'password';
            const passwordInput = document.getElementById(targetId);

            if (passwordInput) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Cambia l'icona
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            }
        });
    });
});