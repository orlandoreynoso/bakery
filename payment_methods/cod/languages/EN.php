<?php

/*
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (C) 2009, Christoph Marti

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


// PAYMENT METHOD CASH ON DELIVERY PAYMENT
// ***************************************

// SETTINGS - USED BY BACKEND
$MOD_BAKERY[$payment_method]['TXT_CHARGES'] = 'CoD Charges<br />(without currency code)';

// USED BY FILE bakery/payment_methods/cod/gateway.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'Cash on Delivery';
$MOD_BAKERY[$payment_method]['TXT_PAY_CASH_ON_DELIVERY'] = 'Pay cash on delivery.';
$MOD_BAKERY[$payment_method]['TXT_ADDITIONAL_CHARGES_1'] = 'Please note additional <b>CoD charges in the amount of ';
$MOD_BAKERY[$payment_method]['TXT_ADDITIONAL_CHARGES_2'] = '</b> to be collected.';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'I will pay cash on delivery';

// USED BY FILE bakery/view.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Gracias por su comunicación, le enviaremos su cotización tan pronto sea posible.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Enviaremos su cotizaci&oacute;n tan pronto como sea posible.';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'La confirmación de su cotizaci&oacute;n en [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Sr. (ra) [CUSTOMER_NAME]

Gracias por su Cotizaci&oacute;n en [SHOP_NAME].
A continuación la información acerca de los productos que ha cotizado:
[ITEM_LIST]

Vamos a enviar la cotizaci&oacute;n al email de abajo:

[TXT_CUST_EMAIL]

Gracias por la confianza que han depositado en nosotros.

VENTAS: [SHOP_NAME]


';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'New order at [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Dear [SHOP_NAME] Administrator

NEW ORDER AT [SHOP_NAME]:
	Order #: [ORDER_ID]
	Payment method: Cash on Delivery

Shipping address:
[TXT_CUST_EMAIL]

Invoice address:
[CUST_ADDRESS]

List of ordered items: 
[ITEM_LIST]


Customers message:
[CUST_MSG]


Kind regards,
[SHOP_NAME]


';



// If utf-8 is set as default charset convert some iso-8859-1 strings to utf-8 
if (defined('DEFAULT_CHARSET') && DEFAULT_CHARSET == 'utf-8') {
	$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = utf8_encode($MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER']);
	$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = utf8_encode($MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER']);
	$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = utf8_encode($MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP']);
	$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = utf8_encode($MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP']);
}
