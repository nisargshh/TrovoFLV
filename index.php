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
      ];
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); // Do not send to screen
      $obj1->operationName = "getLiveInfo";
      $obj1->variables->params->userName = $_GET['stream'];
      $obj1->variables->params->requireDecorations = true;
      
      $obj1->extensions->persistedQuery->version = 1;
      $obj1->extensions->persistedQuery->sha256Hash = "7aa67414a6cbc0f23155bad666ab31561f6b11bf6deda4025c7ce805e5a7f187";
      $obj = [$obj1];
      $post_data = json_encode($obj);
      //echo $post_data;
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      $apiResponse = curl_exec($ch);
      curl_close($ch);
      $jsonArrayResponse = json_decode($apiResponse);
      $url = $jsonArrayResponse[0]->data->getLiveInfo->programInfo->streamInfo[1]->playUrl;
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
