<programme start="{{ $air->startTime }} -0800" stop="{{ $air->endTime }} -0800" channel="{{ $air->channelId }}">
    <title lang="en">{{ htmlspecialchars($air->Title) }}</title>
@if(isset($air->EpisodeTitle))
    <sub-title lang="en">{{ htmlspecialchars($air->EpisodeTitle) }}</sub-title>
@endif
    <desc lang="en">{{ htmlspecialchars($air->Summary) }}</desc>
@if(isset($air->OriginalDate))
    <date>{{ \Carbon\Carbon::parse($air->OriginalDate)->format('Ymd') }}</date>
@endif
@foreach(($air->Categories ?? []) as $cat)
    <category>{{ htmlspecialchars($cat) }}</category>
@endforeach
@if(isset($air->Image))
    <icon src="{{ $air->Image }}" />
@endif
@if($air->SeasonNumber != 0 && $air->EpisodeNumber != 0)
    <episode-num system="xmltv_ns">{{ ($air->SeasonNumber-1) }}.{{ ($air->EpisodeNumber-1) }}.</episode-num>
@endif
@if(collect($air->Tags)->search('New') !== false)
    <new />
@endif
</programme>
