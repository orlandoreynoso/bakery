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


// PAYMENT METHOD INVOICE
// **********************

// SETTINGS - USED BY BACKEND
$MOD_BAKERY[$payment_method]['TXT_BANK_ACCOUNT'] = 'Cuenta bancaria de la Tienda';
$MOD_BAKERY[$payment_method]['TXT_INVOICE_TEMPLATE'] = 'Plantilla de la Factura';
$MOD_BAKERY[$payment_method]['TXT_INVOICE_ALERT'] = '1. Recordatorio despu&eacute;s de Alerta';
$MOD_BAKERY[$payment_method]['TXT_REMINDER_ALERT'] = '2. Recordatorio despu&eacute;s de Alerta';

// USED BY FILE bakery/payment_methods/invoice/gateway.php
$MOD_BAKERY[$payment_method]['TXT_TITLE'] = 'Cotizaci&oacute;n';
$MOD_BAKERY[$payment_method]['TXT_ACCOUNT'] = 'Por favor, pagar el saldo adeudado a nuestra cuenta bancaria de acuerdo con el plazo de pago.';
// $MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Agregar a mi cotizaci&oacute;n';
$MOD_BAKERY[$payment_method]['TXT_PAY'] = 'Continuar';

// USED BY FILE bakery/view_confirmation.php
$MOD_BAKERY[$payment_method]['TXT_SUCCESS'] = 'Le enviaremos una confirmaci&oacute;n de su cotizaci&oacute;n.';
$MOD_BAKERY[$payment_method]['TXT_SHIPMENT'] = 'Enviaremos su cotizaci&oacute;n tan pronto como sea posible.';

// INVOICE TEMPLATE
$MOD_BAKERY[$payment_method]['INVOICE_TEMPLATE'] = '<img src="[WB_URL]/modules/bakery/images/logo.gif" width="690" height="75" alt="[SHOP_NAME] Logo" class="mod_bakery_logo_b" />
<br />
<p class="mod_bakery_shop_address_b">[SHOP_NAME] | Company | No Street | City ZIP | COUNTRY</p>
<br /><br /><br />
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_INVOICE]">[CUST_ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_DELIVERY_NOTE]">[ADDRESS]</p>
<p class="mod_bakery_cust_address_b" style="display: [DISPLAY_REMINDER]">[CUST_ADDRESS]</p>
<br /><br /><br /><br /><br /><br />
<h2>[TITLE]</h2>
<table class="mod_bakery_invoice_no_b" cellspacing="0" cellpadding="0">
<tr>
<td align="right">Date:</td>
<td>[CURRENT_DATE]</td>
</tr>
<tr>
<td align="right">Invoice-No:</td>
<td>[INVOICE_ID]</td>
</tr>
<tr>
<td align="right">Order:</td>
<td>[ORDER_ID] | [ORDER_DATE]</td>
</tr>
<tr>
<td align="right">Your VAT-No:</td>
<td>[CUST_TAX_NO]</td>
</tr>
</table>
<br />
[ITEM_LIST]
<br /><br /><br />

<div style="display: [DISPLAY_INVOICE]">
<p class="mod_bakery_thank_you_b">Gracias por su Cotizaci&oacute;n en [SHOP_NAME].</p>
<p class="mod_bakery_pay_invoice_b">Please pay the balance due within 30 days into account below:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<div style="display: [DISPLAY_DELIVERY_NOTE]">
<p class="mod_bakery_thank_you_b">Gracias por su Cotizaci&oacute;n en [SHOP_NAME].</p>
</div>

<div style="display: [DISPLAY_REMINDER]">
<p class="mod_bakery_pay_invoice_b">Please disregard this letter if you have already made payment. Otherwise please pay the balance due within 10 days into account below:</p>
<p class="mod_bakery_bank_account_b">[BANK_ACCOUNT]</p>
</div>

<br /><br />';

// EMAIL CUSTOMER
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = 'La confirmaci&oacute;n de su cotizaci&oacute;n en: [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = 'Estimado (a): [CUSTOMER_NAME]

Su Cotizaci&oacute;n en: [SHOP_NAME].
Informaci&oacute;n de los productos que ha cotizado:
[ITEM_LIST]

Vamos a enviar la cotizaci&oacute;n al E-mail de abajo:
[TXT_CUST_EMAIL]

Gracias por la confianza que han depositado en nosotros.
VENTAS: [SHOP_NAME]

';

// EMAIL SHOP
$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = 'Nueva Cotizaci&oacute;n en: [SHOP_NAME]';
$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = 'Estimado (a): [SHOP_NAME] Administrador

NUEVA COTIZACI&Oacute;N EN:  [SHOP_NAME]:
	Orden #: [ORDER_ID]

Direcci&oacute;n:
[ADDRESS]

Lista de art&iacute;culos:
[ITEM_LIST]

Mensaje del cliente:
[CUST_MSG]

VENTAS: SHOP_NAME]
';



// If iso-8859-1 is set as WB default charset convert some utf-8 strings to iso-8859-1
if (defined('DEFAULT_CHARSET') && DEFAULT_CHARSET == 'iso-8859-1') {
	$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER'] = utf8_decode($MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_CUSTOMER']);
	$MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER'] = utf8_decode($MOD_BAKERY[$payment_method]['EMAIL_BODY_CUSTOMER']);
	$MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP'] = utf8_decode($MOD_BAKERY[$payment_method]['EMAIL_SUBJECT_SHOP']);
	$MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP'] = utf8_decode($MOD_BAKERY[$payment_method]['EMAIL_BODY_SHOP']);
}
