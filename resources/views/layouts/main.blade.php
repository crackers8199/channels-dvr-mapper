<html>
<head>
    <title>Channels DVR Channel Mapper</title>
</head>
<body>

<p>available backend sources:
    @foreach($sources as $source)
        | <a href="{{ route('getChannelMapUI', ['source' => $source]) }}">{{ $source }}</a>
    @endforeach
    | <a href="{{ $channelsBackendUrl }}">Channels DVR Backend</a></p>

@yield('content')

</body>
</html>
