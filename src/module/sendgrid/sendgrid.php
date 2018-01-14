<?php

// link mail function
msv_set_config("mailer", "EmailSendgrid");

/**
 * Send Email using Sendgrid.com service
 *
 * checks for required fields and correct values
 * config->sendgrid_user is required
 * config->sendgrid_password is required
 * config->email_from is required
 * config->email_fromname is required
 * 
 * @param string $to Send to this email address
 * @param string $subj Email Subject
 * @param string $body Email HTML Body text
 * @return boolean Result of an action
 */
function EmailSendgrid($to, $subj, $body) {
	$sendgridUser = msv_get_config("sendgrid_user");
	$sendgridPassword = msv_get_config("sendgrid_password");
	$sendgridFrom = msv_get_config("email_from");
	$sendgridFromName = msv_get_config("email_fromname");

	if (empty($sendgridUser)) {
		return false;
	}
	if (empty($sendgridPassword)) {
		return false;
	}
	if (empty($sendgridFrom)) {
		return false;
	}
	if (empty($sendgridFromName)) {
		$sendgridFromName = "";
	}

	$sendgridUrl = 'https://api.sendgrid.com/';
	
	$params = array(
		'api_user' => $sendgridUser,
		'api_key' => $sendgridPassword,
		'to' => $to,
		'subject' => $subj,
		'html' => $body,
		'from' => $sendgridFrom,
		'fromname' => $sendgridFromName,
	);
	
	$request = $sendgridUrl.'api/mail.send.json';
	$session = curl_init($request);
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($session);
	curl_close($session);

	return $response;
}

/**
 * Install hook for module sendgrid
 * This function is executed upon installation
 *
 * @param object $module Current Module object
 * @return null
 */
function SendgridInstall($module) {
	
	// Set Sendgrid options
    msv_set_config("sendgrid_user", "", true, "*", "Username to connect to api.sendgrid.com", "website");
    msv_set_config("sendgrid_password", "", true, "*", "Password to connect to api.sendgrid.com", "website");
	
}
