<a href="{{ route('company.create.index') }}">
    @if (isset($superChannel))
        <img src="{{ asset('storage/'.$superChannel->logo) }}" alt="{{ $superChannel->title }}" style="max-height: {{ $height }};" />
    @else
        <span class="icon gun-logo-icon"></span>
    @endif
</a>