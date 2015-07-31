<?php 

class CriptSeg{

    // Hash salt para encriptacion, (recomendable modificar)
    const HASHCRYPT = "@#hJSD*&as@#$%&/&%$%&asdEFGThd";

    /*
    / Funcion:
    /     Encripta una cadena de texto en formato MD5.
    / Prototipo:
    /     String function crypStringMD5( String $cadena );
    / Parametros:
    /     cadena - texto al cual se le realizara la encriptacion
    */
	public function crypStringMD5($cadena){
		$pass = hash('md5',$cadena);
		return $pass;
	}


    /*
    / Funcion:
    /     Encripta una cadena de texto en formato SHA512.
    / Prototipo:
    /     String function crypStringSHA512( String $cadena );
    / Parametros:
    /     cadena - texto al cual se le realizara la encriptacion
    */
	public function crypStringSHA512($cadena){
		$pass = hash('sha512',$cadena);
		return $pass;
	}


    /*
    / Funcion:
    /     Encripta una cadena de texto en formato SHA256.
    / Prototipo:
    /     String function crypStringSHA256( String $cadena );
    / Parametros:
    /     cadena - texto al cual se le realizara la encriptacion
    */
	public function crypStringSHA256($cadena){
		$pass = hash('sha256', $cadena);
		return $pass;
	}


    /*
    / Funcion:
    /     Genera una doble encriptacion a una dena, promero en formato MD5 y posteriormente SHA512
    / Prototipo:
    /     String function crypStringSHA512( String $cadena );
    / Parametros:
    /     cadena - texto al cual se le realizara la encriptacion
    */
	public function doubleCrypString($cadena){
		$salida = $this->CrypStringSHA512($this->CrypStringMD5($cadena));
		return $salida;
	}


    /*
    / Funcion:
    /     Convierte una imagen en una cadena de texto base64 la cual puede ser almacena en base de datos.
    / Prototipo:
    /     String function crypStringSHA512( String $cadena );
    / Parametros:
    /     path - direccion completa de la imagen la caul se encriptara
    */
	public function imageToBase64($path){
		$imagen = file_get_contents($path);
		return base64_encode($imagen);
	}


    /*
    / Funcion:
    /     Encripta una cadena de texto en formato base64.
    / Prototipo:
    /     String function crypStringSHA512( String $cadena );
    / Parametros:
    /     cadena - texto al cual se le realizara la encriptacion
    */
	public function stringToBase64($cadena){
		$pass = base64_encode($cadena);
		return $pass;
	}


    /*
    / Funcion:
    /     Encripta una cadena de texto en formato salt con el hash especificado el inicio de la clase.
    / Prototipo:
    /     String function crypStringSHA512( String $cadena );
    / Parametros:
    /     cadena - texto al cual se le realizara la encriptacion
    */
    public function saltCrypt($cadena){
        $pass = crypt($cadena,self::HASHCRYPT);
        return $pass;
    }

}
