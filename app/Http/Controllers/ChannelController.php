<?php

namespace App\Http\Controllers;

use App\Models\DvrChannel;
use App\Services\ChannelsBackendService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    protected $channelsBackend;

    public function __construct()
    {
        $this->channelsBackend = new ChannelsBackendService();
    }

    // todo: add title tags and whatnot to views
    public function index()
    {
        $sources = $this->channelsBackend->getDevices();

        if($sources->count() > 0) {
            return view('index', ['sources' => $sources]);
        }
        else {
            return view('empty', ['channelsBackendUrl' => $this->channelsBackend->getBaseUrl()]);
        }
    }

    public function list(Request $request)
    {
        $source = $request->source;
        if(!$this->channelsBackend->isValidDevice($source)) {
            throw new Exception('Invalid source detected.');
        }

        $channels = $this->channelsBackend->getScannedChannels($source);

        $existingChannels = DvrChannel::pluck('mapped_channel_number', 'guide_number');

        foreach($channels as &$ch) {
            if(($mapped = $existingChannels->get($ch->GuideNumber)) !== false) {
                // existing channel number in db
                $ch->mapped_channel_number = $mapped;
            }
            else {
                // new channel number for db
                $ch->mapped_channel_number = $ch->GuideNumber;
            }

        }

        return view('channels',
            [
                'channels' => $channels,
                'source' => $source,
                'sources' => $this->channelsBackend->getDevices(),
            ]
        );

    }

    public function map(Request $request)
    {
        $source = $request->source;
        if(!$this->channelsBackend->isValidDevice($source)) {
            throw new Exception('Invalid source detected.');
        }

        $channelMaps = $request->except('_token');

        $data = [];
        foreach($channelMaps as $guideNumberIdx => $mappedNumber) {
            list(,,,$guideNumber) = explode('_', $guideNumberIdx);
            $data[] = [
                'guide_number' => $guideNumber,
                'mapped_channel_number' => $mappedNumber ?? $guideNumber,
            ];
        }

        DvrChannel::upsert(
            $data,
            [ 'guide_number' ],
            [ 'mapped_channel_number' ]
        );

        return redirect(route('getChannelMapUI', ['source' => $source]));

    }

    public function playlist(Request $request)
    {
        $source = $request->source;
        if(!$this->channelsBackend->isValidDevice($source)) {
            throw new Exception('Invalid source detected.');
        }

        $scannedChannels = $this->channelsBackend->getScannedChannels($source);
        $existingChannels = DvrChannel::pluck('mapped_channel_number', 'guide_number');

        echo "#EXTM3U\n\n";
        foreach($scannedChannels as $channel) {
            $mappedChannelNum = $existingChannels->get($channel->GuideNumber) ?? $channel->GuideNumber;
            printf(
                '#EXTINF:0 channel-id=“%s” channel-number="%s" tvg-chno=“%s” tvg-id="%s" ' .
                    'tvc-guide-stationid="%s" tvg-name="%s" tvg-logo="%s" group-title="%s",%s' . "\n" .
                    '%s/devices/%s/channels/%s/stream.mpg' .
                    "\n\n",
                $channel->GuideNumber,      // channel-id
                $mappedChannelNum,          // channel-number
                $mappedChannelNum,          // tvg-chno
                $mappedChannelNum,          // tvg-id
                $channel->Station ?? '',    // tvc-guide-stationid
                $channel->GuideName,        // tvg-name
                $channel->Logo ?? '',       // tvg-logo
                '',                         // group-title
                $channel->GuideName,
                $this->channelsBackend->getBaseUrl(),
                $source,
                $channel->GuideNumber);

        }

    }

    public function xmltv(Request $request)
    {
        $source = $request->source;
        if(!$this->channelsBackend->isValidDevice($source)) {
            throw new Exception('Invalid source detected.');
        }

        $guideDays = env('CHANNELS_GUIDE_DAYS', config('channels.max_guide_days'));
        if($guideDays > config('channels.max_guide_days')) {
            $guideDays = config('channels.max_guide_days');
        }

        $guideChunkDuration =
            env('CHANNELS_GUIDE_DURATION', config('channels.max_guide_chunk_seconds'));

        if($guideChunkDuration > config('channels.max_guide_chunk_seconds')) {
            $guideChunkDuration = config('channels.max_guide_chunk_seconds');
        }

        $endGuideTime = Carbon::now()->addDays($guideDays);
        $guideTime = Carbon::now();

        $existingChannels = DvrChannel::pluck('guide_number', 'mapped_channel_number');

        while($guideTime < $endGuideTime) {

            $guideData = $this->channelsBackend
                ->getGuideData($source, $guideTime->timestamp, $guideChunkDuration);

            foreach($guideData as $data) {

                $mappedChannelNum =
                    $existingChannels->search($data->Channel->Number) ?? $data->Channel->Number;

                $channelId = $mappedChannelNum;

                echo "\t<channel id=\"" . $channelId . "\">\n";
                printf("\t\t<display-name>%s %s</display-name>\n", $mappedChannelNum, $data->Channel->Name);

                if(isset($data->Channel->Station)) {
                    printf("\t\t<display-name>%s %s %s</display-name>\n",
                        $mappedChannelNum, $data->Channel->Name, $data->Channel->Station);
                }

                printf("\t\t<display-name>%s</display-name>\n", $mappedChannelNum);
                printf("\t\t<display-name>%s</display-name>\n", $data->Channel->Name);

                if (isset($data->Channel->Image)) {
                    echo "\t\t<icon src=\"" . $data->Channel->Image . "\" />\n";
                }
                echo "\t</channel>\n";

                foreach($data->Airings as $air) {

                    $startTime = Carbon::createFromTimestamp($air->Time, 'America/Los_Angeles');
                    $endTime = $startTime->copy()->addSeconds($air->Duration);

                    printf("\t<programme start=\"%s -0800\" stop=\"%s -0800\" channel=\"%s\">\n",
                        $startTime->format('YmdHis'),
                        $endTime->format('YmdHis'),
                        $channelId);

                    printf("\t\t<title lang=\"en\">%s</title>\n", htmlspecialchars($air->Title));

                    if(isset($air->EpisodeTitle)) {
                        printf("\t\t<sub-title lang=\"en\">%s</sub-title>\n", htmlspecialchars($air->EpisodeTitle));
                    }

                    printf("\t\t<desc lang=\"en\">%s</desc>\n", htmlspecialchars($air->Summary));

                    if(isset($air->OriginalDate)) {
                        printf("\t\t<date>%s</date>\n", Carbon::parse($air->OriginalDate)->format('Ymd'));
                    }

                    // cycle through categories here
                    foreach(($air->Categories ?? []) as $cat) {
                        printf("\t\t<category>%s</category>\n", htmlspecialchars($cat));
                    }

                    if(isset($air->Image)) {
                        printf("\t\t<icon src=\"%s\" />\n", $air->Image);
                    }

                    if($air->SeasonNumber != 0 && $air->EpisodeNumber != 0) {
                        printf(
                            "\t\t<episode-num system=\"xmltv_ns\">%d.%d.</episode-num>\n",
                            ($air->SeasonNumber-1), ($air->EpisodeNumber-1)
                        );
                    }

                    // search Tags for New
                    if(collect($air->Tags)->search('New') !== false) {
                        printf("\t\t<new />\n");
                    }

                    echo "\t</programme>\n";

                }

            }

            $guideTime->addSeconds($guideChunkDuration);

        }
        echo "</tv>";
    }
}
