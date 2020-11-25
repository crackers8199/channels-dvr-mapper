<html>
<body>

<p>available backend sources:
    @foreach($sources as $source)
        | <a href="{{ route('getChannelMapUI', ['source' => $source]) }}">{{ $source }}</a>
    @endforeach
    |</p>

<h1>Mapping Channels for Source: {{ $source }}</h1>

<form action="{{ route('applyChannelMap', ['source' => $source]) }}" method="POST">
@csrf

<table border="1" cellpadding="5">

    <tr>
        <th>Channel</th>
        <th>DVR Channel Number</th>
        <th>Re-Mapped Channel Number</th>
    </tr>

    @foreach($channels as $channel)

        <tr>
            <td>{{ $channel->GuideName }}</td>
            <td>{{ $channel->GuideNumber }}</td>
            <td>
                <input type="text" name="mapped_channel_num_{{ $channel->GuideNumber }}" value="{{ ($channel->GuideNumber != $channel->mapped_channel_number) ? $channel->mapped_channel_number : '' }}" />
            </td>
        </tr>

    @endforeach

</table>
<br />
<input type="submit" value="Save Channel Map" />
</form>

</body>
</html>
