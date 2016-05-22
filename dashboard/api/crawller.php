
<?php
    $src = '';
    $mode = 'android';
    if($mode == 'android'){
        $dom = new DOMDocument('1.0');
        $classname = "cover-image";

        $storeurl = "https://play.google.com/store/apps";
        $package = "br.brunovercosa.metrobh";

        @$dom->loadHTMLFile($storeurl."/details?id=".$package);
        $nodes = array();
        $nodes = $dom->getElementsByTagName("img");
        foreach ($nodes as $element) {
           $c = $element->getAttribute("class");
           if ($c == $classname){
                $src =  $element->getAttribute("src");
                break;
           }
        }
    }else if($mode == 'ios'){
        $appid = '1049170431';
        $url = 'http://itunes.apple.com/lookup?id=' . $appid;
        $json = file_get_contents($url);
        $obj = json_decode($json);
        $src = $obj->results[0]->artworkUrl512;
    }

    echo "<img src='$src' />";

?>