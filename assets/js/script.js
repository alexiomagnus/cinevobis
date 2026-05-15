// --- GESTIONE DARK MODE IMMEDIATA (Evita FOUC) ---
(function() {
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
    }
})();

document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. SALVATAGGIO PROVENIENZA (Login/Signup/Profile) ---
    const paginaAttuale = window.location.pathname;
    const provenienza = document.referrer;
    
    // Raggruppiamo le pagine che condividono questa logica
    const pagineTracciate = ['login.php', 'signup.php', 'change_password.php', 'profile.php', 'contact.php', 'users_profiles.php'];

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

    // --- 4. CONTEGGIO CARATTERI ---
    const descrizione = document.getElementById('descrizione');
    const contatore = document.getElementById('contatore');

    if (descrizione && contatore) {
        // 1. Definiamo la funzione di aggiornamento
        const aggiornaContatore = () => {
            const numeroCaratteri = descrizione.value.length;
            
            // Aggiorna il testo a schermo
            contatore.textContent = `${numeroCaratteri}/200 caratteri`;
            
            // Gestione colore limite
            if (numeroCaratteri >= 190) {
                contatore.classList.add('text-danger');
            } else {
                contatore.classList.remove('text-danger');
            }
        };

        // 2. Esegui la funzione all'avvio (per calcolare il testo già presente)
        aggiornaContatore();

        // 3. ASCOLTATORE DI EVENTI: Si attiva per ogni modifica futura
        descrizione.addEventListener('input', aggiornaContatore);
    }

    // --- 5. GESTIONE DARK MODE ---
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    if (themeToggle && themeIcon) {
        // Imposta l'icona iniziale basata sul tema attuale (impostato nel tag html)
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
        }

        themeToggle.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-theme');
            let newTheme = theme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            if (newTheme === 'dark') {
                themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
            } else {
                themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
            }
        });
    }
});

// --- 6. FUNZIONE PER TORNARE INDIETRO ---
function closeAndRedirect() {
    const destinazione = sessionStorage.getItem('origin_url');
    sessionStorage.removeItem('origin_url');
    window.location.href = destinazione || '/index.php';
}