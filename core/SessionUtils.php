<?php  

class SessionUtils{

    private $session; // variable de manejo de session


    /*
     * Constructor de clase
     */
	public function __construct(){
		session_start();
        $this->session = $_SESSION;
	}


    /*
     * Geter de clase
     */
	public function __get($dato){
		return $this->session[$dato];
	}


    /*
     * Seter de clase
     */
	public function __set($dato,$valor){
		if($dato != ''){
			$_SESSION[$dato] = $valor;
            $this->session = $_SESSION;
		}
	}


    /*
     * Funcion:
     *     Cierra la seccion y borra los valores almacenados en ella
     * Prototipo:
     *     void function closeSession();
     * Parametros:
     *     sin parametros
     */
	public function closeSession(){
		$this->session = null;
        $_SESSION = null;
        session_destroy();
	}

	public function __destruct(){

	}
}