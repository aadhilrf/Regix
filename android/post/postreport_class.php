<?php
$url = "http://digitalbrains.esy.es/android/report_class.php";
$data = array("regix_id"=>"amscg","year"=>"3","department"=>"cse","section"=>"1","overall"=>"1");

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { echo "error"; }
else{
echo $result;    
}
?>