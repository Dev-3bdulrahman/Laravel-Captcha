/**
 * Laravel Captcha JavaScript
 * @author Abdulrahman Mehesan
 * @link https://3bdulrahman.com/
 */

class LaravelCaptcha {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error('Captcha container not found:', containerId);
            return;
        }

        this.options = {
            type: options.type || 'image',
            difficulty: options.difficulty || 'medium',
            style: options.style || 'default',
            onSuccess: options.onSuccess || null,
            onError: options.onError || null,
            ...options
        };

        this.init();
    }

    init() {
        if (this.options.type === 'slider') {
            this.initSlider();
        }

        // Add input validation
        const input = this.container.querySelector('.captcha-input');
        if (input) {
            input.addEventListener('input', () => {
                input.classList.remove('success', 'error');
            });
        }
    }

    initSlider() {
        const handle = this.container.querySelector('.captcha-slider-handle');
        const track = this.container.querySelector('.captcha-slider-track');
        const input = this.container.querySelector('input[type="hidden"]');
        const sliderContainer = this.container.querySelector('.captcha-slider-container');

        if (!handle || !track || !input || !sliderContainer) return;

        let isDragging = false;
        let startX = 0;
        let currentX = 0;
        const maxX = track.offsetWidth - handle.offsetWidth;

        const onMouseDown = (e) => {
            isDragging = true;
            startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
            handle.style.transition = 'none';
        };

        const onMouseMove = (e) => {
            if (!isDragging) return;

            const clientX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
            const deltaX = clientX - startX;
            currentX = Math.max(0, Math.min(maxX, deltaX));

            handle.style.left = currentX + 'px';
            input.value = currentX;
        };

        const onMouseUp = () => {
            if (!isDragging) return;
            isDragging = false;
            handle.style.transition = '';

            // Verify position
            const targetPosition = parseInt(sliderContainer.dataset.position);
            const tolerance = parseInt(sliderContainer.dataset.tolerance);
            const trackWidth = track.offsetWidth;
            const backgroundWidth = this.container.querySelector('.captcha-slider-background').offsetWidth;
            
            // Calculate the actual position based on the slider position
            const actualPosition = (currentX / maxX) * backgroundWidth;

            if (Math.abs(actualPosition - targetPosition) <= tolerance) {
                this.onSliderSuccess();
            } else {
                this.onSliderError();
            }
        };

        // Mouse events
        handle.addEventListener('mousedown', onMouseDown);
        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);

        // Touch events
        handle.addEventListener('touchstart', onMouseDown);
        document.addEventListener('touchmove', onMouseMove);
        document.addEventListener('touchend', onMouseUp);
    }

    onSliderSuccess() {
        const handle = this.container.querySelector('.captcha-slider-handle');
        const text = this.container.querySelector('.captcha-slider-text');
        
        handle.style.background = 'linear-gradient(135deg, #4CAF50 0%, #45a049 100%)';
        text.textContent = '✓ Verified!';
        text.style.color = '#4CAF50';

        if (this.options.onSuccess) {
            this.options.onSuccess();
        }
    }

    onSliderError() {
        const handle = this.container.querySelector('.captcha-slider-handle');
        const text = this.container.querySelector('.captcha-slider-text');
        
        handle.style.background = 'linear-gradient(135deg, #f44336 0%, #da190b 100%)';
        text.textContent = '✗ Try again';
        text.style.color = '#f44336';

        // Reset after 1 second
        setTimeout(() => {
            handle.style.left = '0px';
            handle.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            text.textContent = 'Slide to complete';
            text.style.color = '#999';
        }, 1000);

        if (this.options.onError) {
            this.options.onError();
        }
    }

    async verify(value) {
        try {
            const response = await fetch('/captcha/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    captcha: value,
                    type: this.options.type
                })
            });

            const data = await response.json();
            return data.success;
        } catch (error) {
            console.error('Captcha verification error:', error);
            return false;
        }
    }

    async refresh() {
        const container = this.container;
        container.classList.add('captcha-loading');

        try {
            const response = await fetch(`/captcha/refresh?type=${this.options.type}&difficulty=${this.options.difficulty}`);
            const data = await response.json();

            if (data.success) {
                // Refresh based on type
                if (this.options.type === 'image') {
                    const img = container.querySelector('.captcha-image');
                    if (img) {
                        img.src = data.data.image_url + '&t=' + Date.now();
                    }
                } else if (this.options.type === 'math' || this.options.type === 'text') {
                    const question = container.querySelector('.captcha-question');
                    if (question) {
                        question.textContent = data.data.question;
                    }
                } else if (this.options.type === 'slider') {
                    // Reload the entire slider
                    location.reload();
                }

                // Clear input
                const input = container.querySelector('.captcha-input');
                if (input) {
                    input.value = '';
                    input.classList.remove('success', 'error');
                }
            }
        } catch (error) {
            console.error('Captcha refresh error:', error);
        } finally {
            container.classList.remove('captcha-loading');
        }
    }
}

// Global refresh function for inline onclick handlers
function refreshCaptcha(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const type = container.dataset.type;
    const difficulty = container.dataset.difficulty;
    const style = container.dataset.style || 'default';

    const captcha = new LaravelCaptcha(containerId, { type, difficulty, style });
    captcha.refresh();
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LaravelCaptcha;
}

