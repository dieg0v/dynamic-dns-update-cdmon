<?php

/**
 *
 * Simple script for dynamic dns update for CDMON service
 *
 * More info:
 * https://support.cdmon.com/entries/24118056-API-de-actualización-de-IP-del-DNS-gratis-dinámico
 *
 * MIT License
 * ===========
 *
 * Copyright (c) 2013 Diego Vilariño <diego.vilarino@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

// ==================================================================
//
// Mail log
//
// To use mail log: run "composer install" ( http://getcomposer.org/ )
// uncomment next "require" line, set " $mail_log = true; " and config smtp settings
// ------------------------------------------------------------------

// require 'vendor/autoload.php';
$mail_log = false;

// ==================================================================
//
// Config
//
// ------------------------------------------------------------------

/**
 * Script options
 */
$user = 'yourUsername'; //username
$pass = 'yourPassword'; //password
$cip = false; // force new ip here or set to false to auto

$retry_time = 30; // time to retry, on seconds
$retry_attempts = 3; // number of attempts

$enctype = 'MD5'; // encryption
$url = 'https://dinamico.cdmon.org/onlineService.php?'; //service url
/**
 * Mail options
 */
$to  = 'to@example.com';
$subject = 'Dinamic dns status';
$from = 'you@example.com';
$mail_log_success = true;
$mail_log_fail = true;
/**
 * smtp config
 */
$mail_config = array();
$mail_config['smtp'] = 'smtp.yourdomain.com';
$mail_config['port'] = 25;
$mail_config['username'] = 'smtpUser';
$mail_config['password'] = 'smtpPassword';

// ==================================================================
//
// MailLog function
//
// ------------------------------------------------------------------

function MailLog ($message, $subject, $from, $to , $mail_config){

	$transport = Swift_SmtpTransport::newInstance($mail_config['smtp'], $mail_config['port'])
	->setUsername($mail_config['username'])
	->setPassword($mail_config['password']);

	$mailer = Swift_Mailer::newInstance($transport);

	// Create a message
	$message = Swift_Message::newInstance($subject)
	->setFrom($from)
	->setTo($to)
	->setBody($message);

	$result = $mailer->send($message);

}

// ==================================================================
//
// Script
//
// ------------------------------------------------------------------

$pass = md5($pass);
$url = $url . 'enctype='.$enctype.'&n='.$user.'&p='.$pass;
if($cip!==false){
	$url .= '&cip='.$cip;
}

$result = false;
$retry = 0;

while(!$result){

	$result = file_get_contents($url);

	if($result){
		$path = explode('&', $result);
		foreach ($path as $key) {
			if( $key=="resultat=guardatok" || $key=="resultat=customok"){
				if($mail_log && $mail_log_success){
					MailLog ("UPDATE OK. ".date('d/m/Y H:i:s').": ".$result , $subject, $from, $to , $mail_config);
				}
				exit(1);
			}
		}
	}

	$retry++;
	if($mail_log && $mail_log_fail){
		MailLog ("UPDATE FAIL. Attempt(".$retry.") - ".date('d/m/Y H:i:s').": ".$result , $subject, $from, $to , $mail_config);
	}
	$result = false;
	if($retry >= $retry_attempts){
		exit(1);
	}
	sleep($retry_time);

}

?>