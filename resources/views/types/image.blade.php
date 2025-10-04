<div class="captcha-image-wrapper">
    <div class="captcha-image-container">
        <img src="{{ route('captcha.image', ['type' => 'image', 'difficulty' => $difficulty]) }}" 
             alt="Captcha Image" 
             class="captcha-image"
             id="{{ $id }}-image">
        <button type="button" class="captcha-refresh" onclick="refreshCaptcha('{{ $id }}')" title="{{ __('Refresh captcha') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <polyline points="1 20 1 14 7 14"></polyline>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
        </button>
    </div>
    <div class="captcha-input-group">
        <label for="{{ $id }}-input" class="captcha-label">
            <i class="fas fa-keyboard me-1"></i>
            {{ __('Enter the code above:') }}
        </label>
        <input type="text" 
               id="{{ $id }}-input" 
               name="captcha" 
               class="captcha-input @error('captcha') is-invalid @enderror" 
               placeholder="{{ __('Enter captcha code') }}"
               autocomplete="off"
               required>
    </div>
</div>