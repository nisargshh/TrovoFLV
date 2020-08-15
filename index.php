<?php
  error_reporting(E_ERROR | E_PARSE);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title></title>
    <link rel="stylesheet" type="text/css" href="index.css" />
  </head>
  <body>
  <?php
    if($_GET['stream']){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,"https://gql.trovo.live/");
      curl_setopt($ch, CURLOPT_POST, 1);
      $headers = [
        'Content-Type: application/json',
        'comminfo: {"session":{"uid":0,"openid":""},"session_ext":{"token_type":2,"token":"","token_channel":0},"app_info":{"version_code":0,"version_name":"","platform":4,"terminal_type":2,"pvid":"781983539220081405","language":"en-US","client_type":4},"client_info":{"device_info":"{"tid":"15973856294109155"}"}}'
      ];
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); // Do not send to screen
      $obj1->query = 'query getLiveInfo($params: GetLiveInfoReqInput){ getLiveInfo(params: $params){ streamerInfo{uid userName} programInfo{streamInfo{bitrate playUrl}} } }';
      $obj1->variables->params->userName = $_GET['stream'];
      $post_data = json_encode($obj1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      $apiResponse = curl_exec($ch);
      curl_close($ch);
      $jsonArrayResponse = json_decode($apiResponse);
      $url = $jsonArrayResponse->data->getLiveInfo->programInfo->streamInfo[1]->playUrl;
    }
  ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flv.js/1.5.0/flv.min.js"></script>
    <div class="container">
      <video id="videoElement" controls autoplay></video>
    </div>

    <script>
      if (flvjs.isSupported()) {
        var videoElement = document.getElementById('videoElement');
        var flvPlayer = flvjs.createPlayer({
          type: 'flv',
          isLive: true,
          url:
            '<?php echo $url ?>',
        });
        flvPlayer.attachMediaElement(videoElement);
        flvPlayer.load();
        flvPlayer.play();
      }
    </script>
  </body>
</html>
