/**
 * Laravel Captcha JavaScript
 * @author Abdulrahman Mehesan
 * @link https://3bdulrahman.com/
 */

function refreshCaptcha(captchaId) {
    const refreshBtn = document.querySelector(`#${captchaId} .captcha-refresh`);
    const questionElement = document.querySelector(`#${captchaId}-question`);
    const imageElement = document.querySelector(`#${captchaId}-image`);
    const inputElement = document.querySelector(`#${captchaId}-input`);
    const wrapper = document.querySelector(`#${captchaId} .captcha-math-wrapper, #${captchaId} .captcha-image-wrapper`);
    
    if (!refreshBtn) return;
    
    // Add spinning animation
    refreshBtn.classList.add('spinning');
    wrapper.classList.add('captcha-loading');
    
    // Clear input
    if (inputElement) {
        inputElement.value = '';
        inputElement.classList.remove('is-invalid', 'is-valid');
    }
    
    // Simulate loading (replace with actual AJAX call if needed)
    setTimeout(() => {
        // Remove loading states
        refreshBtn.classList.remove('spinning');
        wrapper.classList.remove('captcha-loading');
        
        // Refresh image captcha if it's an image type
        if (imageElement) {
            const currentSrc = imageElement.src;
            const newSrc = currentSrc.includes('?') ? currentSrc + '&t=' + Date.now() : currentSrc + '?t=' + Date.now();
            imageElement.src = newSrc;
        }
        
        // Trigger Livewire refresh if available
        if (window.Livewire) {
            window.Livewire.emit('refreshCaptcha');
        }
    }, 500);
}

// Enhanced input validation and user feedback
document.addEventListener('DOMContentLoaded', function() {
    const captchaInputs = document.querySelectorAll('.captcha-input');
    
    captchaInputs.forEach(input => {
        // Real-time validation feedback
        input.addEventListener('input', function() {
            const value = this.value.trim();
            const isMathCaptcha = this.closest('.captcha-math-wrapper');
            
            // Remove previous states
            this.classList.remove('is-invalid', 'is-valid');
            
            // Validation based on captcha type
            if (isMathCaptcha) {
                // Math captcha: numbers only
                if (value && /^\d+$/.test(value)) {
                    this.classList.add('is-valid');
                } else if (value && !/^\d+$/.test(value)) {
                    this.classList.add('is-invalid');
                }
            } else {
                // Image captcha: any text
                if (value && value.length >= 3) {
                    this.classList.add('is-valid');
                }
            }
        });
        
        // Enhanced focus effects
        input.addEventListener('focus', function() {
            const wrapper = this.closest('.captcha-math-wrapper, .captcha-image-wrapper');
            const container = this.closest('.captcha-container');
            if (wrapper) {
                wrapper.style.transform = 'translateY(-2px)';
                wrapper.style.boxShadow = '0 8px 16px rgba(120, 136, 252, 0.15)';
            }
            if (container) {
                container.classList.add('active');
            }
        });
        
        input.addEventListener('blur', function() {
            const wrapper = this.closest('.captcha-math-wrapper, .captcha-image-wrapper');
            const container = this.closest('.captcha-container');
            if (wrapper) {
                wrapper.style.transform = 'translateY(0)';
                wrapper.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.05)';
            }
            if (container) {
                container.classList.remove('active');
            }
        });
        
        // Prevent non-numeric input for math captcha only
        input.addEventListener('keypress', function(e) {
            const isMathCaptcha = this.closest('.captcha-math-wrapper');
            
            if (!isMathCaptcha) return; // Allow all input for image captcha
            
            // Allow backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
    
    // Add hover effects to refresh buttons
    const refreshButtons = document.querySelectorAll('.captcha-refresh');
    refreshButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Add keyboard shortcut for refresh (Ctrl+R on captcha input)
    captchaInputs.forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.keyCode === 82) { // Ctrl+R
                e.preventDefault();
                const captchaId = this.id.replace('-input', '');
                refreshCaptcha(captchaId);
            }
        });
    });
});

// Add smooth animations for form submission
document.addEventListener('submit', function(e) {
    const form = e.target;
    const captchaInputs = form.querySelectorAll('.captcha-input');
    
    captchaInputs.forEach(input => {
        const wrapper = input.closest('.captcha-math-wrapper');
        if (wrapper && input.value.trim()) {
            wrapper.style.opacity = '0.8';
            wrapper.style.pointerEvents = 'none';
        }
    });
});

// Auto-focus on captcha input when question container is clicked
document.addEventListener('click', function(e) {
    if (e.target.closest('.captcha-question-container')) {
        const container = e.target.closest('.captcha-container');
        const input = container.querySelector('.captcha-input');
        if (input) {
            input.focus();
        }
    }
});

// Add accessibility improvements
document.addEventListener('DOMContentLoaded', function() {
    const captchaContainers = document.querySelectorAll('.captcha-container');
    
    captchaContainers.forEach(container => {
        // Add ARIA labels
        const question = container.querySelector('.captcha-question');
        const input = container.querySelector('.captcha-input');
        const refreshBtn = container.querySelector('.captcha-refresh');
        
        if (question && input) {
            const questionId = 'captcha-question-' + Math.random().toString(36).substr(2, 9);
            question.id = questionId;
            input.setAttribute('aria-describedby', questionId);
            input.setAttribute('aria-label', 'حل المعادلة الرياضية للتحقق من الهوية');
        }
        
        if (refreshBtn) {
            refreshBtn.setAttribute('aria-label', 'تحديث الكابتشا');
            refreshBtn.setAttribute('role', 'button');
        }
    });
});