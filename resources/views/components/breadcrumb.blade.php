@props(['items' => []])

<nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="breadcrumb-item">
        <a href="{{ route('dashboard') }}" title="Dashboard">Dashboard</a>
    </div>
    
    @foreach($items as $item)
    <span class="breadcrumb-separator">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </span>
    
    @if($loop->last)
    <span class="breadcrumb-item active">{{ $item['label'] }}</span>
    @else
    <div class="breadcrumb-item">
        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
    </div>
    @endif
    @endforeach
</nav>
