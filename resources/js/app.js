import Alpine from "alpinejs";

window.Alpine = Alpine;

// Toast notification store
Alpine.store("toasts", {
    items: [],
    add(message, type = "success", duration = 5000) {
        const id = Date.now();
        this.items.push({ id, message, type });
        if (duration > 0) {
            setTimeout(() => this.remove(id), duration);
        }
    },
    remove(id) {
        this.items = this.items.filter((t) => t.id !== id);
    },
});

Alpine.start();
