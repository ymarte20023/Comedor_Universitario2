/**
 * Main Frontend Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Comedor Universitario Frontend Initialized');
    
    // Example: Initialize HomeViewModel if we are on the home page
    if (document.getElementById('app')) {
        // We'll load the ViewModel script dynamically or assume it's included
        // For now, let's just log
        console.log('App container found, loading MVVM components...');
        
        // Simulating MVVM interaction
        const view = document.getElementById('app');
        view.innerHTML = `
            <h3>Panel de Control Digital</h3>
            <p id="status-text">Cargando datos del servidor...</p>
            <button class="btn" id="load-btn">Refrescar Datos</button>
        `;
        
        document.getElementById('load-btn').addEventListener('click', () => {
            document.getElementById('status-text').innerText = 'Datos actualizados vía MVVM/Fetch (Simulado)';
        });
    }
});
