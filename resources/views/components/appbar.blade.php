{{-- 
    Composant AppBar réutilisable
    Usage: @include('components.appbar', ['title' => 'Titre', 'subtitle' => 'Sous-titre', 'icon' => 'bi-icon-name', 'color' => 'primary', 'actions' => $actions])
--}}

@props([
    'title' => 'Titre',
    'subtitle' => 'Description',
    'icon' => 'bi-app',
    'color' => 'primary',
    'actions' => []
])

<div class="appbar {{ $color }}-appbar">
    <div class="appbar-content">
        <div class="appbar-left">
            <div class="appbar-icon">
                <i class="{{ $icon }}"></i>
            </div>
            <div class="appbar-title-section">
                <h1 class="appbar-title">{{ $title }}</h1>
                <div class="appbar-subtitle">
                    <i class="bi bi-shield-check appbar-subtitle-icon"></i>
                    <span class="appbar-subtitle-text">{{ $subtitle }}</span>
                </div>
            </div>
        </div>
        <div class="appbar-right">
            @foreach($actions as $action)
                @if(isset($action['type']) && $action['type'] === 'primary')
                    <a href="{{ $action['url'] }}" class="appbar-btn">
                        <i class="{{ $action['icon'] ?? 'bi-plus' }}"></i>
                        <span>{{ $action['label'] }}</span>
                    </a>
                @else
                    <a href="{{ $action['url'] }}" class="appbar-btn-secondary">
                        <i class="{{ $action['icon'] ?? 'bi-gear' }}"></i>
                        <span>{{ $action['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
