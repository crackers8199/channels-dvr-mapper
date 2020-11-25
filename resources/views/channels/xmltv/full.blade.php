<tv>

@foreach($guideData as $data)
@include('channels.xmltv.channel')

@foreach($data->Airings as $air)
@include('channels.xmltv.airing')
@endforeach
@endforeach

</tv>
