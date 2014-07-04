<?php
/*
Tool to convert translated csv file to translation memory (TMX)
*/

$dest_lang = 'BG';
$src = 'tmx/1.6.x/bg_BG';
function xmlspecialchars($text) {
    return str_replace('&#039;', '&apos;', htmlspecialchars($text, ENT_QUOTES));
}


$dir = scandir($src);

$src_list = array();

for($i=0;$i<count($dir);$i++) {
    if(is_file($src . '/' . $dir[$i])) {
        $f_src = fopen($src . '/' . $dir[$i],"r");


        while($r = fgetcsv($f_src,1000)) {
            $src_list[(string)$r[0]] = $r[1];
        }

        fclose($f_src);
    }
}
$f_out = fopen('1.6_to_1.7.tmx','w');

$str = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE tmx SYSTEM "tmx11.dtd">
<tmx version="1.1">
  <header
    creationtool="OmegaT"
    creationtoolversion="1.7.3_1"
    segtype="sentence"
    o-tmf="OmegaT TMX"
    adminlang="EN-US"
    srclang="EN"
    datatype="plaintext"
  >
  </header>
  <body>
EOT;

fwrite($f_out,$str);

reset($src_list);
while(list($k,$v)=each($src_list)) {
    $k = xmlspecialchars($k);
    $v = xmlspecialchars($v);
    $str = <<<EOT
    <tu>
      <tuv lang="EN">
        <seg>$k</seg>
      </tuv>
      <tuv lang="$dest_lang">
        <seg>$v</seg>
      </tuv>
    </tu>
EOT;
    fwrite($f_out,$str);
}

$str = "</body>\n</tmx>";

fwrite($f_out,$str);

fclose($f_out);
