/**
 * MVVM Binding for Lotes
 */
document.addEventListener('DOMContentLoaded', () => {
    const lotesContainer = document.querySelector('.lotes-mvvm-container');
    if (!lotesContainer) return;

    console.log("Initializing Lotes MVVM...");
    const viewModel = new LoteViewModel();

    viewModel.subscribe((state) => {
        const tbody = document.querySelector('#lotes-table tbody');
        if (!tbody) return;

        if (state.loading) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Cargando lotes...</td></tr>';
            return;
        }

        tbody.innerHTML = state.lotes.map(l => {
            // Calculate days remaining simple logic for display (or use server provided)
            // Assuming server provides days_restantes or we parse dates
            // For this output we'll use server provided HTML or safe defaults
            const estadoBadge = `<span class="badge badge-${l.estado}">${l.estado}</span>`;

            return `
            <tr>
                <td>${l.producto}</td>
                <td>${l.numero_lote}</td>
                <td>${l.cantidad} ${l.unidad_medida}</td>
                <td>${l.fecha_ingreso}</td>
                <td>${l.fecha_caducidad}</td>
                <td>${l.ubicacion}</td>
                <td>${estadoBadge}</td>
            </tr>
        `}).join('');
    });

    const refreshBtn = document.getElementById('refresh-lotes-btn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            viewModel.fetchLotes();
        });
    }
});
