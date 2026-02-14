/**
 * MVVM Binding Logic for Dashboard
 */

document.addEventListener('DOMContentLoaded', () => {
    // Only run on dashboard page
    const dashboardContainer = document.querySelector('.dashboard-mvvm-container');
    if (!dashboardContainer) return;

    console.log("Initializing Dashboard MVVM...");

    const viewModel = new DashboardViewModel();

    // Subscribe to state changes to update UI
    viewModel.subscribe((state) => {
        // Update Stats Cards
        if (state.stats) {
            updateElementText('stat-total-productos', state.stats.total_productos);
            updateElementText('stat-stock-critico', state.stats.stock_critico);
            updateElementText('stat-lotes-vencen', state.stats.lotes_vencen);
            updateElementText('stat-total-categorias', state.stats.total_categorias);
            updateElementText('stat-total-proveedores', state.stats.total_proveedores);
            updateElementText('stat-total-usuarios', state.stats.total_usuarios);
        }

        // Update Stock Alerts Table
        if (state.alerts.stock.length > 0) {
            document.getElementById('stock-alert-section').style.display = 'block';
            renderStockTable(state.alerts.stock);
        } else {
            document.getElementById('stock-alert-section').style.display = 'none';
        }

        // Update Lote Alerts Table
        if (state.alerts.lotes.length > 0) {
            document.getElementById('lotes-alert-section').style.display = 'block';
            renderLotesTable(state.alerts.lotes);
        } else {
            document.getElementById('lotes-alert-section').style.display = 'none';
        }
    });

    // Initial Fetch
    viewModel.fetchDashboardData();

    // Refresh button
    const refreshBtn = document.getElementById('refresh-stats-btn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            refreshBtn.classList.add('spinning');
            viewModel.fetchDashboardData().then(() => {
                setTimeout(() => refreshBtn.classList.remove('spinning'), 500);
            });
        });
    }
});

// Helper: Update text safe
function updateElementText(id, text) {
    const el = document.getElementById(id);
    if (el) el.innerText = text;
}

// Helper: Render Stock Table
function renderStockTable(products) {
    const tbody = document.querySelector('#stock-critico-table tbody');
    if (!tbody) return;
    tbody.innerHTML = products.map(p => `
        <tr>
            <td>${p.nombre}</td>
            <td>${p.categoria}</td>
            <td class="stock-critico">${p.stock_actual}</td>
            <td>${p.stock_minimo}</td>
        </tr>
    `).join('');
}

// Helper: Render Lotes Table
function renderLotesTable(lotes) {
    const tbody = document.querySelector('#lotes-vencen-table tbody');
    if (!tbody) return;
    tbody.innerHTML = lotes.map(l => `
        <tr>
            <td>${l.producto}</td>
            <td>${l.numero_lote}</td>
            <td>${l.cantidad}</td>
            <td>${l.fecha_caducidad}</td>
            <td class="stock-critico">${l.dias_restantes} días</td>
        </tr>
    `).join('');
}
