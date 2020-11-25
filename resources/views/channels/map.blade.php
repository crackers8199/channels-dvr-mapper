@extends('layouts.main')

@section('content')
<h1>{{ $source }}</h1>

@include('channels.includes.routeLinks')

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
@endsection
