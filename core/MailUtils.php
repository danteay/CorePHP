<?php 
class MailUtils{
	private $from; // Correo remitente
	private $to; // Correo destinatario
	private $title; // Asunto del email
	private $message; // Texto o cuerpo html del mensaje
	private $is_html; // Valor booleano que indica si es texto plano (false) o texto html (true)
	private $encoding; // Codificacion del mensaje 
	private $extra_headers; // Caveceras extra del mensaje
	private $clean_msg; // Valor booleano que indica si se debe limpiar el mensaje (true) o no (false)
	private $template_keys; // valores para llenado de un template
	private $reply_to; // Email de respuesta


	// Constructor de MailUtils
	public function __construct(){
		$this->from = '';
		$this->to = '';
		$this->title = '';
		$this->message = '';
		$this->is_html = false;
		$this->encoding = 'iso-8859-1';
		$this->extra_headers = '';
		$this->clean_msg = false;
		$this->template_keys = array();
		$this->reply_to = '';
	}


	// Geter's de clase
	public function __get($key){
		return $this->$key;
	}


	// Seter's de clase
	public function __set($key, $value){
		$this->$key = $value;
	}

	
	/*
	/ Funcion:
	/    Limpia y separa el mensaje en renglones de 100 caracteres
	/ Prototipo:
	/    void function CleanupMessage();
	*/
	public function CleanupMessage()	{
		$this->message = wordwrap($this->message, 100, "\r\n");
	}
	

	/*
	/ Funcion: 
	/    Procesa un Template para crear el cuerpo del mensaje
	/    Requiere que antes se inicialize la variable $template_keys
	/ Prototipo:
	/    bool function FromTemplate( String $template );
	/ Variables:
	/    template - Direccion donde se localiza el template a procesar
	*/
	public function FromTemplate($template){
		if (file_exists($template)) {
			$gettemplate = file_get_contents($template);

			foreach ($this->template_keys as $key => $value) {
				$gettemplate = str_replace($key, $value, $gettemplate);
			}

			$this->message = $gettemplate;
			return true;
		}

		return false;
	}


	/*
	/ Funcion:
	/    Envia un email en formato de texto plano
	/ Prototipo:
	/    bool function SendPlain();
	*/
	private function SendPlain(){
		$headers = 'From: '.$this->from."\r\n".
				   'Reply-to: '.$this->reply_to."\r\n".
				   $this->extra_headers;

		return mail($this->to, $this->title, $this->message, $headers);

	}


	/*
	/ Funcion:
	/    Envia un email en formato HTML
	/ Prototipo:
	/    bool function SendHTML();
	*/
	private function SendHTML(){
		$headers = 'From: '.$this->from."\r\n".
				   'Reply-to: '.$this->reply_to."\r\n".
				   'MIME-Version: 1.0' . "\r\n".
				   'Content-type: text/html; charset='.$this->encoding."\r\n".
				   $this->extra_headers;
				   
		return mail($this->to, $this->title, $this->message, $headers);

	}


	/*
	/ Funcion:
	/    Ejecuta el envio de email dependiendo de el tipo de mensaje a enviar
	/ Prototipo:
	/    bool function SendEmail();
	*/
	public function SendEmail(){
		
		if ($this->is_html) {
			//echo "enviado";
			return $this->SendHTML();
		} else {
			//echo "enviado";
			return $this->SendPlain();
		}
		
	}


	/*
	/ Función:  
	/   Comprueba la valides de un Email
	/ Prototipo:
	/   bool function ValidarMail( String $email );
	/ Parametros:
	/   email - Correo a validar
	*/	
	public function ValidarMail($email){
		$mail_correcto = 0;

		if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){

		  if ((!strstr($email,"'")) && (!strstr($email,'/')) && (!strstr($email,"\$")) && (!strstr($email," "))){
			  if (substr_count($email,".")>= 1){
				$term_dom = substr(strrchr ($email, '.'),1);

				if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){

					$antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
					$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
					if ($caracter_ult != "@" && $caracter_ult != "."){
					  $mail_correcto = 1;
					}
				}
			  }
		  }
		}

		if ($mail_correcto){
			return true;
		}else{
			return false;
		}

	}
	
	public function __destruct(){
	}
	
}