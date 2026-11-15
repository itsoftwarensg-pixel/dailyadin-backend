@props(['message'])

@if($message)
    <span style="color: red;">{{ $message }}</span>
@endif
