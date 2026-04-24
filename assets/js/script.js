document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. SALVATAGGIO PROVENIENZA (Login/Signup) ---
    const paginaAttuale = window.location.pathname;
    const provenienza = document.referrer;

    if (paginaAttuale.includes('login.php') || paginaAttuale.includes('signup.php') || 
        paginaAttuale.includes('change_password.php') || paginaAttuale.includes('profile.php')) {
            
        if (provenienza !== "" && !sessionStorage.getItem('origin_url')) {
            sessionStorage.setItem('origin_url', provenienza);
        }
    }

    // --- 2. MOSTRA/NASCONDI PASSWORD ---
    const iconePassword = document.querySelectorAll('.toggle-icon');
    
    iconePassword.forEach(function(icona) {
        icona.addEventListener('click', function() {

            const inputId = this.getAttribute('data-target');
            const inputField = document.getElementById(inputId);

            if (inputField.type === 'password') {
                inputField.type = 'text';
                this.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                inputField.type = 'password';
                this.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });

    // --- 3. GESTIONE TRAILER (MODAL) ---
    const trailerModal = document.getElementById('trailerModal');
    const container = document.querySelector('#trailerModal .ratio'); 

    if (trailerModal && container) {
        const iframeOriginale = container.querySelector('iframe');
        const videoUrlBase = iframeOriginale.getAttribute('data-src');
        const classiIframe = iframeOriginale.className;

        // Creiamo la struttura dell'iframe una volta sola, SENZA autoplay
        const iframeHTML = `<iframe src="${videoUrlBase}" class="${classiIframe}" allowfullscreen></iframe>`;

        // Svuotiamo il contenitore all'inizio
        container.innerHTML = '';

        // Quando la modale si apre: inseriamo l'iframe pulito
        trailerModal.addEventListener('show.bs.modal', function() {
            container.innerHTML = iframeHTML;
        });

        // Quando la modale si chiude: distruggiamo l'iframe per fermare l'audio/video
        trailerModal.addEventListener('hidden.bs.modal', function() {
            container.innerHTML = '';
        });
    }
});

// --- 4. FUNZIONE PER TORNARE INDIETRO ---
function closeAndRedirect() {
    const destinazione = sessionStorage.getItem('origin_url');
    
    if (destinazione) {
        sessionStorage.removeItem('origin_url');
        window.location.href = destinazione;
    } else {
        window.location.href = '/index.php';
    }
}