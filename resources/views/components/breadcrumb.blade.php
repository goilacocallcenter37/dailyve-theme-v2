@props([
    'items' => [],
    'preset' => 'default', // 'default', 'directory', 'seo'
    'class' => '',
])

@php
$wrapperClass = $class;
$containerClass = 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8';
$navClass = 'flex text-xs md:text-sm font-medium items-center gap-2 flex-wrap';
$showHomeIcon = false;

if ($preset === 'default') {
    $wrapperClass = $wrapperClass ?: 'bg-transparent';
    $containerClass = $containerClass . ' pt-6 pb-4';
    $showHomeIcon = true;
} elseif ($preset === 'directory') {
    $wrapperClass = $wrapperClass ?: 'border-b border-slate-200 bg-white';
    $containerClass = $containerClass . ' py-4';
} elseif ($preset === 'seo') {
    $wrapperClass = $wrapperClass ?: 'route-breadcrumb';
    $containerClass = 'dailyve-container';
}
@endphp

@if ($preset === 'seo')
  <nav class="{{ $wrapperClass }}" aria-label="Breadcrumb">
    <div class="{{ $containerClass }}">
      <ol>
        @foreach ($items as $item)
          @if (!empty($item['url']))
            <li><a href="{{ esc_url($item['url']) }}">{{ $item['title'] }}</a></li>
          @else
            <li aria-current="page">{{ $item['title'] }}</li>
          @endif
        @endforeach
      </ol>
    </div>
  </nav>
@elseif ($preset === 'directory')
  <nav class="{{ $wrapperClass }}" aria-label="Breadcrumb">
    <ol class="{{ $containerClass }} flex flex-wrap items-center gap-2">
      @foreach ($items as $index => $item)
        @if ($index > 0)
          <li aria-hidden="true" class="text-slate-400 select-none">/</li>
        @endif
        <li>
          @if (!empty($item['url']))
            <a class="text-slate-500 hover:text-blue-600 transition-colors" href="{{ esc_url($item['url']) }}">{{ $item['title'] }}</a>
          @else
            <span class="font-semibold text-slate-900" aria-current="page">{{ $item['title'] }}</span>
          @endif
        </li>
      @endforeach
    </ol>
  </nav>
@else
  {{-- default --}}
  <div class="{{ $wrapperClass }}">
    <div class="{{ $containerClass }}">
      <nav class="{{ $navClass }}" aria-label="Breadcrumb">
        @foreach ($items as $index => $item)
          @if ($index > 0)
            <span class="text-slate-400 select-none">/</span>
          @endif

          @if ($loop->last)
            <span class="text-slate-800 font-semibold" aria-current="page">{{ $item['title'] }}</span>
          @else
            <a href="{{ esc_url($item['url'] ?: '/') }}" class="text-slate-500 hover:text-blue-600 transition-colors flex items-center gap-1 group">
              @if ($index === 0 && $showHomeIcon)
                <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                  </path>
                </svg>
              @endif
              {{ $item['title'] }}
            </a>
          @endif
        @endforeach
      </nav>
    </div>
  </div>
@endif
