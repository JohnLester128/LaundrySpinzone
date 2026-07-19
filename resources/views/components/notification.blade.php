@php
    $types = [
        'success' => 'green',
        'error' => 'red',
        'warning' => 'orange',
    ];
@endphp

@foreach ($types as $key => $color)
    @if (session($key))
        <div style="color:{{ $color }}; padding:10px; border:1px solid {{ $color }}; margin-bottom:10px;">
            {{ session($key) }}
        </div>
    @endif
@endforeach

@if ($errors->any())
    <ul style="color:red; padding:10px; border:1px solid red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif