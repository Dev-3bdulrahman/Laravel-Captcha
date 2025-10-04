@php
    // Only generate captcha once, not on every Livewire update
    $sessionKey = config('captcha.session_key', 'laravel_captcha');
    $captchaData = Session::get("{$sessionKey}.math");

    if (!$captchaData || !isset($captchaData['value'])) {
        $captchaData = app('captcha')->generate('math', $difficulty);
    }
@endphp

<div class="captcha-math-wrapper" wire:ignore.self>
    <div class="captcha-question-container">
        <div class="captcha-question" id="{{ $id }}-question">
            <i class="fas fa-calculator me-2"></i>
            {{ $captchaData['question'] ?? $captchaData['value'] }}
        </div>
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
            <i class="fas fa-edit me-1"></i>
            {{ __('Your answer:') }}
        </label>
        <input type="text"
               id="{{ $id }}-input"
               name="captcha"
               wire:model.blur="captcha"
               class="captcha-input @error('captcha') is-invalid @enderror"
               placeholder="{{ __('Enter your answer') }}"
               autocomplete="off"
               inputmode="numeric"
               pattern="[0-9]*"
               required>
    </div>
</div>

