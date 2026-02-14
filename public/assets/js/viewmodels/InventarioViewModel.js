/**
 * Inventario ViewModel
 * Handles product listing and interaction
 */
class InventarioViewModel extends ViewModel {
    constructor() {
        super();
        this.data = {
            productos: [],
            loading: false,
            error: null
        };
    }

    async fetchProductos() {
        this.setState({ loading: true, error: null });
        try {
            const response = await fetch(`${window.URLROOT}/api/productos`);
            if (!response.ok) throw new Error('Failed to fetch productos');

            const result = await response.json();

            this.setState({
                loading: false,
                productos: result
            });
            console.log("Inventario loaded:", result.length + " items");
        } catch (err) {
            console.error(err);
            this.setState({ loading: false, error: err.message });
        }
    }
}
