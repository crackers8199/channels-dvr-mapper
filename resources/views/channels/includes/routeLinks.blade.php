<a class="dropdown-item" href="{{ route('internalPlaylist', ['source' => $source]) }}">Internal M3U Playlist</a>
<a class="dropdown-item" href="{{ route('externalPlaylist', ['source' => $source, 'external' => 'external']) }}">External M3U Playlist</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{ route('sourceXmlTv', ['source' => $source]) }}">XML TV EPG</a>
