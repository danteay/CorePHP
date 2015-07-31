<?php 

class DirectoryUtils{

	/*
	/ Funcion:
	/     Crea un directorio en la ruta especificada
	/ Prototipo:
	/     void function MakeDir( String $ruta );
	/ Parametros:
	/     ruta - direccion completa del directorio que se desea crear
	*/
	public function MakeDir($ruta){
		if(!file_exists($ruta)){
			mkdir($ruta);
		}
	}


    /*
	/ Funcion:
	/     Regresa el numero total de elementos contenidos en un directorio
    / Prototipo:
    /     int function CountElements( String $ruta );
	/ Parametros:
	/     ruta - direccion completa del directorio del cual se desea contar los elementos contenidos en el
	*/
	public function CountElements($ruta){
		$directorio = opendir($ruta);
		$cont = 0;
		
		while($archivo = readdir($directorio)){
			if($archivo != '.' && $archivo != '..'){
				$cont++;
			}
		}
		
		return $cont;
	}


    /*
	/ Funcion:
	/     Genera el listado con los nombres de los archivos contenidos en un directorio
	/ Prototipo:
	/     array function ListFiles( String $ruta );
	/ Parametros:
	/     ruta - direccion completa del directorio del cual se desea generar el listado de archivos
    /     patern:null - Expresion regular que servira de filtro para el listado de archivos
	*/
	public function ListFiles($ruta, $patern = null){
		$directorio = opendir($ruta);
		$lista = array();
		$cont = 0;
		
		while($archivo = readdir($directorio)){
			if($archivo != '.' && $archivo != '..'){

                if(!empty($patern)){
                    if(preg_match($patern,$archivo)){
                        $lista[$cont] = $archivo;
                        $cont++;
                    }
                }else{
                    $lista[$cont] = $archivo;
                    $cont++;
                }
			}
		}
		
		return $lista;
	}


    /*
	/ Funcion:
	/     Borra un archivo
	/ Prototipo:
	/     bool function DeleteFile( String $ruta );
	/ Parametros:
	/     ruta - direccion completa del archivo el cual se desea borrar (No aplicable a directorios)
	*/
	public function DeleteFile($ruta){
		if(file_exists($ruta)){
			unlink($ruta);
            return true;
		}else{
            return false;
        }
	}


    /*
	/ Funcion:
	/     Borra un directorio especificado
	/ Prototipo:
	/     bool function DeleteDirectory( String $ruta );
	/ Parametros:
	/     ruta - direccion completa del directorio el cual se desea borrar.
	*/
	public function DeleteDirectory($ruta){
		if(is_dir($ruta)){
			$lista = $this->ListFiles($ruta);
			
			foreach($lista as $item){
				if(is_dir($ruta."/".$item)){
					$this->DeleteDirectory($ruta."/".$item);
				}else{
					$this->DeleteFile($ruta."/".$item);
				}
			}
			
			rmdir($ruta);
            return true;
		}else{
            return false;
        }
	}


    /**
     * @param $source - fichero a copiar
     * @param $target - lugar en donde se copiara en nuevo direcotio
     */
    public function fullCopy( $source, $target ) {
        if ( is_dir( $source ) ) {
            @mkdir( $target );
            $d = dir( $source );
            while ( FALSE !== ( $entry = $d->read() ) ) {
                if ( $entry == '.' || $entry == '..' ) {
                    continue;
                }
                $Entry = $source . '/' . $entry;
                if ( is_dir( $Entry ) ) {
                    $this->fullCopy( $Entry, $target . '/' . $entry );
                    continue;
                }
                copy( $Entry, $target . '/' . $entry );
            }

            $d->close();
        }else {
            copy( $source, $target );
        }
    }
	
}