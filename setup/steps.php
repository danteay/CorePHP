<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CorePHP Setup</title>

    <link rel="stylesheet" href="css/foundation.min.css"/>
    <link rel="stylesheet" href="css/normalize.css"/>
    <link rel="stylesheet" href="css/style.css">

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/foundation.min.js"></script>
</head>
<body>
    <div class="fixed">
        <nav class="top-bar" data-topbar role="navigation">
            <ul class="title-area">
                <li class="name">
                    <h1><a href="https://github.com/danteay/CorePHP">CorePHP</a></h1>
                </li>
            </ul>
        </nav>
    </div>

    <header>
        <div class="row">
            <div class="large-12 columns">
                <h1>CorePHP</h1>
                <h6>Una solución para la creación de plataformas de administración de una manera facil, rapida y segura.</h6>
            </div>
        </div>
    </header>

    <main>
        <?php
        if(isset($_GET['error']) && $_GET['error'] == 201){
            ?>
            <div data-alert class="alert-box alert" id="Alert"> <!-- Your content goes here -->
                Ocurrio un error en la instalacion.
                <a onclick="$('#Alert').css('display','none');" class="close">&times;</a>
            </div>
        <?php
        }
        ?>

        <?php
        if(isset($_GET['error']) && $_GET['error'] == 0){
            ?>
            <div data-alert class="alert-box success" id="Alert"> <!-- Your content goes here -->
                Se instalo con exito <em>The Core</em> en su servidor. <br>
                <?php
                if(isset($_GET['ad']) && $_GET['ad'] == 'true'){
                    if(!isset($_GET['custom'])){
                        echo "<b>AVISO:</b> Se ha creado un usuario por defecto coon nick: <b>'<i>root</i>'</b> y password <b>'<i>root</i>'</b><br/>";
                    }

                    echo "Entra a tu aplicación dando click <a href='../index.php'>AQUI</a><br/>";
                    echo "Si desea regresar al menu de instaslacion de click <a href='index.php'>AQUI</a>";
                }
                ?>
                <a onclick="$('#Alert').css('display','none');" class="close">&times;</a>
            </div>
        <?php
        }
        ?>

        <?php
        if(isset($_GET['error']) && $_GET['error'] == 204){
            ?>
            <div data-alert class="alert-box alert" id="Alert"> <!-- Your content goes here -->
                <?php
                switch($_GET['v']){
                    case 1:
                        echo "El campo de <em>HOST</em> no puede quedar vacio.";
                        break;

                    case 2:
                        echo "El campo de <em>USUARIO</em> no puede quedar vacio.";
                        break;

                    case 3:
                        echo "La base de datos seleccionada no cuenta con tablas para su manipulacion. Asegurese de que al menos exista una tabla dentro de la base de datos.";
                        break;

                    case 4:
                        echo "El campo de <em>BASE DE DATOS</em> no puede quedar vacio.";
                        break;
                }
                ?>
                <a onclick="$('#Alert').css('display','none');" class="close">&times;</a>
            </div>
        <?php
        }
        ?>

        <div class="row">
            <div class="large-12 columns text-center">
                <h1>Guia de instalación</h1>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="large-12 columns">
                <h3>Instalación</h3><br/>
                <h4>Paso 1:</h4>
                <p>
                    Completa la siguiente configuración, en donde deberas llenar los datos referentes a la base de datos (host, usuario, password, nombre de la base)
                    y si lo deseas tambien podras realizar la configuración para la creación de un panel de control basico (configuraciones de panel en desarrollo).
                </p>

                <p>
                    Una vez terminada la instalación de los datos del servidor usted ya contara con el archivo de conexión, los modelos de las tablas y el archivo QueryMap.php el cual contara
                    con las sentencias básicas de SQL que se requieren para el funcionamiento de su aplicación las cuales serán:
                    <br/>

                    <ul>
                        <li>Selección de un elemento por id: <code>SELECT * FROM {table} where {clave-primaria} = ?</code></li>
                        <li>Selección de todos los elementos de una tabla: <code>SELECT * FROM {tabla}</code></li>
                        <li>Inserción de un registro en una tabla: <code>INSERT INTO {tabla} ( {campos} ) VALUES (?,?,...)</code></li>
                        <li>Eliminación de un elemento por id: <code>DELETE FROM {tabla} WHERE {clave-primaria} = ? </code></li>
                    </ul>
                </p>
            </div>

            <div class="large-12 columns">
                <form id="formStep1" action="controllers/Install.php" method="post">
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="host">Host
                                <input type="text" name="host" id="host" placeholder="ejemplo: localhost"/>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="user">Usuario
                                <input type="text" name="user" id="user" placeholder="ejemplo: root" />
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="pass">Password
                                <input type="text" name="pass" id="pass" placeholder="ejemplo: root" />
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="dbas">Base de datos
                                <input type="text" name="dbas" id="dbas" placeholder="ejemplo: root" />
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="large-12 columns">
                            <label for="ver1"><input id="ver1" name="version" type="radio" value="1" checked/> Version con sentencias preparadas.</label>
                            <label for="ver2"><input id="ver2" name="version" type="radio" value="2"/> Version sin sentencias preparadas.</label>
                        </div>
                    </div>

                    <input type="hidden" id="panel2" name="panel" value="0"/>

                    <div class="row">
                        <div class="large-3 columns">
                            <label class="inline" for="panel">Generar panel de control</label>
                        </div>
                        <div class="large-3 columns">
                            <div class="switch">
                                <input id="panel" type="checkbox">
                                <label for="panel"></label>
                            </div>
                        </div>
                        <div class="large-6 columns">
                            <button class="button tiny right" >Sigiente</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <footer ></footer>

    <script src="js/foundation/foundation.topbar.js"></script>
    <script src="js/foundation/foundation.alert.js"></script>
    <!-- Other JS plugins can be included here -->

    <script>
        $(document).foundation();

        $(document).ready(function(){
            $("#panel").click(function(){
                if($("#panel").attr('checked')){
                    $("#panel").removeAttr('checked');
                    document.getElementById("panel2").value = 0;
                }else {
                    $("#panel").attr('checked', 'checked');
                    document.getElementById("panel2").value = 1;
                }
            });


        });
    </script>
</body>
</html>