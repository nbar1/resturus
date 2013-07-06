<?php
class brdi_Api_SendMail extends brdi_Api
{
	private $mail_to;
	private $mail_from;
	private $mail_subject;
	private $mail_body;
	private $mail_wrapper;
	
	function __construct($mail_to, $mail_from, $mail_subject, $mail_body, $mail_wrapper = false)
	{
		$this->mail_to = $mail_to;
		$this->mail_from = $mail_from;
		$this->mail_subject = $mail_subject;
		$this->mail_body = $mail_body;
		$this->mail_wrapper = $mail_wrapper;
	}

	public function sendMail()
	{
		// check if all information is provided
		if(!isset($this->mail_to)||!isset($this->mail_from)||!isset($this->mail_subject)||!isset($this->mail_body)) return false;
		// check if send address validates
		if($this->validateEmail($this->mail_to) === false) return false;

		
		$this->mail_body = $this->wrapBody($this->mail_body);

		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: ' . $this->mail_from . "\r\n";
		
		$body = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>'.$this->mail_subject.'</title></head><body>';
		$body .= $this->mail_body;
		$body .= '</body></html>';
		
		var_dump(mail($this->mail_to, $this->mail_subject, $this->mail_body, $headers));

		if(mail($this->mail_to, $this->mail_subject, $this->mail_body, $headers))
		{
			return true;
		}
		return false;
	}
	
	private function wrapBody($mail_body)
	{	
		if($this->mail_wrapper !== false)
		{
			$template = $this->getTemplate($this->mail_wrapper);
			if($template !== false)
			{
				$template = str_replace("!{replace://email_body/}", $mail_body, $template);
				return $template;
			}
		}
		return $mail_body;
	}
	
	private function validateEmail($email)
	{
		// First, we check that there's one @ symbol, 
		// and that the lengths are right.
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
		{
		// Email invalid because wrong number of characters 
		// in one section or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++)
		{
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i]))
			{
				return false;
			}
		}
		// Check if domain is IP. If not, 
		// it should be valid domain name
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
		{
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2)
			{
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++)
			{
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",	$domain_array[$i]))
				{
					return false;
				}
			}
		}
		return true;
	}
}
?>