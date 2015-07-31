<?php

/*
 * Este archivo no fue desarrollado propiamente para The Core.
 * Este archivo fue recopilado de una serie de plugins precreados, corregido e implementado.
 * Fuente: PHPClases.com
 */

class PasswordGenerator {

    /*
     * arrego de inicializacion para generar la cadena aleatorea
     */
	private $args = array(
			'lenght'				=>	8,      // Tamaño de la cadena resultante
			'alpha_upper_include'	=>	TRUE,   // Incluir letras mayusculas
			'alpha_lower_include'	=>	TRUE,	// Incluir letras minusculas
			'number_include'		=>	TRUE,   // Incluir numeros
			'symbol_include'		=>	TRUE,	// Incluir caracteres especiales
		);

    // Arreglo de letras mayusculas permitidas
	private $alpha_upper = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" );

    // Arreglo de letras minusculas permitidas
	private $alpha_lower = array( "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z" );

    // Arreglo de numeros permitidos
	private $number = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );

    // Arreglo de caracteres especiales permitidos
	private $symbol = array( "-", "_", "^", "~", "@", "&", "|", "=", "+", ";", "!", ",", "(", ")", "{", "}", "[", "]", ".", "?", "%", "*", "#" );



    /*
     * Constructor de clase
     * Parametros:
     *     args - arreglo de inicializacion el cual si no es pasado como parametro se tomara por defecto el que este inicializado con
     *            los valores de la clase
     */
	public function __construct( $args = array() ) {
		$this->set_args( $args );
	}


    /*
     *
     */
    public function __get($data){
        return $this->$data;
    }


    /*
     * Funcion:
     *     Compara el arreglo de opciones por defecto con el que se pasa al constructor de la clase
     *     y reemplaza por los nuevos valores establecidos
     * Prototipo:
     *     array function chip_parse_args( array $args, array $defautls);
     * Parametros:
     *     args - Arreglo pasado al constructor
     *     defaults - Arreglo con las configuraciones por defecto
     */
	private function chip_parse_args( $args = array(), $defaults = array() ) { 
		return array_merge( $defaults, $args );	 
	}
	

    /*
     * Funcion:
     *     Configura los parametros pasados en el arreglo principal
     * Prototipo:
     *     void function set_args( array $args);
     * Parametros:
     *     args - Arreglo pasado en el constructor de la clase
     */
	private function set_args( $args = array() ) {
		$defaults = $this->args;
		$args = $this->chip_parse_args( $args, $defaults );
		$this->args = $args;	 
	}


    private function get_args(){
        return $this->args;
    }

    private function get_alpha_upper(){
        return $this->alpha_upper;
    }

    private function get_alpha_lower(){
        return $this->alpha_lower;
    }

    private function get_number(){
        return $this->number;
    }

    private function get_symbol(){
        return $this->symbol;
    }


    /*
     * Funcion:
     *     Genera la cadena de caracteres aleatorios segun la configuracion establecida
     * Prototipo:
     *     String function set_password();
     * Parametros:
     *     sin parametros
     */
	private function set_password() { 
		
		/* Temporary Array(s) */
		$temp = array();
		$exec = array();
		
		/* Arguments */
		$args = $this->get_args();	 
		extract($args);
		
		/* Minimum Validation */		
		if( $lenght <= 0 ) {
			return 0;
		}
		
		/* Execution Array Logic */
		
		/* Alpha Upper */
		if( $alpha_upper_include == TRUE ) {
			$alpha_upper = $this->get_alpha_upper();
			$exec[] = 1;
		}
		
		/* Alpha Lower */
		if( $alpha_lower_include == TRUE ) {
			$alpha_lower = $this->get_alpha_lower();
			$exec[] = 2;
		}
		
		/* Number */
		if( $number_include == TRUE ) {
			$number = $this->get_number();
			$exec[] = 3;
		}
		
		/* Symbol */
		if( $symbol_include == TRUE ) {
			$symbol = $this->get_symbol();
			$exec[] = 4;
		}
		
		/* Unique and Random Loop */
		$exec_count = count( $exec ) - 1;
		$input_index = 0;
		//$this->chip_print( $exec );
		
		for ( $i = 1; $i <= $lenght; $i++ ) {
			
			switch( $exec[$input_index] ) {
				
				case 1:				
				shuffle( $alpha_upper );
				$temp[] = $alpha_upper[0];
				unset( $alpha_upper[0] );				
				break;
				
				case 2:				
				shuffle( $alpha_lower );
				$temp[] = $alpha_lower[0];
				unset( $alpha_lower[0] );				
				break;
				
				case 3:				
				shuffle( $number );
				$temp[] = $number[0];
				unset( $number[0] );				
				break;
				
				case 4:				
				shuffle( $symbol );
				$temp[] = $symbol[0];
				unset( $symbol[0] );				
				break;
				
			}
			
			if ( $input_index < $exec_count ) {
				$input_index++;
			} else {
				$input_index = 0;
			}
		
		} // for ( $i = 1; $i <= $lenght; $i++ )
		
		/* Shuffle */
		shuffle($temp);
		
		/* Make Password */		
		$password = implode( $temp );
		
		return $password;
		
	}


    /*
     * Funcion:
     *     Regresa la cadena aleatoria generada
     * Prototipo:
     *     String function get_password();
     * Parametros:
     *     sin parametros
     */
	public function get_password() { 		
		return $this->set_password();		
	}	
	

	public function __destruct() {
	}
}
?>