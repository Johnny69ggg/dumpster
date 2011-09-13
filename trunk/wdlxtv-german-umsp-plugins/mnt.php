<?php
function _pluginMain($prmQuery) { 

$myMediaItems[] = array(
        'id'                    => 'umsp://plugins/mnt-spiegeltv',     #Attribute of item
        'parentID'              => '0',                             #Attribute of item
        'restricted'    => '1',                                     #Attribute of item
        'dc:creator'    => 'myCreator',
        'dc:title'              => 'Spiegel TV',
        'dc:date'               => '2009-12-30',
        'upnp:author'   => 'myAuthor',
        'upnp:artist'   => 'myArtist',
        'upnp:album'    => 'myAlbum',
        'upnp:genre'    => 'myGenre',
        'upnp:length'   => '2:10:20',
        'desc'                  => 'myDesc',
        'upnp:class'    => 'object.container',
        'upnp:album_art'=> '',
        'duration'              => 'myDur3',                    #Attribute of res
        'size'                  => 'mySize3',                   #Attribute of res in bytes
        'bitrate'               => 'myBitr',                    #Attribute of res
        'protocolInfo'  => '*:*:*:*',           		#Attribute of res
        'resolution'    => 'myReso',                            #Attribute of res
        'colorDepth'    => 'myColor',                           #Attribute of res
);

$myMediaItems[] = array(
        'id'                    => 'umsp://plugins/mnt-tagesschau',     #Attribute of item
        'parentID'              => '0',                             #Attribute of item
        'restricted'    => '1',                                     #Attribute of item
        'dc:creator'    => 'myCreator',
        'dc:title'              => 'Tagesschau',
        'dc:date'               => '2009-12-30',
        'upnp:author'   => 'myAuthor',
        'upnp:artist'   => 'myArtist',
        'upnp:album'    => 'myAlbum',
        'upnp:genre'    => 'myGenre',
        'upnp:length'   => '2:10:20',
        'desc'                  => 'myDesc',
        'upnp:class'    => 'object.container',
        'upnp:album_art'=> '',
        'duration'              => 'myDur3',                    #Attribute of res
        'size'                  => 'mySize3',                   #Attribute of res in bytes
        'bitrate'               => 'myBitr',                    #Attribute of res
        'protocolInfo'  => '*:*:*:*',           		#Attribute of res
        'resolution'    => 'myReso',                            #Attribute of res
        'colorDepth'    => 'myColor',                           #Attribute of res
);

$myMediaItems[] = array(
        'id'                    => 'umsp://plugins/mnt-zdf',     #Attribute of item
        'parentID'              => '0',                             #Attribute of item
        'restricted'    => '1',                                     #Attribute of item
        'dc:creator'    => 'myCreator',
        'dc:title'              => 'ZDF',
        'dc:date'               => '2009-12-30',
        'upnp:author'   => 'myAuthor',
        'upnp:artist'   => 'myArtist',
        'upnp:album'    => 'myAlbum',
        'upnp:genre'    => 'myGenre',
        'upnp:length'   => '2:10:20',
        'desc'                  => 'myDesc',
        'upnp:class'    => 'object.container',
        'upnp:album_art'=> '',
        'duration'              => 'myDur3',                    #Attribute of res
        'size'                  => 'mySize3',                   #Attribute of res in bytes
        'bitrate'               => 'myBitr',                    #Attribute of res
        'protocolInfo'  => '*:*:*:*',           		#Attribute of res
        'resolution'    => 'myReso',                            #Attribute of res
        'colorDepth'    => 'myColor',                           #Attribute of res
);

return $myMediaItems[];

}