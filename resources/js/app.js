import "./bootstrap";
import "./flash";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    console.log("App initialized with Flash System");

    // You can add any additional initialization here
    // For example, attach flash events to buttons with data attributes

    // Auto-attach flash to buttons with data-flash attribute
    document.querySelectorAll("[data-flash]").forEach((button) => {
        button.addEventListener("click", function (e) {
            const message =
                this.dataset.flashMessage || "Operation successful!";
            const type = this.dataset.flashType || "success";
            const duration = parseInt(this.dataset.flashDuration) || 5000;
            const description = this.dataset.flashDescription || "";

            // Trigger flash
            if (window.flash) {
                window.flash(message, type, duration, description);
            }
        });
    });
});

// Make flash function available globally
window.flash = function (
    message,
    type = "success",
    duration = 5000,
    description = ""
) {
    if (window.flashSystem && window.flashSystem.add) {
        return window.flashSystem.add({ message, type, duration, description });
    } else {
        // Fallback if flash system not loaded
        console.warn("Flash system not loaded yet, queuing message");

        // Store for when system loads
        if (!window.queuedFlashMessages) {
            window.queuedFlashMessages = [];
        }
        window.queuedFlashMessages.push({
            message,
            type,
            duration,
            description,
        });

        // Try again in a bit
        setTimeout(() => {
            if (window.flashSystem && window.flashSystem.add) {
                window.queuedFlashMessages.forEach((msg) => {
                    window.flashSystem.add(msg);
                });
                window.queuedFlashMessages = [];
            }
        }, 100);
    }
};
