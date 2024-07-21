<?php

class SendMail {

	// A simple POST request for emailing
	// because mail() is not configured in City's Web Server
	public function __construct($to, $from, $subject, $message) {

		// Sending Post request to safat.dev (my website url)
		$url = 'https://safat.dev/ccssb/send_mail.php';

		// Preparing the data to be sent
		$mail_data = array(
			'to' => $to,
			'from' => $from,
			'subject' => $subject,
			'message' => $message
		);

		// The Context
		$context = stream_context_create(array(
			
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($mail_data)
			)

		));

		// Executing
		$result = file_get_contents($url, false, $context);

	}

}

?>