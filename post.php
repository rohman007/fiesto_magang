<?php
/** /
function httpPost($url,$params)
{
  $postData = '';
   //create name value pairs seperated by &
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;
 
}
$params = array(
   "name" => "Ravishanker Kusuma",
   "age" => "32",
   "location" => "India"
);
echo httpPost("http://esw.fiesto.net/data.php",$params);
/**/
/** /
$xml=<<<END
<?xml version="1.0"?>
<evoucher>
   <command>TOPUP</command>
   <product>S1</product>
   <userid>eazy shop way</userid>
   <time>191001</time>
   <msisdn>08179388230</msisdn>
   <partner_trxid>12001</partner_trxid>
   <signature>115599</signature>
</evoucher>
END;
  $url = "http://110.139.1.198:13165";
  $url = "http://esw.fiesto.net/data.php";
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $url );
  curl_setopt( $ch, CURLOPT_POST, true );
  curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
  curl_setopt($ch,CURLOPT_HEADER, false); 
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml );
  $result = curl_exec($ch);
  
  
  curl_close($ch);
  echo "Hasil testing";
  echo $result;
 /**/ 
  /**/ 

$xml=<<<END
<?xml version="1.0"?>
<evoucher>
<command>TOPUP</command>
<product>S1</product>
<userid>eazy shop way</userid>
<time>191001</time>
<msisdn>08179388230</msisdn>
<partner_trxid>12001</partner_trxid>
<signature>115599</signature>
</evoucher>
END;

$url = "http://110.139.1.198:13165";

  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $url );
  curl_setopt( $ch, CURLOPT_POST, true );
  curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $ch, CURLOPT_POSTFIELDS,$xml);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  $output = curl_exec($ch);
curl_close($ch);
echo "Hasil testing <br>";
echo $output;
if($output=="")
{
echo "<br>tidak respon";
}
   /**/ 
?>
