<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TheCore-PHP Setup</title>

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
                <li class="divider"></li>
            </ul>
        </nav>
    </div>

    <header>
        <div class="row">
            <div class="large-12 columns">
                <h1>CorePHP <small>Versión: 3.0</small></h1>
                <h6>Una solución para la creación de plataformas de administración de una manera facil, rapida y segura.</h6>
            </div>
        </div>
    </header>

    <main>
        <?php
        if(isset($_GET['errorManager']) && $_GET['errorManager'] == 1){
            ?>
            <div data-alert class="alert-box alert" id="Alert"> <!-- Your content goes here -->
                Para instalar el Managar de TheCore en necesario primero instalar el framework. De click en la opcion <em>Framework Install</em> para intalarlo.
                <a onclick="$('#Alert').css('display','none');" class="close">&times;</a>
            </div>
            <?php
        }
        ?>



        <div class="row">
            <div class="large-12 columns">
                <h1>Introducción</h1>
                <p>
                    <em>CorePHP</em> es una plataforma simple de usar, la cual le permite crear desde el nucleo de una
                    aplicacion sencilla, hasta una compleja aplicacion con funcionalidad completa.
                    <br/><br/>

                    <em>CorePHP</em> se basa en el patron de diseño DAO y toma algunas practicas del modelo MVC, dando
                    como resultado aplicaciones modulares y faciles de manipular convirtiendola en una herramienta
                    flexible para cualquier tipo de desarrollo que involucre bases de datos.
                    <br/><br/>

                    <em>CorePHP</em> trabaja directamente desde su instalación con la base de datos (actualmente solo
                    disponible el soporte para MySQL) que se especifica para la conexión generando así los modelos de
                    las tablas requeridos, archivos de conexión, y sentencias básicas necesarias para cada cobertura a
                    crear, permitiendo así tener un núcleo sólido, manejable y completamente modificable desde el primer
                    comienzo del desarrollo. <br/>
                </p>
                <hr/>
            </div>
        </div>



        <div class="row">
            <div class="large-12 columns">
                <p style="text-align: center;">
                    <a href="steps.php ">
                        <img src="img/gear.svg" alt="" style="width: 200px; height: auto;"/>
                        <br/>
                        <div class="text-center"><h3>Instalar CorePHP</h3></div>
                    </a>
                </p>
            </div>
        </div>
    </main>

    <footer></footer>

    <script src="js/foundation/foundation.topbar.js"></script>
    <script src="js/foundation/foundation.alert.js"></script>
    <!-- Other JS plugins can be included here -->

    <script>
        $(document).foundation();
    </script>
</body>
</html>