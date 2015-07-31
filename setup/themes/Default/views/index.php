<?php
require_once("core/SessionUtils.php");

$session = new SessionUtils();

if(isset($_SESSION['admin']) && $_SESSION['admin'] != null){
    header("Location: views/home.php");
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login</title>

    <link rel="stylesheet" href="css/foundation.min.css"/>
    <link rel="stylesheet" href="css/normalize.css"/>
    <link rel="stylesheet" href="css/login.css"/>

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/foundation.min.js"></script>
</head>
<body>

<nav class="top-bar" data-topbar role="navigation">
    <ul class="title-area">
        <li class="name">
            <h1><a href="#"><b>My Site</b></a></h1>
        </li>
    </ul>
</nav>

<main>
    <div class="loginform">
        <form action="controllers/loginController.php" method="post">
            <div class="row">
                <div class="large-12 columns">
                    <label for="llave1">Usuario</label>
                    <input type="text" id="llave1" name="llave1" placeholder="nick"/>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <label for="llave2">Contraseña</label>
                    <input type="password" id="llave2" name="llave2" placeholder="password"/>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <input type="submit" class="button tiny expand" value="Entrar"/>
                </div>
            </div>
        </form>
    </div>
</main>


<script src="js/foundation/foundation.topbar.js"></script>
<!-- Other JS plugins can be included here -->

<script>
    $(document).foundation();
</script>
</body>
</html>