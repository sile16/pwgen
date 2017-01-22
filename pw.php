<?php
require_once "mersenne_twister.php";
use mersenne_twister\twister;



function randomPassword($length,$alphabet,$delim) {
    $twister = new twister(289324987+intval($_GET['num'])*2);
    $pass = "";
    for ($i = 0; $i < $length; $i++) {
        $n = $twister->rangeint(0, count($alphabet)-1);
        $pass .= $alphabet[$n] . $delim;
    }
    return $pass;
}


if(isset($_GET['num']) && is_numeric($_GET['num']) ){
    $num = $_GET['num'];
}else{
    http_response_code(500);
    echo "invalid num param";
}

$type = $_GET['type'];
$length = $_GET['len'];





$word_file='./diceware.wordlist';

//Assume Diceware
$alpha = array();
$word_file = './diceware.wordlist';
$delim=" ";
switch($type){
	case "bip39":
		$word_file="./english-bip39.txt";
	case "diceware":
		//Read in wordlist for either bip38 or diceware
		foreach(file($word_file) as $val) 
		{ 
			$alpha[] = trim(preg_replace('/\s\s+/', ' ', $val));
		}
		break;
	case "pw70":
		$alpha = str_split("abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*");
		$delim="";
		break;
	default:
		print('ERROR Unknown type');
	}


$pass = randomPassword($length,$alpha,$delim);
if(isset($_GET['text'])){

  echo $pass;
}
else{

header('Content-type: image/png');

// Create the image
$w=800;
$h=50;
$im = imagecreatetruecolor($w, $h);

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, $w-1, $h-1, $white);

// The text to draw

// Replace path by your own font path
$font = 'ARIAL.TTF';

// Add some shadow to the text
//imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

// Add the text
imagettftext($im, 16, 0, 10 , $h-10 , $black, $font, $pass);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im);
imagedestroy($im);
}

?> 
