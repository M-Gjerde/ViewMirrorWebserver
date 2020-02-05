<?php
function Parse ($url) {
  $fileContents= file_get_contents($url);
  $xml = simplexml_load_string($fileContents, 'SimpleXMLElement', LIBXML_NOCDATA);
  $json = json_encode($xml);
  return $json;
}

$site = $_GET["site"];
echo Parse($site);

