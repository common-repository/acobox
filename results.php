<?php
// config options
$imageBaseURL = 'http://acobox.com/';
$url = 'http://acobox.com/xmlrpc.php';
$maxImagesToDisplay = 25;
// end config options

if (isset($_GET['list'])){


include('xml_parser.php');

$imgCount = 0;
$imgServed = 0;

$words = explode(",",$_GET['list']);

$previousImages = array();

      foreach($words as $word){

      $request = "<methodCall><methodName>searchwebserver.getResults</methodName><params><param><value><string>$word</string></value></param></params></methodCall>";

      $session = curl_init($url);
      curl_setopt($session, CURLOPT_HEADER, false);
      curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($session, CURLOPT_POST, true);
      curl_setopt($session, CURLOPT_POSTFIELDS,$request);
      $xml = curl_exec($session);
      curl_close($session);
      $response = xml2array($xml);

      //print_r($response);

            if (count($response['methodResponse']['params']['param']['value'],1) > 2){
            $results = $response['methodResponse']['params']['param']['value']['array']['data']['value'];



                  if (count($results) > 1){

                      for($i=0; $i< count($results); $i++){
                      $imgServed++;

                          if ($imgServed <= $maxImagesToDisplay) {

                          $imgCount++;

                          $description = $response['methodResponse']['params']['param']['value']['array']['data']['value'][$i]['struct']['member'][0]['value']['string']['value'];

                          $thumbnail = $response['methodResponse']['params']['param']['value']['array']['data']['value'][$i]['struct']['member'][1]['value']['string']['value'];

                          $pageURL = $response['methodResponse']['params']['param']['value']['array']['data']['value'][$i]['struct']['member'][3]['value']['string']['value'];

                              if(!in_array($pageURL, $previousImages)){

                              $previousImages[] = $pageURL;
                              echo '<div style="float: left;width: 120px; height: 154px; border:1px solid #ccc; text-align: center; padding: 6px; margin-right: 6px; margin-bottom: 6px;"><div style="padding: 4px;">'.$description.'</div><br />';
                              echo '<form action="'.$pageURL.'" method="post" target="acoSearchResults"><input type="image" alt="Grab Image Code" src="'.$imageBaseURL.$thumbnail.'" /></form></div>';
                              }

                          $previousImages[] = $thumbnail;

                          }
                      }


                  } else {

                  $description = $response['methodResponse']['params']['param']['value']['array']['data']['value']['struct']['member'][0]['value']['string']['value'];

                  $thumbnail = $response['methodResponse']['params']['param']['value']['array']['data']['value']['struct']['member'][1]['value']['string']['value'];

                  $pageURL = $response['methodResponse']['params']['param']['value']['array']['data']['value']['struct']['member'][3]['value']['string']['value'];
                  $previousImages[] = $pageURL;

                  echo '<div style="float: left;width: 120px; border:1px solid #ccc; text-align: center; padding: 6px; margin-right: 6px;"><div style="padding: 4px;">'.$description.'</div><br />';
                   echo '<form action="'.$pageURL.'" method="post" target="acoSearchResults"><input type="image" alt="Grab Image Code" src="'.$imageBaseURL.$thumbnail.'" /></form></div>';


                  }

            }
      }
}
?>