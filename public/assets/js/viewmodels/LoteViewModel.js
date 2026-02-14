/**
 * Lote ViewModel
 */
class LoteViewModel extends ViewModel {
    constructor() {
        super();
        this.data = {
            lotes: [],
            alerts: [],
            loading: false,
            error: null
        };
    }

    async fetchLotes() {
        this.setState({ loading: true, error: null });
        try {
            const response = await fetch(`${window.URLROOT}/api/lotes`);
            if (!response.ok) throw new Error('Failed to fetch lotes');

            const result = await response.json();

            // Filter locally for alerts (e.g. 7 days) if needed, 
            // but for now we display all and highlight in view
            this.setState({
                loading: false,
                lotes: result
            });
            console.log("Lotes loaded:", result.length);
        } catch (err) {
            console.error(err);
            this.setState({ loading: false, error: err.message });
        }
    }
}
