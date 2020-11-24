<html>
<body>

<br />available backend lineups:
@foreach($devices as $device)
    | <a href="{{ route('getChannelMapUI', ['lineup' => $device]) }}">{{ $device }}</a>
@endforeach
|<br />

<h1>Mapping Channels for Lineup: {{ $channelLineup }}</h1>

<form action="{{ route('applyChannelMap', ['lineup' => $channelLineup]) }}" method="POST">
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
