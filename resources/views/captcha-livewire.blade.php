@php
    $type = $type ?? config('captcha.default', 'image');
    $difficulty = $difficulty ?? config('captcha.difficulty', 'medium');
    $style = $style ?? config('captcha.style', 'default');
    $id = $id ?? 'captcha-' . uniqid();
@endphp

<div class="captcha-container captcha-style-{{ $style }}" id="{{ $id }}" data-type="{{ $type }}" data-difficulty="{{ $difficulty }}">
    @if($type === 'math')
        @include('captcha::types.math-livewire', ['id' => $id, 'difficulty' => $difficulty, 'style' => $style])
    @else
        {{-- For other types, use the default views --}}
        @if($type === 'image')
            @include('captcha::types.image', ['id' => $id, 'difficulty' => $difficulty, 'style' => $style])
        @elseif($type === 'text')
            @include('captcha::types.text', ['id' => $id, 'difficulty' => $difficulty, 'style' => $style])
        @elseif($type === 'slider')
            @include('captcha::types.slider', ['id' => $id, 'difficulty' => $difficulty, 'style' => $style])
        @endif
    @endif
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/dashboard/v1/css/captcha.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/dashboard/v1/js/captcha.js') }}"></script>
    @endpush
@endonce

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const captcha = new LaravelCaptcha('{{ $id }}', {
            type: '{{ $type }}',
            difficulty: '{{ $difficulty }}',
            style: '{{ $style }}'
        });
    });
</script>
@endpush

