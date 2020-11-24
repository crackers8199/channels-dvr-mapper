<html>
<body>
@foreach($devices as $device)

    <a href="{{ route('getChannelMapUI', ['lineup' => $device]) }}">{{ $device }}</a><br /><br />

@endforeach
</body>
</html>
