<?php require('inc/head.inc.php'); ?>
<body>
    <?php require('inc/navbar.inc.php'); ?>

    <img src="img/LandingXL.png" class="img-fluid">

    <div class="container">
        <div class="my-4 text-center text-black">
                <h1>Lo mejor de los mundiales</h1>
                <p class="my-2"><a class="btn btn-lg btn-primary" href="<?= url('signup') ?>">Registrate ahora</a></p>
        </div>
        <hr class="featurette-divider my-5">
        <div class="row featurette">
            <div class="col-md-7">
                <h2 class="featurette-heading fw-normal lh-1">
                    Publica y comparte tu afición. <br>            
                    <span class="text-body-secondary">
                        Cualquier jugador, selección o mundial.
                    </span>
                </h2>
                <p class="lead">
                    Puedes crear publicaciones sobre tus temas favoritos a cerca de los mundiales fútbol.                 
                    Interactuar con usuarios mediante comentarios y likes te hará sentir la pasión de quienes te rodean.                 
                </p>
                <p class="lead my-2">
                    Añade fotos o videos a tus tus publicaciones para darles un toque único.
                    Además, podrás buscar las publicaciones de los demás usuarios mediante la barra de búsqueda y nuestro sistema de filtros.
                </p>
            </div>
            <div class="col-md-5">
                <img src="img/copa.jpg" class="img-fluid rounded mb-2" alt="La copa del mundo">
            </div>
        </div>
        <hr class="featurette-divider m-5">
        <div class="row featurette">
            <div class="col-md-8 order-md-2">
                <h2 class="featurette-heading fw-normal lh-1">
                    Una sola wiki. 
                    <span class="text-body-secondary">
                        Toda la historia del fútbol.
                    </span>
                </h2>
                <p class="lead">
                    La información más selecta y verificada por los expertos sobre todos los mundiales de futbol que han existido.
                    Nuestros administradores se encargan de enriqueder día a día aún más esta gran librería digital.
                </p>
                <p class="lead my-2">
                    También existen categorías de cada mundial de fútbol que ha existido las cuales podrás añadir a tus piblicaciones, 
                    así formas parte de este gran esfuerzo por preservar la información de el deporte más icónico de la humanidad.
                </p>
            </div>
            <div class="col-md-4 order-md-1"> 
                <img src="img/world1.jpg" class="img-fluid rounded mb-2" alt="El planeta tierra con un balón de fútbol como luna">
            </div>
        </div>
        <hr class="featurette-divider"> <!-- /END THE FEATURETTES -->
    </div>
    <footer class="container">
        <p class="float-end"><a href="#">
            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#000000"><path d="m280-400 200-200.67L680-400H280Z"/></svg>
        </a></p>
        <p>2025 · <a href="https://github.com/nullnekocat/FootBook">GitHub Repo</a></p>
    </footer>
    <?php require('inc/footer.inc.php'); ?>
</body>