<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/30
 * Time: 1:39
 */

class TimeTest
{

}

//1 GET UTC0 DATE AND NEXT DATE + 5
$loginTime = new DateTime('2018-01-30 23:00:00',new DateTimeZone('JST')); //JST
//$loginTime->setTimezone(new DateTimeZone('JST'));
$currentHour = (int) $loginTime->format('H');
$modifyDate = new DateTime($loginTime->format(DateTime::ISO8601));
if($currentHour >= 14 ){
	$modifyDate->modify('+1 day');
}

$borderTime = new DateTime($modifyDate->format('Y-m-d 14:00:00'));

$localTimeDiff = 13;
$localTimeDiff = round($localTimeDiff);

//First date for sending notification
//Send
if( $localTimeDiff > -7 && $localTimeDiff <= 8){
	$intervalHour = 7 + $localTimeDiff;
}else if($localTimeDiff <= -7){
	$intervalHour = 13 + $localTimeDiff;
}else if($localTimeDiff > 8 && $localTimeDiff < 12){
	$intervalHour =  $localTimeDiff - 8;
}else if($localTimeDiff >= 12){
	$intervalHour = $localTimeDiff - 11;
}
$firstNotificationTime = new DateTime($borderTime->format(DateTime::ISO8601));
$firstNotificationTime->modify("+{$intervalHour} Hour");


$secondNotificationTime = new DateTime($firstNotificationTime->format(DateTime::ISO8601));
if($localTimeDiff <= -7){
	$secondNotificationTime->modify("+3 Hour");
}else if($localTimeDiff >= -6 && $localTimeDiff <= 8){
	$secondNotificationTime->modify("+6 Hour");
}else if($localTimeDiff >= 9 && $localTimeDiff <= 11){
	$secondNotificationTime->modify("+15 Hour");
}else if($localTimeDiff >= 12){
	$secondNotificationTime->modify("+3 Hour");
}

$thirdNotificationTime = new DateTime($secondNotificationTime->format(DateTime::ISO8601));


//Exception first.
//最終通知変更値
//境界値 : -7 : //12時 -> 11:00
//境界値 : 8  : //21時 -> 20:00
//境界値 : 11 : //18時 -> 17:00

if($localTimeDiff == -7){
	$thirdNotificationTime->modify("+14 Hour");
}else if ($localTimeDiff == 8){
	$thirdNotificationTime->modify("+2 Hour");
}else if ($localTimeDiff == 11){
	$thirdNotificationTime->modify("+5 Hour");
}else

	if($localTimeDiff < -7){
	$thirdNotificationTime->modify("+15 Hour");
}else if($localTimeDiff >= -6 && $localTimeDiff < 8){
	$thirdNotificationTime->modify("+3 Hour");
}else if($localTimeDiff >= 9 && $localTimeDiff <= 11){
	$thirdNotificationTime->modify("+6 Hour");
}else if($localTimeDiff >= 12){
	$thirdNotificationTime->modify("+15 Hour");
}





//初期通知変更値
//境界値 : -7 : +6 //12時 -> 18時
//境界値 : 8  : +15 //21時 -> 翌日の12:00
//境界値 : 11 : +3 //18時 -> 21時に変更

//最終通知変更値
//境界値 : -7 : //12時 -> 11:00
//境界値 : 8  : //21時 -> 20:00
//境界値 : 11 : //18時 -> 17:00

//print_r("LOCAL TIME DIFF {$localTimeDiff}");
//echo "\r\n";
//print_r("TIME INTERVAL {$intervalHour}" );
//echo "\r\n";
//print_r("User Login  : ");
//print_r($loginTime->format('Y-m-d H:i:s'));
//echo "\r\n";
//print_r("border time : ");
//print_r($borderTime->format('Y-m-d H:i:s'));
echo "\r\n";
print_r("First Date  : ");
print_r($firstNotificationTime->format('Y-m-d H:i:s'));
echo "\r\n";
print_r("Second Date : ");
print_r($secondNotificationTime->format('Y-m-d H:i:s'));
echo "\r\n";
print_r("Third  Date : ");
print_r($thirdNotificationTime->format('Y-m-d H:i:s'));

