<channel id="{{ $data->Channel->channelId }}">
@foreach($data->Channel->displayNames as $name)
    <display-name>{{ $name }}</display-name>
@endforeach
@if(isset($data->Channel->Image))
    <icon src="{{ $data->Channel->Image }}" />
@endif
</channel>
