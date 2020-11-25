#EXTM3U

@foreach($scannedChannels as $channel)
    @include('channels.playlist.channel')
@endforeach
