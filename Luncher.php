<?php
define('BOT_TOKEN', '242765013:AAF-Tbfj3vHMCD7TyS0N9W6J2SOPzvJw0do');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}
function processMessage($message) {
  // process incoming message
  $boolean = file_get_contents('booleans.txt');
  $booleans= explode("\n",$boolean);
  $admin = 251862863;
  $message_id = $message['message_id'];
  $rpto = $message['reply_to_message']['forward_from']['id'];
  $chat_id = $message['chat']['id'];
  $txxxtt = file_get_contents('msgs.txt');
  $pmembersiddd= explode("-!-@-#-$",$txxxtt);
  if (isset($message['photo'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>
<b>Get Out Of Here Idiot</b> <i>don't send pm again</i>" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
    $photo = $message['photo'];
    $photoid = json_encode($photo, JSON_PRETTY_PRINT);
    $photoidd = json_encode($photoid, JSON_PRETTY_PRINT); 
    $photoidd = str_replace('"[\n    {\n        \"file_id\": \"','',$photoidd);
    $pos = strpos($photoidd, '",\n');
    //$pphoto = strrpos($photoid,'",\n        \"file_size\": ',1);
    $pos = $pos -1;
    $substtr = substr($photoidd, 0, $pos);
    $caption = $message['caption'];
    if($caption != "")
    {
    apiRequest("sendphoto", array('chat_id' => $rpto, "photo" => $substtr,"caption" =>$caption));
    }
    else{
        apiRequest("sendphoto", array('chat_id' => $rpto, "photo" => $substtr));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Done.</b>" ,"parse_mode" =>"HTML"));
    
}  else if ($chat_id == $admin && $booleans[0] == "true") {
    
    $photo = $message['photo'];
    $photoid = json_encode($photo, JSON_PRETTY_PRINT);
    $photoidd = json_encode($photoid, JSON_PRETTY_PRINT); 
    $photoidd = str_replace('"[\n    {\n        \"file_id\": \"','',$photoidd);
    $pos = strpos($photoidd, '",\n');
    //$pphoto = strrpos($photoid,'",\n        \"file_size\": ',1);
    $pos = $pos -1;
    $substtr = substr($photoidd, 0, $pos);
    $caption = $message['caption'];
    
    
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			if($caption != "")
    {
    apiRequest("sendphoto", array('chat_id' => $membersidd[$y], "photo" => $substtr,"caption" =>$caption));
    }
    else{
        apiRequest("sendphoto", array('chat_id' => $membersidd[$y], "photo" => $substtr));
    }
			
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "📦 پیام شما به  ".$memcout." مخاطب ازسال شد.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
    }
  }
    if (isset($message['video'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1],"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>
<b>Get Out Of Here Idiot</b> <i>don't send pm again</i>" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $video = $message['video']['file_id'];
    $caption = $message['caption'];
    //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $video ,"parse_mode" =>"HTML"));
    if($caption != "")
    {
    apiRequest("sendvideo", array('chat_id' => $rpto, "video" => $video,"caption" =>$caption));
    }
    else{
        apiRequest("sendvideo", array('chat_id' => $rpto, "video" => $video));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" =>"<b>Done.</b>","parse_mode" =>"HTML"));
    
}
else if ($chat_id == $admin && $booleans[0] == "true") {
    $video = $message['video']['file_id'];
    $caption = $message['caption'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			if($caption != "")
    {
    apiRequest("sendvideo", array('chat_id' => $membersidd[$y], "video" => $video,"caption" =>$caption));
    }
    else{
        apiRequest("sendvideo", array('chat_id' => $membersidd[$y], "video" => $video));
    }
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "📦 پیام شما به  ".$memcout." مخاطب ازسال شد.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
    }
  }
   if (isset($message['sticker'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>
<b>Get Out Of Here Idiot</b> <i>don't send pm again</i>" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $sticker = $message['sticker']['file_id'];
   
    apiRequest("sendsticker", array('chat_id' => $rpto, "sticker" => $sticker));
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" =>"<b>Done.</b>" ,"parse_mode" =>"HTML"));
    
}

 else if ($chat_id == $admin && $booleans[0] == "true") {
       $sticker = $message['sticker']['file_id'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			//apiRequest("sendMessage", array('chat_id' => $membersidd[$y], "text" => $texttoall,"parse_mode" =>"HTML"));
			
			    apiRequest("sendsticker", array('chat_id' => $membersidd[$y], "sticker" => $sticker));

			
			
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "📦 پیام شما به  ".$memcout." مخاطب ازسال شد.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
}
  }
  
  
  
  if (isset($message['document'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1],"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>
<b>Get Out Of Here Idiot</b> <i>don't send pm again</i>" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $video = $message['document']['file_id'];
    $caption = $message['caption'];
    //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $video ,"parse_mode" =>"HTML"));
    if($caption != "")
    {
    apiRequest("sendDocument", array('chat_id' => $rpto, "document" => $video,"caption" =>$caption));
    }
    else{
        apiRequest("sendDocument", array('chat_id' => $rpto, "document" => $video));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Done.</b>" ,"parse_mode" =>"HTML"));
    
}
 else if ($chat_id == $admin && $booleans[0] == "true") {
    $video = $message['document']['file_id'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){

    apiRequest("sendDocument", array('chat_id' => $membersidd[$y], "document" => $video));
    
			
			
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "📦 پیام شما به  ".$memcout." مخاطب ازسال شد.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
}
  }
  if (isset($message['voice'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>
<b>Get Out Of Here Idiot</b> <i>don't send pm again</i>" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $video = $message['voice']['file_id'];
    $caption = $message['caption'];
    //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $video ,"parse_mode" =>"HTML"));
    if($caption != "")
    {
    apiRequest("sendVoice", array('chat_id' => $rpto, "voice" => $video,"caption" =>$caption));
    }
    else{
        apiRequest("sendVoice", array('chat_id' => $rpto, "voice" => $video));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" =>"<b>Done.</b>","parse_mode" =>"HTML"));
    
}
 else if ($chat_id == $admin && $booleans[0] == "true") {
    $video = $message['voice']['file_id'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){

        apiRequest("sendVoice", array('chat_id' => $membersidd[$y], "voice" => $video));
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "📦 پیام شما به  ".$memcout." مخاطب ازسال شد.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
}
  }
  if (isset($message['audio'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>
<b>Get Out Of Here Idiot</b> <i>don't send pm again</i>" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $video = $message['audio']['file_id'];
    $caption = $message['caption'];
    //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $video ,"parse_mode" =>"HTML"));
    if($caption != "")
    {
    apiRequest("sendaudio", array('chat_id' => $rpto, "audio" => $video,"caption" =>$caption));
    }
    else{
        apiRequest("sendaudio", array('chat_id' => $rpto, "audio" => $video));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Done.</b>" ,"parse_mode" =>"HTML"));
    
}
 else if ($chat_id == $admin && $booleans[0] == "true") {
    $video = $message['audio']['file_id'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){

                apiRequest("sendaudio", array('chat_id' => $membersidd[$y], "audio" => $video));

		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "📦 پیام شما به  ".$memcout." مخاطب ازسال شد.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
}
  }
  if (isset($message['contact'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>
<b>Get Out Of Here Idiot</b> <i>don't send pm again</i>" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $phone = $message['contact']['phone_number'];
    $first = $message['contact']['first_name'];
    
    $last = $message['contact']['last_name'];
    
    //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $video ,"parse_mode" =>"HTML"));
    
    apiRequest("sendcontact", array('chat_id' => $rpto, "phone_number" => $phone,"Last_name" =>$last,"first_name"=> $first));
    
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" =>"<b>Done.</b>","parse_mode" =>"HTML"));
    
}
else if ($chat_id == $admin && $booleans[0] == "true") {
     $phone = $message['contact']['phone_number'];
    $first = $message['contact']['first_name'];
    
    $last = $message['contact']['last_name'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){

    apiRequest("sendcontact", array('chat_id' => $membersidd[$y], "phone_number" => $phone,"Last_name" =>$last,"first_name"=> $first));

		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "📦 پیام شما به  ".$memcout." مخاطب ازسال شد.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
}
  }
  
  
  
  
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    $matches = explode(" ", $text); 
    if ($text=="/start") {
        
        
        
      if($chat_id!=$admin){
      apiRequest("sendMessage", array('chat_id' => $chat_id,"text"=>$pmembersiddd[0] ,"parse_mode"=>"HTML"));

$txxt = file_get_contents('pmembers.txt');
$pmembersid= explode("\n",$txxt);
	if (!in_array($chat_id,$pmembersid)) {
		$aaddd = file_get_contents('pmembers.txt');
		$aaddd .= $chat_id."
";
    	file_put_contents('pmembers.txt',$aaddd);
}

}
if($chat_id==$admin){
  apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => '*Bot Is online*',"parse_mode"=>"MARKDOWN", 'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
}

    } else if ($matches[0] == "/setstart" && $chat_id == $admin) {

    $starttext = str_replace("/setstart","",$text);
            
    file_put_contents('msgs.txt',$starttext."

-!-@-#-$"."
".$pmembersiddd[1]);
apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" =>"Start PM Bot Changed To 

".$starttext.""."


"));
    
    
    
    
    }
    else if ($matches[0] == "/setdone" && $chat_id == $admin) {
        
    $starttext = str_replace("/setdone","",$text);
            
    file_put_contents('msgs.txt',$pmembersiddd[0]."

-!-@-#-$"."
".$starttext);
apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" =>"Send pm Bot Changed To

".$starttext.""."


"));
    
    
    
    
    }
    else if ($text != "" && $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" =>$pmembersiddd[1] ,"parse_mode" =>"HTML"));	
	
}else{
  if($substr !="thisisnarimanfrombeatbotteam"){
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>
<b>Get Out Of Here Idiot</b> <i>don't send pm again</i>" ,"parse_mode" =>"HTML"));	
}
else{
  $textfa =str_replace("thisisnarimanfrombeatbotteam","🖕",$text);;
apiRequest("sendMessage", array('chat_id' => $admin, "text" =>  $textfa,"parse_mode" =>"HTML"));	
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	

}
}
    	
    
    }else if ($text == "Settings" && $chat_id==$admin) {
    		
    		
    		 apiRequestJson("sendMessage", array('chat_id' => $chat_id,"parse_mode"=>"HTML", "text" => '
---------------------------------------------
<b>Clean Members</b>
<b>Clean Block List</b>
<i>for cancell select back</i>
', 'reply_markup' => array(
        'keyboard' => array(array('Clean Members ','Clean Block List '),array('Back')),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
    		
    		
    		
    }else if ($text == "Help" && $chat_id==$admin) {
      
    		apiRequest("sendMessage", array('chat_id' => $admin, "text" => "🔹`1.` */block*
_block user_
—------------------------------
🔹`2. `*/unblock *
_unblock user_
—------------------------------
🔹`3. `*/setstart * *{pm}*
_set start pm_
—------------------------------
🔹`4. `*/setdone * *{pm}*
_set done pm_
➖➖➖➖➖➖➖➖➖➖➖
🔸`1.`*broadcast*
_braodcast pm_
—------------------------------
🔸`2.`*Members*
_your members_
—------------------------------
🔸`3.`*Blocked*
_your block members_
—-------------------------------
🔸`4.`*Settings*
_settings_
*developer* : `@iborn , @ibornbot`
","parse_mode" =>"MARKDOWN",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
    		
    }else if ($text == "Clean Members" && $chat_id==$admin) {
    		
    		
    		$txxt = file_get_contents('pmembers.txt');
        $pmembersid= explode("\n",$txxt);
    		file_put_contents('pmembers.txt',"");
    		apiRequestJson("sendMessage", array('chat_id' => $chat_id,"parse_mode"=>"HTML", "text" => '<b>Members Cleaned</b>.', 'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
    }
    else if ($text == "Clean Block List" && $chat_id==$admin) {
    		
    		
    		$txxt = file_get_contents('banlist.txt');
        $pmembersid= explode("\n",$txxt);
    		file_put_contents('banlist.txt',"");
    		apiRequestJson("sendMessage", array('chat_id' => $chat_id,"parse_mode"=>"HTML", "text" => '<b>Black list Cleaned</b>', 'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
    }
    else if ($text == "Back" && $chat_id==$admin) {
    		apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'bot is online', 'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
        
        
        
    }
    else if ($text =="🗣 Send To All"  && $chat_id == $admin && $booleans[0]=="false") {
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Send Your PM</b>" ,"parse_mode" =>"HTML"));
      $boolean = file_get_contents('booleans.txt');
		  $booleans= explode("\n",$boolean);
	  	$addd = file_get_contents('banlist.txt');
	  	$addd = "true";
    	file_put_contents('booleans.txt',$addd);
    	
    }
      else if ($chat_id == $admin && $booleans[0] == "true") {
    $texttoall =$text;
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			apiRequest("sendMessage", array('chat_id' => $membersidd[$y], "text" => $texttoall,"parse_mode" =>"HTML"));
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "📦 پیام شما به  ".$memcout." مخاطب ازسال شد.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
    }else if($text == "Members" && $chat_id == $admin ){
		$txtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$txtt);
		$mmemcount = count($membersidd) -1;
		 apiRequestJson("sendMessage", array('chat_id' => $chat_id,"parse_mode" =>"HTML", "text" => "<b>Your Members</b> : ".$mmemcount,'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
		
		
	}else if($text == "Blocked" && $chat_id == $admin ){
		$txtt = file_get_contents('banlist.txt');
		$membersidd= explode("\n",$txtt);
		$mmemcount = count($membersidd) -1;
		 apiRequestJson("sendMessage", array('chat_id' => $chat_id,"parse_mode" =>"HTML", "text" => "<b>Your black list</b> : ".$mmemcount,'reply_markup' => array(
        'keyboard' => array(array('broadcast'),array('Help','Members','Blocked'),array("Settings")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
		
		
	}
    else if($rpto != "" && $chat_id == $admin){
    	if($text != "/ban" && $text != "/unban")
    	{
	apiRequest("sendMessage", array('chat_id' => $rpto, "text" => $text ,"parse_mode" =>"HTML"));
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Done.</b>" ,"parse_mode" =>"HTML"));
    	}
    	else
    	{
    		if($text == "/ban"){
    	$txtt = file_get_contents('banlist.txt');
		$banid= explode("\n",$txtt);
	if (!in_array($rpto,$banid)) {
		$addd = file_get_contents('banlist.txt');
		$addd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $addd);
		$addd .= $rpto."
";

    	file_put_contents('banlist.txt',$addd);
    	apiRequest("sendMessage", array('chat_id' => $rpto, "text" => "<b>You Are Banned</b> , <i>Don't Send PM Again</i>." ,"parse_mode" =>"HTML"));
}
		apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Banned</b>" ,"parse_mode" =>"HTML"));
    		}
    	if($text == "/unban"){
    	$txttt = file_get_contents('banlist.txt');
		$banidd= explode("\n",$txttt);
	if (in_array($rpto,$banidd)) {
		$adddd = file_get_contents('banlist.txt');
		$adddd = str_replace($rpto,"",$adddd);
		$adddd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $adddd);
    $adddd .="
";


		$banid= explode("\n",$adddd);
    if($banid[1]=="")
      $adddd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $adddd);

    	file_put_contents('banlist.txt',$adddd);
}
		apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>UnBanned</b>" ,"parse_mode" =>"HTML"));
		apiRequest("sendMessage", array('chat_id' => $rpto, "text" => "<b> You Have Been UnBanned,</b> <i>You Can Send PM Again</i>" ,"parse_mode" =>"HTML"));
    		}
    	}
	}
  } else {
    
  }
}


define('WEBHOOK_URL', 'https://my-site.example.com/secret-path-for-webhooks/');

if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}
