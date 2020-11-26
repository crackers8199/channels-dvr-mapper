@extends('layouts.main')

@section('content')

<form action="{{ route('applyChannelMap', ['source' => $source]) }}" method="POST">
@csrf

    <div class="row">
        <div class="col-xs-8 text-center">
            <h1>{{ $source }}</h1>
        </div>
        <div class="col-xs-4"></div>
    </div>
    <div class="row">
        <div class="col-xs-8" style="padding-top: 10px;">
            <table border="1" width="100%">

                <tr>
                    <th style="padding: 10px;">Channel</th>
                    <th style="padding: 10px;">DVR Channel Number</th>
                    <th style="padding: 10px;">Re-Mapped Channel Number</th>
                </tr>

                @foreach($channels as $channel)

                    <tr>
                        <td style="padding: 10px;">{{ $channel->GuideName }}</td>
                        <td style="padding: 10px;">{{ $channel->GuideNumber }}</td>
                        <td style="padding: 10px;">
                            <input type="text" class="form-control" name="mapped_channel_num_{{ $channel->GuideNumber }}" value="{{ ($channel->GuideNumber != $channel->mapped_channel_number) ? $channel->mapped_channel_number : '' }}" />
                        </td>
                    </tr>

                @endforeach
            </table>
        </div>
        <div class="col-xs-4 align-left" >
            <input type="submit" value="Save {{ $source }} Channel Map" style="margin: 10px 0px 0px 20px;" />
        </div>
    </div>

</form>
@endsection
