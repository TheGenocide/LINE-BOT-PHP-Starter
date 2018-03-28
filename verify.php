<?php
  $access_token = 'cJKdyIbmbzVAK91DtllUd4vBBOp7RgvaqxraUkBzRV9CaVgZAey+g4awFInI27+RlBTb9VLJsrpF328chNVoJEgzqRqK1ycKd6JYSXGDOcI8y3w2NLYXYzSnC2z2bcJgGiHzms4ZYHglml8/rDrHCwdB04t89/1O/w1cDnyilFU=';
  $url = 'https://api.line.me/v1/oauth/verify';
  $headers = array('Authorization: Bearer ' . $access_token);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);
  curl_close($ch);
  echo $result;
?>
