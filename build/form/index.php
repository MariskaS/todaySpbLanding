<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


#Config
$mailto='alex@2347.ru, yulia@seospb.com, profosnova.spb@yandex.ru';
header('Content-Type: application/json');

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
//var_dump($data);

$subject="Новая заявка с сайта";


//Тема сообщения в зависимости от формы
if($data["formtype"] == 'callback'){
	$subject="[s.profosnova]Заказ обратного звонка";
}else if($data["formtype"] == 'contactForm'){
	$subject="[s.profosnova]Заявка на замерщика";
}else if($data["formtype"] == 'orderForm'){
	$subject="[s.profosnova]Заявка на расчет";
}




//Тип объекта
$objectType="";
if(isset($data['objectType'])){
	switch ($data['objectType']) {
	    case 'option1':
	        $objectType="Квартира";
	        break;
	    case 'option2':
	        $objectType="Коттедж";
	        break;
	    case 'option3':
	        $objectType="Нежилое помещение";
	        break;
	}
}



// Компонуем сообщение
$message = <<<MSG
<h1>Новая заявка с сайта</h1>
<b>Номер телефона:</b> {$data['phone']}<br>
MSG;


// Если это заявка на вызов замерщика
if($data["formtype"] == 'contactForm'){
$message .= <<<MSG
<b>Контактное лицо: </b> {$data['name']} <br>
<b>Адрес объекта: </b> {$data['address']} <br>
MSG;
}




//Если это заявка на расчет
if($data["formtype"] == 'orderForm'){
$message .= <<<MSG
<b>Контактное лицо: </b> {$data['name']} <br><br>
<b>Тип объекта: </b> {$objectType} ({$data['objectType']}) <br>
<b>Площадь стен: </b>{$data['walls']} <br>
<b>Площадь потолка: </b> {$data['ceiling']} <br>
<b>Откосы:</b> {$data['slopes']}
MSG;
}


$headers = 'Content-type: text/html; charset=utf-8' . "\r\n" .
			'From: Profosnova<leads@profosnova.ru>' . "\r\n";


mail($mailto, $subject, $message, $headers);
echo '{"status": "ok", "message": "Ваша заявка принята. В ближайшее время мы свяжемся с Вами!"}';

?>
