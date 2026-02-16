// Loading Screen Controller
class LoadingScreen {
    constructor() {
        console.log('LoadingScreen: Initializing...');
        this.loadingScreen = document.getElementById('loading-screen');
        this.progressBar = document.querySelector('.progress-bar');
        this.loadingText = document.querySelector('.loading-text');
        this.percentageText = document.querySelector('.loading-percentage');
        
        if (!this.loadingScreen) {
            console.error('LoadingScreen: Element #loading-screen not found!');
            return;
        }
        
        console.log('LoadingScreen: Elements found successfully');
        this.progress = 0;
        this.loadingMessages = [
            'Preparing your table...',
            'Loading menu items...',
            'Setting up kitchen...',
            'Warming up the grill...',
            'Almost ready...'
        ];
        this.currentMessageIndex = 0;
    }

    init() {
        console.log('LoadingScreen: Starting simulation...');
        this.simulateLoading();
    }

    updateProgress(value) {
        this.progress = Math.min(value, 100);
        this.progressBar.style.width = this.progress + '%';
        
        if (this.percentageText) {
            this.percentageText.textContent = Math.floor(this.progress) + '%';
        }

        // Update loading message based on progress
        const messageIndex = Math.floor((this.progress / 100) * this.loadingMessages.length);
        if (messageIndex !== this.currentMessageIndex && messageIndex < this.loadingMessages.length) {
            this.currentMessageIndex = messageIndex;
            this.updateMessage(this.loadingMessages[messageIndex]);
        }
    }

    updateMessage(message) {
        if (this.loadingText) {
            this.loadingText.style.opacity = '0';
            setTimeout(() => {
                this.loadingText.textContent = message;
                this.loadingText.style.opacity = '1';
            }, 200);
        }
    }

    simulateLoading() {
        let progress = 0;
        const interval = setInterval(() => {
            // Simulate realistic loading with variable speed
            const increment = Math.random() * 15 + 5;
            progress += increment;
            
            if (progress >= 100) {
                progress = 100;
                this.updateProgress(progress);
                clearInterval(interval);
                setTimeout(() => this.hide(), 500);
            } else {
                this.updateProgress(progress);
            }
        }, 200);
    }

    hide() {
        console.log('LoadingScreen: Hiding...');
        if (this.loadingScreen) {
            this.loadingScreen.classList.add('hidden');
            setTimeout(() => {
                this.loadingScreen.style.display = 'none';
                console.log('LoadingScreen: Hidden successfully');
            }, 500);
        }
    }

    show() {
        if (this.loadingScreen) {
            this.loadingScreen.style.display = 'flex';
            this.loadingScreen.classList.remove('hidden');
            this.progress = 0;
            this.currentMessageIndex = 0;
            this.updateProgress(0);
        }
    }
}

// Initialize loading screen when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing loading screen');
    const loader = new LoadingScreen();
    if (loader.loadingScreen) {
        loader.init();
    } else {
        console.error('Failed to initialize loading screen - element not found');
    }
    
    // Add interactive effects
    addInteractiveEffects();
});

// Add interactive hover and movement effects
function addInteractiveEffects() {
    const logoContainer = document.querySelector('.loading-logo-container');
    const loadingScreen = document.getElementById('loading-screen');
    
    // Parallax effect on mouse move
    if (loadingScreen) {
        loadingScreen.addEventListener('mousemove', function(e) {
            const particles = document.querySelectorAll('.particle');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            particles.forEach((particle, index) => {
                const speed = (index + 1) * 0.5;
                const x = (mouseX - 0.5) * speed * 20;
                const y = (mouseY - 0.5) * speed * 20;
                particle.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    }
    
    // Logo click effect
    if (logoContainer) {
        logoContainer.addEventListener('click', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'float 3s ease-in-out infinite';
            }, 10);
        });
    }
    
    // Add sparkle effect on progress bar
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        setInterval(() => {
            createSparkle(progressBar);
        }, 500);
    }
}

// Create sparkle effect
function createSparkle(element) {
    const sparkle = document.createElement('div');
    sparkle.style.position = 'absolute';
    sparkle.style.width = '4px';
    sparkle.style.height = '4px';
    sparkle.style.background = 'white';
    sparkle.style.borderRadius = '50%';
    sparkle.style.pointerEvents = 'none';
    sparkle.style.left = Math.random() * 100 + '%';
    sparkle.style.top = '50%';
    sparkle.style.transform = 'translate(-50%, -50%)';
    sparkle.style.animation = 'sparkleAnimation 0.6s ease-out forwards';
    
    element.appendChild(sparkle);
    
    setTimeout(() => {
        sparkle.remove();
    }, 600);
}

// Add sparkle animation
const style = document.createElement('style');
style.textContent = `
    @keyframes sparkleAnimation {
        0% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(0);
        }
        50% {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.5);
        }
        100% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0);
        }
    }
`;
document.head.appendChild(style);

// Optional: Show loading screen on page navigation
window.addEventListener('beforeunload', function() {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        loadingScreen.style.display = 'flex';
        loadingScreen.classList.remove('hidden');
    }
});
