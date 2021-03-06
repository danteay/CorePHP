<?php
require_once("../core/DBOMySQL.php");
require_once("../core/DirectoryUtils.php");

$conx = new DBOMySQL();
$filemanager = new DirectoryUtils();

?>
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
                <h1>CorePHP <small>Versión: 4.0</small></h1>
                <h6>Una solución para la creación de plataformas de administración de una manera facil, rapida y segura.</h6>
            </div>
        </div>
    </header>

    <main>
        <div class="row">
            <div class="large-12 columns text-center">
                <h1>Guia de instalación</h1>
                <hr/>
            </div>
        </div>

        <?php if(!isset($_GET['tb']) || empty($_GET['tb'])){ ?>

            <form action="controllers/Install.php" method="post">

            <div class="row">
                <div class="large-12 columns">
                    <h4>Paso 2:
                        <small>[Seleccion del tema]</small>
                    </h4>
                </div>
            </div>

            <div class="row">
                <div class="large-12 columns">
                    <select name="theme" id="theme">
                        <?php

                        foreach($filemanager->ListFiles("themes") as $value){
                            $theme = new SimpleXMLElement(file_get_contents("themes/".$value."/configTheme.xml"));

                            if($theme->directives->name == "Default"){
                                echo "<option value='".$value."' selected>".$theme->directives->name."</option>";
                            }else{
                                echo "<option value='".$value."'>".$theme->directives->name."</option>";
                            }

                        }

                        ?>
                    </select>

                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="large-12 columns">
                    <h4>Paso 3: <small>[Configuracion del panel de control]</small></h4>
                </div>
            </div>

            <div class="row">
                <div class="large-12 columns">
                    <p>
                        Seleccione la tabla que servira para los usuarios administradores del panel de control. Esta tabla debera ser una tabla con al menos 2 campos
                        usuario y contraseña (los nombre de los campos no son obligatorios), o puede seleccionar la opcion 'Generar automaticamente', la cual creara una
                        tabla dedicada a la gestion de administradores para el panel de control. La generacion automática no modificara la estructura existente
                        de la base de datos.
                    </p>
                </div>
            </div>
            <div class="row">

                    <div class="large-12 columns">
                        <?php
                            $conx->initializeQuery("SHOW TABLES FROM ".$_GET['db']);
                            $tables = $conx->getRequest();

                            $tables->fetch_array();

                            $cont = 0;

                            foreach($tables as $value){
                                if(!preg_match("/^(thecore_)/",$value['Tables_in_' . $_GET['db']])) {
                                    ?>
                                    <label for="table<?php echo $cont; ?>">
                                        <input type="radio" name="adminTab" id="table<?php echo $cont; ?>"
                                               value="<?php echo $value['Tables_in_' . $_GET['db']] ?>"/> <?php echo $value['Tables_in_' . $_GET['db']] ?>
                                    </label>
                                    <?php
                                    $cont++;
                                }
                            }
                        ?>

                        <input type="hidden" name="db" value="<?php $_GET['db'] ?>"/>

                        <label for="defaultAdminTable">
                            <input type="radio" name="adminTab" id="defaultAdminTable" value="defaultAdminTable"/> Generar automaticamente
                        </label>
                    </div>

                    <input type="hidden" name="dbas" value="<?php echo $_GET['db']; ?>"/>
                    <input type="hidden" name="version" value="<?php echo $_GET['version']; ?>"/>
                    <input type="hidden" name="panel" value="1"/>

                    <div class="large-12 columns">
                        <div class="row">
                            <input type="submit" class="button tiny" value="Siguiente"/>
                        </div>
                    </div>

            </div>

            </form>

        <?php }elseif(isset($_GET['tb']) && !empty($_GET['tb'])){ ?>

            <div class="row">
                <div class="large-12 columns">
                    <h4>Paso 4: <small>[Selección de campos llave]</small></h4>
                </div>
            </div>

            <div class="row">
                <div class="large-12 columns">
                    <p>
                        Seleccione respectivamente de <b><i>'Usuario'</i></b> y <b><i>Password</i></b>.<br/>
                        Estos campos seran usados para el ingreso a la aplicación.
                    </p>
                </div>
            </div>

            <form name="setInstallFinish" action="controllers/Install.php" method="post">

                <div class="row">
                    <div class="large-6 columns"><h4>Usuario</h4></div>
                    <div class="large-6 columns"><h4>Password</h4></div>
                </div>

                <?php
                    $conx->initializeQuery("SHOW COLUMNS FROM ".$_GET['tb']);
                    $tables = $conx->getRequest();
                    $cont = 0;

                    if($tables != null && $tables->num_rows > 1){
                        while($col = $tables->fetch_array(MYSQL_NUM)){
                ?>
                            <div class="row">
                                <div class="large-6 columns">
                                    <label for="llave1-<?php echo $cont; ?>" >
                                        <input type="radio" name="llave1" id="llave1-<?php echo $cont; ?>" value="<?php echo $col[0]; ?>"/> <?php echo $col[0]; ?>
                                    </label>
                                </div>
                                <div class="large-6 columns">
                                    <label for="llave2-<?php echo $cont; ?>" >
                                        <input type="radio" name="llave2" id="llave2-<?php echo $cont; ?>" value="<?php echo $col[0]; ?>"/> <?php echo $col[0]; ?>
                                    </label>
                                </div>
                            </div>
                <?php
                            $cont++;
                        }
                    }else{
                ?>

                <div class="row">
                    <div class="large-12 columns">
                        <center>
                            <h3>Campos insuficientes</h3>
                            <a href="configPanel.php?db=<?php echo $_GET['db']; ?>"></a>
                        </center>
                    </div>
                </div>

                <?php } ?>

                <input type="hidden" name="dbas" value="<?php echo $_GET['db'] ?>"/>
                <input type="hidden" name="version" value="<?php echo $_GET['version']; ?>"/>
                <input type="hidden" name="adminTab" value="<?php echo $_GET['tb'] ?>"/>
                <input type="hidden" name="theme" value="<?php echo $_GET['theme'] ?>"/>
                <input type="hidden" name="panel" value="1"/>
                <input id="viewGenerate" name="viewGenerate" type="hidden" value=""/>
            </form>

            <div class="row">
                <div class="large-12 columns">
                    <hr/>
                </div>
                <div class="large-12 columns">
                    <h4>Paso 5: <small>[Seleccion de vistas]</small></h4>
                </div>
                <div class="large-12 columns">
                    <p>
                        Selecciona las vistas que desea que se generen en su aplicación para cada tabla en la base de datos
                    </p>
                </div>
                <div class="large-12 columns">
                    <?php
                        $conx->initializeQuery("SHOW TABLES FROM ".$_GET['db']);
                        $tables = $conx->getRequest();
                        $cont = 0;

                        $tables->fetch_array();

                        foreach($tables as $value){
                            if(!preg_match("/^(thecore_)/",$value['Tables_in_' . $_GET['db']])) {
                                ?>
                                <div class="row">
                                    <div class="large-12 columns">
                                        <hr/>
                                    </div>
                                    <div class="large-12 columns"><?php echo $value['Tables_in_' . $_GET['db']]; ?></div>
                                    <div class="large-4 columns">
                                        <label for="view-report-<?php echo $cont; ?>"><input type="checkbox" id="view-report-<?php echo $cont; ?>"/> Reporte</label>
                                    </div>
                                    <div class="large-4 columns">
                                        <label for="view-add-<?php echo $cont; ?>"><input type="checkbox" id="view-add-<?php echo $cont; ?>"/> Altas</label>
                                    </div>
                                    <div class="large-4 columns">
                                        <label for="view-edit-<?php echo $cont; ?>"><input type="checkbox" id="view-edit-<?php echo $cont; ?>"/> Edicion y Bajas</label>
                                    </div>
                                </div>
                                <?php
                                $cont++;
                            }
                        }
                    ?>

                    <input id="total-views" type="hidden" value="<?php echo $cont; ?>"/>
                </div>
            </div>

            <div class="row">
                <div class="large-12 columns">
                    <input id="finish" type="button" class="button tiny" value="Terminar"/>
                </div>
            </div>

        <?php } ?>

    </main>

    <footer></footer>

    <script src="js/foundation/foundation.topbar.js"></script>
    <script src="js/foundation/foundation.alert.js"></script>
    <!-- Other JS plugins can be included here -->

    <script src="js/view-select.js"></script>

    <script>
        $(document).foundation();
    </script>

</body>
</html>