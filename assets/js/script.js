document.addEventListener("DOMContentLoaded", () => {
    
    // 1. LOGICA DI TRACCIAMENTO REFERER
    const path = window.location.pathname;
    const ref = document.referrer;
    if (/(login|signup)\.php$/.test(path) && ref && !sessionStorage.getItem('origin_url') && !ref.includes(path)) {
        sessionStorage.setItem('origin_url', ref);
    }

    // 3. VISIBILITÀ PASSWORD
    document.querySelectorAll('.toggle-icon').forEach(icon => {
        icon.addEventListener('click', (e) => {
            const input = document.getElementById(e.currentTarget.dataset.target || 'password');
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
                e.currentTarget.classList.toggle('bi-eye');
                e.currentTarget.classList.toggle('bi-eye-slash');
            }
        });
    });

    // 4. GESTIONE TRAILER MODAL
    const modal = document.getElementById('trailerModal');
    const container = modal?.querySelector('.ratio');
    const iframe = container?.querySelector('iframe');
    
    if (iframe) {
        const url = iframe.dataset.src;
        const cls = iframe.className;
        container.innerHTML = ''; // Svuota il contenitore iniziale
        
        modal.addEventListener('show.bs.modal', () => {
            container.innerHTML = `<iframe src="${url}" class="${cls}" allowfullscreen></iframe>`;
        });
        
        modal.addEventListener('hidden.bs.modal', () => container.innerHTML = '');
    }
});

// 2. FUNZIONE PER IL TASTO CHIUDI (X)
// Esposta su window per garantire che funzioni con gli attributi onclick="" nell'HTML
window.closeAndRedirect = () => {
    const dest = sessionStorage.getItem('origin_url');
    if (dest) sessionStorage.removeItem('origin_url');
    window.location.href = dest || '/index.php';
};