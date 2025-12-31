// Flash Message System - Vanilla JS
class FlashMessageSystem {
    constructor() {
        this.messages = [];
        this.container = null;
        this.init();
    }

    init() {
        // Create container if it doesn't exist
        if (!this.container) {
            this.container = document.createElement("div");
            this.container.className =
                "flash-messages-container fixed top-4 right-4 z-[9999] space-y-3 w-96 max-w-[calc(100vw-2rem)] pointer-events-none";
            document.body.appendChild(this.container);
        }

        // Listen for flash events
        window.addEventListener("flash", (event) => {
            this.add(event.detail);
        });

        // Initialize global flash function
        window.flash = (
            message,
            type = "success",
            duration = 5000,
            description = ""
        ) => {
            this.add({ message, type, duration, description });
        };

        console.log("Flash message system initialized");
    }

    add({ message, type = "success", duration = 5000, description = "" }) {
        const id = Date.now() + Math.random();

        // Create message element
        const messageElement = this.createMessageElement(
            id,
            message,
            type,
            description,
            duration
        );

        // Add to container
        this.container.appendChild(messageElement);

        // Add to messages array
        const messageObj = {
            id,
            element: messageElement,
            progressBar: messageElement.querySelector(".flash-progress-bar"),
            progress: 100,
            duration,
            timer: null,
            remainingTime: duration,
            startTime: Date.now(),
            isPaused: false,
        };

        this.messages.unshift(messageObj);

        // Start timer
        this.startTimer(messageObj);

        // Limit number of messages
        if (this.messages.length > 5) {
            this.remove(this.messages[this.messages.length - 1].id);
        }

        return id;
    }

    createMessageElement(id, message, type, description, duration) {
        const typeConfig = {
            success: {
                bg: "bg-green-50",
                border: "border-green-200",
                text: "text-green-800",
                progress: "bg-green-500",
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`,
            },
            error: {
                bg: "bg-red-50",
                border: "border-red-200",
                text: "text-red-800",
                progress: "bg-red-500",
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`,
            },
            warning: {
                bg: "bg-yellow-50",
                border: "border-yellow-200",
                text: "text-yellow-800",
                progress: "bg-yellow-500",
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.312 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>`,
            },
            info: {
                bg: "bg-blue-50",
                border: "border-blue-200",
                text: "text-blue-800",
                progress: "bg-blue-500",
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`,
            },
        };

        const config = typeConfig[type] || typeConfig.info;

        const element = document.createElement("div");
        element.id = `flash-${id}`;
        element.className = `flash-message ${config.bg} ${config.border} ${config.text} border rounded-lg shadow-lg overflow-hidden pointer-events-auto transform transition-all duration-300 translate-x-full opacity-0`;
        element.dataset.id = id;

        element.innerHTML = `
            <div class="flex items-start p-4">
                <div class="flex-shrink-0 mt-0.5">
                    ${config.icon}
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-semibold">${message}</p>
                    ${
                        description
                            ? `<p class="text-sm mt-1 opacity-75">${description}</p>`
                            : ""
                    }
                </div>
                <button class="flash-close ml-4 flex-shrink-0 opacity-50 hover:opacity-100 focus:outline-none transition-opacity">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="h-1 w-full bg-gray-200 bg-opacity-50">
                <div class="flash-progress-bar h-full transition-all duration-300 ease-linear ${
                    config.progress
                }" style="width: 100%"></div>
            </div>
        `;

        // Add hover events
        element.addEventListener("mouseenter", () => this.pauseTimer(id));
        element.addEventListener("mouseleave", () => this.resumeTimer(id));

        // Add close button event
        const closeBtn = element.querySelector(".flash-close");
        closeBtn.addEventListener("click", () => this.remove(id));

        // Animate in
        setTimeout(() => {
            element.classList.remove("translate-x-full", "opacity-0");
            element.classList.add("translate-x-0", "opacity-100");
        }, 10);

        return element;
    }

    startTimer(messageObj) {
        if (messageObj.timer) clearInterval(messageObj.timer);

        messageObj.startTime = Date.now();
        messageObj.isPaused = false;

        messageObj.timer = setInterval(() => {
            if (messageObj.isPaused) return;

            const elapsed = Date.now() - messageObj.startTime;
            messageObj.remainingTime = messageObj.duration - elapsed;
            messageObj.progress =
                (messageObj.remainingTime / messageObj.duration) * 100;

            // Update progress bar
            if (messageObj.progressBar) {
                messageObj.progressBar.style.width = `${messageObj.progress}%`;
            }

            if (messageObj.remainingTime <= 0) {
                this.remove(messageObj.id);
            }
        }, 50);
    }

    pauseTimer(id) {
        const message = this.messages.find((msg) => msg.id === id);
        if (message) {
            message.isPaused = true;
        }
    }

    resumeTimer(id) {
        const message = this.messages.find((msg) => msg.id === id);
        if (message) {
            // Adjust duration to remaining time
            message.duration = message.remainingTime;
            message.startTime = Date.now();
            message.isPaused = false;
        }
    }

    remove(id) {
        const messageIndex = this.messages.findIndex((msg) => msg.id === id);
        if (messageIndex !== -1) {
            const message = this.messages[messageIndex];

            // Animate out
            if (message.element) {
                message.element.classList.remove(
                    "translate-x-0",
                    "opacity-100"
                );
                message.element.classList.add("translate-x-full", "opacity-0");

                // Remove after animation
                setTimeout(() => {
                    if (message.element.parentElement) {
                        message.element.remove();
                    }
                }, 300);
            }

            // Clear timer
            if (message.timer) {
                clearInterval(message.timer);
            }

            // Remove from array
            this.messages.splice(messageIndex, 1);
        }
    }

    clear() {
        this.messages.forEach((msg) => this.remove(msg.id));
    }
}

// Initialize flash system when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    window.flashSystem = new FlashMessageSystem();

    // Handle server-side flash messages
    if (window.flashMessages && Array.isArray(window.flashMessages)) {
        window.flashMessages.forEach((msg) => {
            window.flashSystem.add(msg);
        });
    }
});
