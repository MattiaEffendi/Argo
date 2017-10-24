<?php


$version = "1.0";

if(!file_exists('config.php')){
    echo "<b>Error</b>: you must generate a config.php file from the config.example.php file in order to use the bot!";
    exit;
}else {
    include "config.php";
}
?>
<style>
    .outer {
        display: table;
        position: absolute;
        height: 90%;
        width: 100%;
    }

    .middle {
        display: table-cell;
        vertical-align: middle;
    }

    .inner {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        text-align: center;
    }
</style>
<div class="middle">
    <div class="inner">
        <div class="outer">
        <font face="Verdana"><b>ArgoBot <?php echo $version; ?> | by Doppio</b><br />Bot is working!<br />Code errors:<br /><br /></font>
</div></div></div>
<?php
/*$extensions = array('curl');
foreach($extensions as $extension){
    if(!extension_loaded($extension)) echo "<b>Error:</b> Install the php7.0-" . $extension . " extension to make the bot work.";
    exit;
}*/


$api = "bot" . $API;



require 'database.php';
require 'class-http-request.php';
$content = file_get_contents("php://input");
$update = json_decode($content, true);

$config = array(

"formattazione_predefinita" => "HTML",
     //o "Markdown" o "" per nulla


"formattazione_messaggi_globali" => "HTML",



"nascondi_anteprima_link" => false,


"tastiera_predefinita" => "inline",
       //metti "normale" per mettere le tastiere vecchie


"funziona_nei_canali" => true,
"funziona_messaggi_modificati" => true,
"funziona_messaggi_modificati_canali" => true,


);

$chatID = $update["message"]["chat"]["id"];
$userID = $update["message"]["from"]["id"];
$msg = $update["message"]["text"];
$username = $update["message"]["from"]["username"];
$nome = $update["message"]["from"]["first_name"];
$cognome = $update["message"]["from"]["last_name"];
if($chatID<0)
{
$titolo = $update["message"]["chat"]["title"];
$usernamechat = $update["message"]["chat"]["username"];
}

$voice = $update["message"]["voice"]["file_id"];
$photo = $update["message"]["photo"][0]["file_id"];
$document = $update["message"]["document"]["file_id"];
$audio = $update["message"]["audio"]["file_id"];
$sticker = $update["message"]["sticker"]["file_id"];

if($update["callback_query"])
{
$cbid = $update["callback_query"]["id"];
$cbdata = $update["callback_query"]["data"];
$cbmid = $update["callback_query"]["message"]["message_id"];
$chatID = $update["callback_query"]["message"]["chat"]["id"];
$userID = $update["callback_query"]["from"]["id"];
$nome = $update["callback_query"]["from"]["first_name"];
$cognome = $update["callback_query"]["from"]["last_name"];
$username = $update["callback_query"]["from"]["username"];
}else{
    $cbdata = null;
}

if($update) unlink('input.json'); file_put_contents('input.json', $content);

function sm($chatID, $text, $rmf = false, $pm = 'pred', $dis = false, $replyto = false, $inline = 'pred')
{
global $api;
global $userID;
global $update;
global $config;


if($pm=='pred') $pm = $config["formattazione_predefinita"];

if($inline=='pred')
{
if($config["tastiera_predefinita"] == "inline") $inline = true;
elseif($config["tastiera_predefinita"] == "normale")
$inline = false;
}
if($rmf == "nascondi") $inline = false;


$dal = $config["nascondi_anteprima_link"];

if(!$inline)
{
if($rmf == 'nascondi')
{
$rm = array('hide_keyboard' => true
);
}else{
$rm = array('keyboard' => $rmf,
'resize_keyboard' => true
);
}
}else{
$rm = array('inline_keyboard' => $rmf,
);
}
$rm = json_encode($rm);

$args = array(
'chat_id' => $chatID,
'text' => $text,
'disable_notification' => $dis,
'parse_mode' => $pm
);
if($dal) $args['disable_web_page_preview'] = $dal;
if($replyto) $args['reply_to_message_id'] = $replyto;
if($rmf) $args['reply_markup'] = $rm;
if($text)
{
$r = new HttpRequest("post", "https://api.telegram.org/$api/sendmessage", $args);
$rr = $r->getResponse();
$ar = json_decode($rr, true);
$ok = $ar["ok"]; //false
$e403 = $ar["error_code"];
if($e403 == "403")
{
return false;
}elseif($e403){
return false;
}else{
return $rr;
}
}
}

function si($chatID, $img, $rmf = false, $cap = '')
{
global $api;
global $userID;
global $update;



$rm = array('inline_keyboard' => $rmf,
);
$rm = json_encode($rm);


if(strpos($img, "."))
{
$img = str_replace("index.php","",$_SERVER['SCRIPT_URI']).$img;
}
$args = array(
'chat_id' => $chatID,
'photo' => $img,
'caption' => $cap
);
if($rmf) $args['reply_markup'] = $rm;
$r = new HttpRequest("post", "https://api.telegram.org/$api/sendPhoto", $args);




$rr = $r->getResponse();
$ar = json_decode($rr, true);
$ok = $ar["ok"]; //false
$e403 = $ar["error_code"];
if($e403 == "403")
{
return false;
}elseif($e403){
return false;
}else{
return true;
}
}

function sv($chatID, $img, $rmf = false, $cap = '')
{
global $api;
global $userID;
global $update;



$rm = array('inline_keyboard' => $rmf,
);
$rm = json_encode($rm);


if(strpos($img, "."))
{
$img = str_replace("index.php","",$_SERVER['SCRIPT_URI']).$img;
}
$args = array(
'chat_id' => $chatID,
'video' => $img,
'caption' => $cap
);
if($rmf) $args['reply_markup'] = $rm;
$r = new HttpRequest("post", "https://api.telegram.org/$api/sendVideo", $args);




$rr = $r->getResponse();
$ar = json_decode($rr, true);
$ok = $ar["ok"]; //false
$e403 = $ar["error_code"];
if($e403 == "403")
{
return false;
}elseif($e403){
return false;
}else{
return $rr;
}
}

function cb_reply($id, $text, $alert = false, $cbmid = false, $ntext = false, $nmenu = false, $dis = False, $npm = "pred")
{
global $api;
global $chatID;
global $config;

if($npm == 'pred') $npm = $config["formattazione_predefinita"];



$args = array(
'callback_query_id' => $id,
'text' => $text,
'show_alert' => $alert

);
$r = new HttpRequest("get", "https://api.telegram.org/$api/answerCallbackQuery", $args);

if($cbmid)
{
if($nmenu)
{
$rm = array('inline_keyboard' => $nmenu
);
$rm = json_encode($rm);

}


$args = array(
'chat_id' => $chatID,
'message_id' => $cbmid,
'text' => $ntext,
'parse_mode' => $npm,
);
if($nmenu) $args["reply_markup"] = $rm;
if($dis) $args["disable_web_page_preview"] = True;
$r = new HttpRequest("post", "https://api.telegram.org/$api/editMessageText", $args);


}
}

function delete($chatID, $messageID)
{
    global $api;
	$args = array(
		"chat_id" => $chatID,
		"message_id" => $messageID
		);
	new HttpRequest("post", "https://api.telegram.org/$api/deleteMessage", $args);
}

include "argoapi.php";
require "code.php";
require "callbacks.php";