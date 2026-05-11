import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

// Toast notification
document.addEventListener("alpine:init", () => {
    Alpine.data("toastItem", () => ({
        visible: false,
        progress: 100,
        duration: 4000,
        interval: null,

        init() {
            // Delay sedikit biar transisi enter keliatan
            setTimeout(() => {
                this.visible = true;
            }, 50);

            const steps = 100;
            const stepTime = this.duration / steps;

            this.interval = setInterval(() => {
                this.progress -= 1;
                if (this.progress <= 0) this.dismiss();
            }, stepTime);
        },

        dismiss() {
            clearInterval(this.interval);
            this.visible = false;
        },
    }));
});

Alpine.start();
