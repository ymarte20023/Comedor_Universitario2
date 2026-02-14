/**
 * MVVM Binding for Inventario (Productos)
 */
document.addEventListener('DOMContentLoaded', () => {
    const inventarioContainer = document.querySelector('.inventario-mvvm-container');
    if (!inventarioContainer) return;

    console.log("Initializing Inventario MVVM...");
    const viewModel = new InventarioViewModel();

    // Subscribe to UI updates
    viewModel.subscribe((state) => {
        const tbody = document.querySelector('#productos-table tbody');
        if (!tbody) return;

        if (state.loading) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Cargando productos...</td></tr>';
            return;
        }

        tbody.innerHTML = state.productos.map(p => `
            <tr>
                <td>${p.nombre}</td>
                <td>${p.categoria}</td>
                <td>${p.proveedor}</td>
                <td class="${p.stock_actual < p.stock_minimo ? 'stock-critico' : ''}">
                    ${p.stock_actual} ${p.unidad_medida}
                </td>
                <td>${p.stock_minimo}</td>
                <td>$${parseFloat(p.precio_unitario).toFixed(2)}</td>
                <td>
                    <a href="http://localhost/Comedor_Universitario/productos/editar/${p.id}" class="btn btn-sm">Editar</a>
                </td>
            </tr>
        `).join('');
    });

    // Refresh button logic
    const refreshBtn = document.getElementById('refresh-inventario-btn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            viewModel.fetchProductos();
        });
    }

    // Initial load (optional, since SSR already provides data, but this verifies JS connection)
    // viewModel.fetchProductos();
});
