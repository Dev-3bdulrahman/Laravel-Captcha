@php
    $captchaData = app('captcha')->generate('text', $difficulty);
@endphp

<div class="captcha-text-wrapper">
    <div class="captcha-question-container">
        <div class="captcha-question" id="{{ $id }}-question">
            {{ $captchaData['question'] }}
        </div>
        <button type="button" class="captcha-refresh" onclick="refreshCaptcha('{{ $id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <polyline points="1 20 1 14 7 14"></polyline>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
        </button>
    </div>
    <div class="captcha-input-group">
        <label for="{{ $id }}-input" class="captcha-label">Your answer:</label>
        <input type="text" 
               id="{{ $id }}-input" 
               name="captcha" 
               class="captcha-input" 
               placeholder="Enter your answer"
               autocomplete="off"
               required>
    </div>
</div>

