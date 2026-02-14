/**
 * Base ViewModel Class
 */
class ViewModel {
    constructor() {
        this.state = {};
        this.observers = [];
    }

    /**
     * Update state and notify observers
     * @param {Object} newState 
     */
    setState(newState) {
        this.state = { ...this.state, ...newState };
        this.notifyObservers();
    }

    /**
     * Register an observer function
     * @param {Function} observer 
     */
    subscribe(observer) {
        this.observers.push(observer);
    }

    /**
     * Notify all observers of state change
     */
    notifyObservers() {
        this.observers.forEach(observer => observer(this.state));
    }
}
