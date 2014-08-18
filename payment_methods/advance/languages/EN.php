<?php

/*
  Module developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org)
  Copyright (C) 2007 - 2013, Christoph Marti

  LICENCE TERMS:
  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  DISCLAIMER:
  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/


// PAYMENT METHOD ADVANCE PAYMENT
// ******************************

// SETTINGS - USED BY BACKEND


// USED BY FILE bakery/payment_methods/advance/gateway.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'Advance payment';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'Please pay the balance due to our bank account in advance.';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'I will pay in advance';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Le enviaremos una confirmaci&oacute;n de pedido con la informaci&oacute;n de pago requerida.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Tan pronto como recibamos su pago enviaremos la orden a usted.';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'La confirmaci&oacute;n y la factura de su pedido en [SHOP_NAME]';

//As soon as we receive your payment

$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Estimado (a): [CUSTOMER_NAME]

Su Cotizaci&oacute;n en: [SHOP_NAME].
A continuación la información acerca de los productos que ha cotizado:
[ITEM_LIST]

Please pay the balance due in advance to our bank account
[BANK_ACCOUNT]

Vamos a enviar la cotizaci&oacute;n al email de abajo:

[TXT_CUST_EMAIL]


Gracias por la confianza que han depositado en nosotros.

VENTAS: [SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nuevo orden en [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Estimado(a): [SHOP_NAME] Administrador

NEW ORDER AT [SHOP_NAME]:
	Order #: [ORDER_ID]
	M&eacute;todo de pago: Anticipo

Direcci&oacute;n de env&iacute;o:
[TXT_CUST_EMAIL]

Lista de los art&iacute;culos pedidos:
[ITEM_LIST]


Mensaje de los clientes:
[CUST_MSG]


VENTAS: [SHOP_NAME]


';



// If iso-8859-1 is set as WB default charset convert some utf-8 strings to iso-8859-1
if (defined('DEFAULT_CHARSET') && DEFAULT_CHARSET == 'iso-8859-1') {
	$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = utf8_decode($MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER']);
	$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = utf8_decode($MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER']);
	$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = utf8_decode($MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP']);
	$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = utf8_decode($MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP']);
}
