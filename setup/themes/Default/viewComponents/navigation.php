<nav class="top-bar" data-topbar role="navigation">
    <ul class="title-area">
        <li class="name">
            <h1><a href="../../index.php"><b>My Site</b></a></h1>
        </li>
    </ul>

    <section class="top-bar-section">
        <ul class="left">
            <li class="divider"></li>
            <li><a href="home.php">Home</a></li>
            <!--addlinkmenu-->
        </ul>

        <ul class="right">
            <li class="divider"></li>
            <li class="has-dropdown">
                <a href="#"><?php echo $_SESSION['admin']['nick']; ?></a>
                <ul class="dropdown">
                    <li><a href="../controllers/loginController.php?sess=0">Salir</a></li>
                </ul>
            </li>
        </ul>
    </section>
</nav>