document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. SALVATAGGIO PROVENIENZA (Login/Signup/Profile) ---
    const paginaAttuale = window.location.pathname;
    const provenienza = document.referrer;
    
    // Raggruppiamo le pagine che condividono questa logica
    const pagineTracciate = ['login.php', 'signup.php', 'change_password.php', 'profile.php', 'contact.php'];

    // Controlliamo se siamo in una di queste pagine
    const isPaginaTracciata = pagineTracciate.some(pagina => paginaAttuale.includes(pagina));

    if (isPaginaTracciata) {
        // Controlliamo se arriviamo da una delle altre pagine tracciate
        const arrivoDaPaginaInterna = pagineTracciate.some(pagina => provenienza.includes(pagina));

        // Sovrascriviamo l'URL di origine SOLO se arriviamo da una pagina esterna a questo gruppo
        if (!arrivoDaPaginaInterna) {
            sessionStorage.setItem('origin_url', provenienza !== "" ? provenienza : '/index.php');
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
        
        // Aggiungi questo controllo di sicurezza
        if (iframeOriginale) {
            const videoUrlBase = iframeOriginale.getAttribute('data-src');
            const classiIframe = iframeOriginale.className;

            const iframeHTML = `<iframe src="${videoUrlBase}" class="${classiIframe}" allowfullscreen></iframe>`;
            container.innerHTML = '';

            trailerModal.addEventListener('show.bs.modal', function() {
                container.innerHTML = iframeHTML;
            });

            trailerModal.addEventListener('hidden.bs.modal', function() {
                container.innerHTML = '';
            });
        }
    }
});

// --- 4. FUNZIONE PER TORNARE INDIETRO ---
function closeAndRedirect() {
    const destinazione = sessionStorage.getItem('origin_url');
    sessionStorage.removeItem('origin_url');
    window.location.href = destinazione || '/index.php';
}