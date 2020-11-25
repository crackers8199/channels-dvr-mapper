#EXTINF:0 channel-id=“{{ $channel->GuideNumber }}” channel-number="{{ $channel->mappedChannelNum }}" tvg-chno=“{{ $channel->mappedChannelNum }}” tvg-id="{{ $channel->mappedChannelNum }}" tvc-guide-stationid="{{ $channel->Station ?? '' }}" tvg-name="{{ $channel->GuideName }}" tvg-logo="{{ $channel->Logo ?? '' }}" group-title="{{ '' }}",{{ $channel->GuideName }}
{{ $channelsBackendUrl }}/devices/{{ $source }}/channels/{{ $channel->GuideNumber }}/stream.mpg

