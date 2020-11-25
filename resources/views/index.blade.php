<html>
<body>

<p>available backend sources:
@foreach($sources as $source)
    | <a href="{{ route('getChannelMapUI', ['source' => $source]) }}">{{ $source }}</a>
@endforeach
    |</p>

</body>
</html>
