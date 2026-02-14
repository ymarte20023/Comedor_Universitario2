/**
 * Dashboard ViewModel
 * Handles fetching stats and alerts dynamically
 */
class DashboardViewModel extends ViewModel {
    constructor() {
        super();
        this.state = {
            stats: {
                total_productos: 0,
                stock_critico: 0,
                lotes_vencen: 0,
                total_categorias: 0,
                total_proveedores: 0,
                total_usuarios: 0
            },
            alerts: {
                stock: [],
                lotes: []
            },
            loading: false,
            error: null
        };
    }

    async fetchDashboardData() {
        this.setState({ loading: true, error: null });
        try {
            const response = await fetch(`${window.URLROOT}/api/dashboard`);
            if (!response.ok) throw new Error('Failed to fetch dashboard data');

            const result = await response.json();

            this.setState({
                loading: false,
                stats: result.stats,
                alerts: {
                    stock: result.stock_critico_list,
                    lotes: result.lotes_vencen_list
                }
            });
            console.log("Dashboard data updated:", this.state);
        } catch (err) {
            console.error(err);
            this.setState({ loading: false, error: err.message });
        }
    }
}
