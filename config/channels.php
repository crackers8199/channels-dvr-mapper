<?php

const MAX_GUIDE_DURATION = (16 * 86400);
const MAX_BACKEND_CHUNK_SIZE = 86400;

return [

    // maximum number of seconds tptal of guide data that can be requested
    'guideDuration' => min(
        env('CHANNELS_GUIDE_DURATION', MAX_GUIDE_DURATION),
        MAX_GUIDE_DURATION,
    ),

    // maximum number of seconds of guide data that
    //      can be requested from the backend at one time
    'backendChunkSize' => min(
        env('CHANNELS_BACKEND_CHUNK_SIZE', MAX_BACKEND_CHUNK_SIZE),
        MAX_BACKEND_CHUNK_SIZE
    ),

];
