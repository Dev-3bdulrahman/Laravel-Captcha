@php
    $captchaData = app('captcha')->generate('slider', $difficulty);
@endphp

<div class="captcha-slider-wrapper">
    <div class="captcha-slider-container" 
         id="{{ $id }}-container"
         data-position="{{ $captchaData['position']['x'] }}"
         data-tolerance="{{ $captchaData['tolerance'] }}">
        <div class="captcha-slider-background" 
             style="background: {{ $captchaData['background_image'] }}; width: {{ $captchaData['width'] }}px; height: {{ $captchaData['height'] }}px;">
            <div class="captcha-slider-puzzle" 
                 id="{{ $id }}-puzzle"
                 style="left: {{ $captchaData['position']['x'] }}px; top: {{ $captchaData['position']['y'] }}px; width: {{ $captchaData['puzzle_size'] }}px; height: {{ $captchaData['puzzle_size'] }}px;">
            </div>
        </div>
        <div class="captcha-slider-track">
            <div class="captcha-slider-handle" id="{{ $id }}-handle">
                <span>→</span>
            </div>
            <div class="captcha-slider-text">Slide to complete</div>
        </div>
    </div>
    <input type="hidden" id="{{ $id }}-input" name="captcha" value="">
</div>

