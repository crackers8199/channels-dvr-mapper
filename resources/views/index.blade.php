<html>
<body>

<p>available backend lineups:
@foreach($devices as $device)
    | <a href="{{ route('getChannelMapUI', ['lineup' => $device]) }}">{{ $device }}</a>
@endforeach
    |</p>

</body>
</html>
