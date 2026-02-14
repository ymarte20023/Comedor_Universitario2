/**
 * Base ViewModel Class
 * Implements Observer pattern for reactive UI updates
 */
class ViewModel {
    constructor() {
        this.state = {};
        this.observers = [];
    }

    /**
     * Update state and notify observers
     */
    setState(newState) {
        this.state = { ...this.state, ...newState };
        this.notifyObservers();
    }

    /**
     * Subscribe to state changes
     */
    subscribe(callback) {
        this.observers.push(callback);
    }

    /**
     * Notify all observers of state change
     */
    notifyObservers() {
        this.observers.forEach(observer => observer(this.state));
    }

    /**
     * Get current state
     */
    getState() {
        return this.state;
    }
}
