
<?php

function crawStore($mode,$appid){
    $src = '';
    if($mode == 1){
        $dom = new DOMDocument('1.0');
        $classname = "cover-image";

        $storeurl = "https://play.google.com/store/apps";
       
        @$dom->loadHTMLFile($storeurl."/details?id=".$appid);
        $nodes = array();
        $nodes = $dom->getElementsByTagName("img");
        foreach ($nodes as $element) {
           $c = $element->getAttribute("class");
           if ($c == $classname){
                $src =  $element->getAttribute("src");
                break;
           }
        }
    }else if($mode == 2){

        $url = 'http://itunes.apple.com/lookup?id=' . $appid;
        $json = file_get_contents($url);
        $obj = json_decode($json);
        $src = $obj->results[0]->artworkUrl512;
    }

    return $src;
}