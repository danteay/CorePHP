<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TheCore-PHP Setup</title>

    <link rel="stylesheet" href="css/foundation.min.css"/>
    <link rel="stylesheet" href="css/normalize.css"/>

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/foundation.min.js"></script>

    <style>
        p{ text-align: justify }
    </style>
</head>
<body>
    <div class="fixed">
        <nav class="top-bar" data-topbar role="navigation">
            <ul>
                <li><a href="#"><img src="img/icon2.png" alt="The Core" style="height: 30px; width: auto; margin-top: 5px;"/></a></li>
            </ul>
        </nav>
    </div>

    <header>
        <img src="img/the-core-banner2.png" alt="" style="max-width: 100%; height: auto;"/>
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
                    <em>The Core</em> es un framework de PHP el cual está enfocado en la modularidad y reutilización de sentencias para base de datos. Actualmente se encuentra en su primera versión beta
                    Por lo cual está sujeta a cambios drásticos en funcionamiento. <br/><br/>

                    El sistema de funcionamiento de <em>The Core</em> es simple, sin embargo recomendable tener nociones de programación orientada a objetos, dado que este Framework es 100% orientado a objetos
                    <br/><br/>

                    <em>The Core</em> trabaja directamente desde su instalación con la base de datos (actualmente solo disponible el soporte para MySQL) que se especifica para la conexión generando así los
                    modelos de las tablas requeridos, archivos de conexión, y sentencias básicas necesarias para cada cobertura a crear, permitiendo así tener un núcleo sólido, manejable y completamente modificable
                    desde el primer comienzo del desarrollo. <br/>
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
                        <div class="text-center"><h3>Instalar TheCore-PHP</h3></div>
                    </a>
                </p>
            </div>
        </div>
    </main>

    <footer style="width: 100%; min-height: 100px; padding-top: 20px; padding-bottom: 10px; overflow: hidden; background-color: maroon"></footer>

    <script src="js/foundation/foundation.topbar.js"></script>
    <script src="js/foundation/foundation.alert.js"></script>
    <!-- Other JS plugins can be included here -->

    <script>
        $(document).foundation();
    </script>
</body>
</html>