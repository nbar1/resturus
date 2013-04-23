<?php
class brdi_Exception extends Exception
{
	protected $_error_codes = array(
		0 => "Undefined Error",
		/* Database */
		100 => "General Database Error",
		101 => "Insert Database Error",
		102 => "Update Database Error",
		103 => "Select Database Error",
		/* Assets */
		300 => "Asset Not Found",
		301 => "Config Override Not Found",
		302 => "Component Template Not Found",
		303 => "Page Template Not Found",
		304 => "Wrapper Template Not Found",
		305 => "Javascript Not Found",
		306 => "Stylesheet Not Found",
		307 => "Token Not Found",
		/* Component */
		400 => "Error Loading Component",
		401 => "Error Loading Component Config",
		/* Page */
		400 => "Error Loading Page",
		401 => "Error Loading Page Config",
		
		/* Custom Components */
		/* Twitter */
		20100 => "Error: brdi_Portal_Component_Twitter"
	);
	
	public function logError()
	{
		$error = array(
			'err_code' => $this->getCode(),
			'err_type' => $this->_error_codes[$this->getCode()],
			'err_info' => $this->getMessage(),
			'err_scope' => print_r($this->getTrace(), true)."\n\n".print_r($_SERVER,true),
			'err_time' => date("Y-m-d H:i:s"),
		);
		
		$this->storeInDatabase($error);
		$this->outputError();
	}
	
	private function storeInDatabase($error)
	{
		global $db;
		$error['err_info'] = print_r($error['err_info'], true);
		$dbh = $db->prepare("INSERT INTO errors (err_code, err_type, err_info, err_scope, err_time) VALUES (?, ?, ?, ?, ?)");
		$dbh->execute(array($error['err_code'], $error['err_type'], $error['err_info'], $error['err_scope'], $error['err_time']));
		return true;
	}
	
	private function outputError()
	{
		echo 'Error on line '.$this->getLine().' in '.$this->getFile() .': <b>'.$this->getMessage().'</b>';
	}
}
?>