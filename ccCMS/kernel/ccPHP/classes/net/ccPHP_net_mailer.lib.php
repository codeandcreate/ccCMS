<?

class ccPHP_net_mailer extends ccPHP_base {
	
	protected $mailer;
	
	//initialisiert die seite:
    function __construct($fullPackage = false)
    {
    	if (file_exists(CCPHP_BASE_PATH . '/3rdparty/PHPMailer/class.phpmailer.php')) {
			require_once (CCPHP_BASE_PATH . '/3rdparty/PHPMailer/class.phpmailer.php');
			if ($fullPackage) {
				require_once (CCPHP_BASE_PATH . '/3rdparty/PHPMailer/class.phpmaileroauthgoogle.php');
				require_once (CCPHP_BASE_PATH . '/3rdparty/PHPMailer/class.pop3.php');
				require_once (CCPHP_BASE_PATH . '/3rdparty/PHPMailer/class.smtp.php');
				require_once (CCPHP_BASE_PATH . '/3rdparty/PHPMailer/class.phpmaileroauth.php');
			}
    	}
    	$this->mailer = new PHPMailer();
    }
    
	//gibt den standard Emailheader zurück:
	private function _get_header ($title)
	{
		return "<HTML>
					<HEAD>
						<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\"></meta>
						<meta name=\"language\" content=\"de\" />
						<TITLE>".$title."</TITLE>
					</HEAD>
					<BODY>
						<div><b>".$title."</b></div><br><br>";
	}
	
	//gibt den standard Emailfooter zurück:
	private function _get_footer ($mail_viewable_from = "")
	{
		return "<br><br>Mit freundlichem Gru&szlig;<br>".$mail_viewable_from."</BODY></HTML>";
	}

	//gibt den standard Emailfooter zurück:
	public function decrypt_email ($email)
	{
		$return_email_code = "";
		for ($i=0;$i<=strlen($email); $i++) {
			$char_id = ord($email[$i]);
			if( $char_id >= 8364 ) {
				$char_id = 128;
			}
			$return_email_code .= chr($char_id-1);
		}
		return $return_email_code;
	}
	
	//gibt den standard Emailfooter zurück:
	public function encrypt_email ($email)
	{
		$return_email_code = "";
		for ($i=0;$i<=strlen($email); $i++) {
			$char_id = ord($email[$i]);
			if( $char_id >= 8364 ) {
				$char_id = 128;
			}
			$return_email_code .= chr($char_id+1);
		}
		return $return_email_code;
	}
	
	public function send_mail($address_input, $title_input, $body_input, $from_mail, $from_name, $attachment_path = "", $encoded_address = "")
	{	
		if ((!empty($address_input) AND !empty($encoded_address)) AND !empty($title_input) AND !empty($body_input)) {
			
			$this->mailer->From = $from_mail;
			$this->mailer->FromName = $from_name;
		
			$this->mailer->Subject = $from_name.": ".$title_input;
		
		
			$body = $this->_get_header($title_input);
			$body .= $body_input;
			$body .= $this->_get_footer($from_name);
			$this->mailer->MsgHTML($body);
				
			//für nicht-html-emails
			$this->mailer->AltBody = strip_tags(str_replace("<br>", "\r\n", $body));
		
		
			if ($attachment_path != "") {
				$this->mailer->AddAttachment($attachment_path);
			}
		
			if (!empty($encoded_address)) {
				$address_input = $this->decrypt_email($encoded_address);
			}	
			$this->mailer->AddAddress($address_input, "");
		
			if(!$this->mailer->Send()) {
			  $return_code = "Mailer Error: " . $this->mailer->ErrorInfo;
			} else {
			  $return_code = "true";
			}
		} else {
			$return_code = "email data incomplete";
		}
		return $return_code;
	}
}