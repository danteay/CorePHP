<?php

/*
 * Este archivo no fue desarrollado propiamente para The Core.
 * Este archivo fue recopilado de una serie de plugins precreados, corregido e implementado.
 * Fuente: PHPClases.com
 */

require_once("DirectoryUtils.php"); // Imclucion de manejo de directorios
require_once("PasswordGenerator.php"); // Para generar nombres aleatorios y evitar sobreescritura de archivos

class SimpleImage {   

    private $image; // variable para manejar la imagen
    private $image_type; // variable de manejo del tipo de imagen


    /*
     * Funcion:
     *     Carga la imagen a procezar
     * Prototipo:
     *     void function load( String $filename );
     * Parametros:
     *     filename - direccion completa de la imagen a cargar
     */
    public function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];

        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
        }
    }


    /*
     * Funcion:
     *     Guarda la imagen procesada
     * Prototipo:
     *     void function save( String $filename, const $image_type [ IMAGETYPE_JPEG | IMAGETYPE_GIF | IMAGETYPE_PNG ], int $compression, int $permissions);
     * Parametros:
     *     filename - nombre del nevo archivo a generar
     *     image_type - tipo con el que la nueva imagen sera guardada
     *     compression - porcentaje de comprecion para la nueva imagen
     *     permissions - permisos de administracion con los que la imagen sera guardada
     */
    public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {

        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image,$filename,$compression);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            imagegif($this->image,$filename);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            imagepng($this->image,$filename);
        }

        if( $permissions != null) {
            chmod($filename,$permissions);
        }

    }


    /*
     * Funcion:
     *     Genera una nueva imagen a partir una previamente cargada y la muestra directamente en el navegador sin guardarla en disco
     * Prototipo:
     *     void function output( const $image_type[ IMAGETYPE_JPEG | IMAGETYPE_GIF | IMAGETYPE_PNG ] );
     * Parametros:
     *     image_type - constante que define el tipo de imagen con que se mostrara el resultado
     */
    public function output($image_type=IMAGETYPE_JPEG) {
        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            imagegif($this->image);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            imagepng($this->image);
        }
    }


    /*
     * Funcion:
     *     Regresa el ancho de la imagen cargada en pixeles
     * Prototipo:
     *     int function getWidth();
     * Parametros:
     *     sin parametros
     */
    public function getWidth() {
        return imagesx($this->image);
    }


    /*
     * Funcion:
     *     Regresa la altura de la imagen cargada en pixeles
     * Prototipo:
     *     int function getHeight();
     * Parametros:
     *     sin parametros
     */
    public function getHeight() {
        return imagesy($this->image);
    }


    /*
     * Funcion:
     *     Redimenciona la imagen cargada con respecto a una altura dada
     * Prototipo:
     *     void function resizeToHeidht( int $height );
     * Parametros:
     *     height - altura sobre la cual se redimencionara la imagen
     */
    public function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }


    /*
     * Funcion:
     *     Redimenciona la imagen cargada con respecto a un ancho dado
     * Prototipo:
     *     void function resizeToWidth( int $width );
     * Parametros:
     *     width - ancho sobr el cual se redimencionara la imagen
     */
    public function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }


    /*
     * Funcion:
     *     Redimenciona la imagen cargada conforme a un porcentaje
     * Prototipo:
     *     void function scale( float $scale );
     * Parametros:
     *     scale - porcentaje al cual ser redimencionara la imagen
     */
    public function scale($scale) {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }


    /*
     * Funcion:
     *     Redimenciona la cimagen cargada con respecto a un ancho y altura dados
     * Prototipo:
     *     void function resize( int $width, int $height );
     * Parametros:
     *     width - ancho de la nueva imagen
     *     height - alto de la nueva imagen
     */
    public function resize($width,$height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }


    /*
     * Funcion:
     *     Carga un imagene en el servidor
     * Prototipo:
     *     bool | String function upload( String $destination_dir, filearray $file );
     * Parametros:
     *     destination_dir - direccion completa (sin nombre de archivo) donde se guardara la imagen.
     *     file - arreglo con los datos del archivo a subir (regularmete es un arreglo global $_FILES el que se pasa como parametro)
     */
    public function upload($destination_dir = '', $file = array()){

        $tmp_name = $file['tmp_name'];

        if (is_uploaded_file($tmp_name)){
            $crea = new DirectoryUtils();

            if(!is_dir($destination_dir) && $destination_dir != ''){
                $crea->MakeDir($destination_dir);
            }

            $img_type  = explode("/", $file['type']);
            $type = $img_type[1];

            $args = array(
                'lenght'                =>  20,
                'alpha_upper_include'   =>  TRUE,
                'alpha_lower_include'   =>  TRUE,
                'number_include'        =>  TRUE,
                'symbol_include'        =>  FALSE,
            );

            $randobj = new PasswordGenerator($args);
            $name = $randobj->get_password();
            $img_file = $name.".".$type;

            if ($type === "gif" || $type === "jpeg" || $type === "jpg" || $type === "png"){
                $dir = $destination_dir."/".$img_file;

                if(move_uploaded_file($tmp_name, $dir)){
                    return $img_file;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }

    }


    /*
     * Funcion:
     *     Sube una imagen al servidor y la guarda con una redimencion con respecto a una altura dada
     * Prototipo:
     *     void function uploadAndScaleToHeight( filearray $file, String $dir, int $height);
     * Parametros:
     *     file - arreglo con los datos del archivo a subir (regularmete es un arreglo global $_FILES el que se pasa como parametro).
     *     dir - direccion completa (sin nombre de archivo) donde se guardara la imagen.
     *     height - altura sobre la cual se redimencionara la imagen.
     */
    public function uploadAndScaleToHeight($file=array(),$dir,$height){
        $upload_file = $this->upload($dir,$file);
        $this->load($upload_file);
        $this->resizeToHeight($height);
        $this->save($upload_file);
    }


    /*
     * Funcion:
     *     Sube una imagen al servidor y la guarda con una redimencion con respecto a un ancho dado
     * Prototipo:
     *     void function uploadAndScaleToWidth( filearray $file, String $dir, int $width);
     * Parametros:
     *     file - arreglo con los datos del archivo a subir (regularmete es un arreglo global $_FILES el que se pasa como parametro).
     *     dir - direccion completa (sin nombre de archivo) donde se guardara la imagen.
     *     width - ancho sobre el cual se redimencionara la imagen.
     */
    public function uploadAndScaleToWidth($file=array(),$dir,$width){
        $upload_file = $this->upload($dir,$file);
        $this->load($upload_file);
        $this->resizeToWidth($width);
        $this->save($upload_file);
    }


    /*
     * Funcion:
     *     Sube una imagen al servidor y la guarda con una redimencion con respecto a un porcentaje de su tamaño original
     * Prototipo:
     *     void function uploadAndScalePercent( filearray $file, String $dir, int $percent);
     * Parametros:
     *     file - arreglo con los datos del archivo a subir (regularmete es un arreglo global $_FILES el que se pasa como parametro).
     *     dir - direccion completa (sin nombre de archivo) donde se guardara la imagen.
     *     height - altura sobre la cual se redimencionara la imagen.
     */
    public function uploadAndScalePercent($file=array(),$dir,$percent){
        $upload_file = $this->upload($dir,$file);
        $this->load($upload_file);
        $this->scale($percent);
        $this->save($upload_file);
    }


    /**
     * Funcion:
     *      Borra una imagen almacenada en el servidor.
     * Prototipo:
     *      bool function deleteImage( String $dir );
     *
     * @param $dir - direccion de la imagen a borrar.
     * @return bool
     */
    public function deleteImage($dir){
        if(unlink($dir)){
            return true;
        }

        return false;
    }

}
