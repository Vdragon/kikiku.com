<?php
// 通过新浪api查找城市
// http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=218.192.3.42
$img_base = 'http://www.google.com';

$ip = $_SERVER['REMOTE_ADDR'];

// 通过谷歌api查看天气
$city_info = json_decode(file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip));

//$xml_api_reply = simplexml_load_string(file_get_contents('http://www.google.com/ig/api?weather='.$city_info->city));

// 显示语言请修改这里的参数,删除则一同删除字符转换函数
$xml_api_reply = simplexml_load_string(mb_convert_encoding(file_get_contents('http://www.google.com/ig/api?hl=zh-cn&weather=成都'), 'UTF-8', 'GB2312'));

$forecast = array('city'=>$city_info->city,'today'=>array(),'second'=>array(),'third'=>array());

/* today */
$xml_element = $xml_api_reply->weather->current_conditions;
$forecast['today'] = array('condition'=>$xml_element->condition->attributes()->data.'',
	'temper'=>$xml_element->temp_c->attributes()->data.'',
	'img'=>$img_base.$xml_element->icon->attributes()->data,
	'humidity'=>$xml_element->humidity->attributes()->data.'',
	'wind'=>$xml_element->wind_condition->attributes()->data.'');

/* second */
$xml_element = $xml_api_reply->weather->forecast_conditions[1];
$forecast['second'] = array('condition'=>$xml_element->condition->attributes()->data.'',
	'temper'=>$xml_element->low->attributes()->data.'~'.$xml_element->high->attributes()->data,
	'img'=>$img_base.$xml_element->icon->attributes()->data.'');

/* third */
$xml_element = $xml_api_reply->weather->forecast_conditions[2];
$forecast['third'] = array('condition'=>$xml_element->condition->attributes()->data.'',
	'temper'=>$xml_element->low->attributes()->data.'~'.$xml_element->high->attributes()->data,
	'img'=>$img_base.$xml_element->icon->attributes()->data.'');

echo(json_encode($forecast));
?>