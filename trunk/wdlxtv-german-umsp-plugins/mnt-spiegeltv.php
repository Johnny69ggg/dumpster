<?php
if (!empty($_GET['id'])) {

    $xml = file_get_contents('http://video.spiegel.de/flash/'.$_GET['id'].'.xml');
	preg_match_all('|<filename>(.*?)</filename>|i',$xml,$row);
   
   foreach ($row[1] as $str) {
    if (strpos($str,'mp4')!==false) {
        $url = 'http://video.spiegel.de/flash/'.$str;
        break;
       }
    }
    
    ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
    header('Content-Type: video/mp4');
    readfile($url);
    die;       

}



function _pluginMain($prmQuery) {
		$queryData = array();
		parse_str($prmQuery, $queryData); 

        $links = array(
        "aktuell2"=>"Aktuell",
        "newsmitfragmenten"=>"News",
        "politikundwirtschaft"=>"Politik & Wirtschaft",
        "panorama2"=>"Panorama",
        "kino"=>"Kino",
        "kultur"=>"Kultur",
        "sport2"=>"Sport",
        "wissenundtechnik"=>"Wissen & Technik",
        "blogs"=>"Serien & Blogs",
        "spiegel tv magazin"=>"SPIEGEL TV Magazin"
        );
     
        
        if (!isset($queryData['section'])) {
            $items = array();
            foreach ($links as $sec=>$title) {
                $dataStr = http_build_query(array('section' => $sec), 'pluginvar_');
                $items[] = array (
                    'id'           => 'umsp://plugins/mnt-spiegeltv?'.$dataStr,
                    'dc:title'     => $title,
                    'upnp:class'   => 'object.container',
                   );
            }
            return $items;
         }

         
         if (!empty($queryData['section'])) {
            $items = array();
            $url = 'http://www1.spiegel.de/active/playlist/fcgi/playlist.fcgi/asset=flashvideo/mode=list/displaycategory='.$queryData['section'].'/start=1/count=48';
    
            $xml = file_get_contents($url);
            $rss = new SimpleXMLElement($xml);
            foreach ($rss->xpath('//listitem') as $item) {
                        $id = $item->videoid;
                        $headline=$item->headline;
            
                        $dataString = http_build_query(array(
                            'id'         => $id,
                        ), 'pluginvar_');
                        $items[] = array (
                                        'id'            => 'umsp://plugins/mnt-spiegeltv?' . $dataString,
                                        'dc:title'      => htmlentities($headline),
                                        'res'		=> 'http://127.0.0.1/umsp/plugins/mnt-spiegeltv.php?id='.$id,
                                        'upnp:class'	=> 'object.item.videoitem',
                                        'protocolInfo'	=> 'http-get:*:video/mp4:*',
                                );

            } #end while
         
            return $items;
         }

} # end function
?>