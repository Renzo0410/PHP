<?php include('../layout/header.php'); ?>
<?php require('../config/db.php'); ?>

<?php
// Obtener las últimas 3 noticias
$query = "SELECT idNoticia, titulo, imagen, texto FROM noticias ORDER BY fecha DESC LIMIT 3";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>

    <!-- c_m = Container Main -->
    <div class="container-md c_m">

        <!-- c_d-e = Container Doctor & Enfermeros -->
        <main class="container mx-auto c_d-e row">

            <div class="col-12 col-md-5">
                <h2>Centro Médico</h2>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quisquam, ipsum eos quibusdam quas iure tempore quis modi in, illo porro totam qui earum fuga quia veritatis atque similique! Amet, omnis. Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>

            <div class="col-12 col-md-7">
                <!-- img_d-e = imagen doctor y enferemero -->
                <img class="img_d-e img-fluid" src="../img/index/doctor&enfermero.jpg" alt="Doctores & Enfermeros">
            </div>

        </main>

    </div>

    <!-- c_m-2 = Container Main 2 -->
    <div class="container c_m-2">

        <!-- c_m-a = Container Main y Aside -->
        <div class="c_m-a row">

            <main class="col-12 col-md-8 row mx-auto">
                <div class="col-12">
                    <h2>Sobre nosotros</h2>
                </div>
                <div class="row mx-auto col-lg-6 col-12">
                    <div class="col-xs-12 col-sm-6 col-lg-12">
                        <img class="img_n img-cluid" src="../img/nosotros/hospital-daroca.png" alt="Hospital Daroca">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-lg-12">
                        <p class="text-n">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Illo libero facere ratione tempore repellendus, velit consequatur assumenda odit ad delectus commodi corporis.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <p class="text-n">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quae voluptas aliquam ducimus accusantium velit totam pariatur ullam itaque illum quam magni nisi exercitationem recusandae debitis impedit quaerat harum, fugit tenetur? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam sunt nemo, voluptatem, ullam nisi aliquam cumque a nam obcaecati aut, quaerat aliquid impedit iure incidunt maxime sed repellendus nobis voluptate!</p>
                </div>

                <div class="col-lg-6 col-12">
                    <p class="text-n">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quae voluptas aliquam ducimus accusantium velit totam pariatur ullam itaque illum quam magni nisi exercitationem recusandae debitis impedit quaerat harum, fugit tenetur? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam sunt nemo, voluptatem, ullam nisi aliquam cumque a nam obcaecati aut, quaerat aliquid impedit iure incidunt maxime sed repellendus nobis voluptate!</p>
                </div>
                <div class="row mx-auto col-lg-6 col-12">
                    <div class="col-xs-12 col-sm-6 col-lg-12">
                        <img class="img_n img-cluid" src="../img/nosotros/salud.jpg" alt="Salud">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-lg-12">
                        <p class="text-n">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Illo libero facere ratione tempore repellendus, velit consequatur assumenda odit ad delectus commodi corporis.</p>
                    </div>
                </div>

                <div class="row mx-auto col-lg-6 col-12">
                    <div class="col-xs-12 col-sm-6 col-lg-12">
                        <img class="img_n img-cluid" src="../img/nosotros/pasillo.jpg" alt="Salud">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-lg-12">
                        <p class="text-n">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Illo libero facere ratione tempore repellendus, velit consequatur assumenda odit ad delectus commodi corporis.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <p class="text-n">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quae voluptas aliquam ducimus accusantium velit totam pariatur ullam itaque illum quam magni nisi exercitationem recusandae debitis impedit quaerat harum, fugit tenetur? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam sunt nemo, voluptatem, ullam nisi aliquam cumque a nam obcaecati aut, quaerat aliquid impedit iure incidunt maxime sed repellendus nobis voluptate!</p>
                </div>
            </main>

            <?php include('../layout/aside.php'); ?>

        </div>

    </div>

    <!-- c_m-3 = Container Main 3 -->
    <!-- new_cc = New Con Carrousel -->
    <div class="container c_m-3 new_cc">

        <h2>Noticias</h2>

        <main id="carouselExampleDark" class="carousel carousel-dark slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="bd_l active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" class="bd_l" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" class="bd_l" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="5000">
                    <img src="../img/noticias/noticia_uno.jpg" class="d-block w-100" alt="Noticia 1">
                    <div class="carousel-caption d-md-block">
                        <p>A caption for the above image.</p>
                        <a href="noticias.php" class="btn_vm btn">Ver más</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="../img/noticias/noticia_dos.jpeg" class="d-block w-100" alt="Noticia 2">
                    <div class="carousel-caption d-md-block">
                        <p>A caption for the above image.</p>
                        <a href="noticias.php" class="btn_vm btn">Ver más</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="../img/noticias/noticia_tres.jpg" class="d-block w-100" alt="Noticia 3">
                    <div class="carousel-caption d-md-block">
                        <p>A caption for the above image.</p>
                        <a href="noticias.php" class="btn_vm btn">Ver más</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </main>
    </div>

    <!-- c_s = Container Servicios -->
    <div class="container c_s row mx-auto">

        <h2 class="text-center">Servicios</h2>

        <!-- cc_s = Container Cards Servicios -->
        <main class="cc_s row mx-auto">
            <div class="col-md-3 col-3 mx-auto card" style="width: 18rem;">
                <!-- icon_s = icono servicios -->
                <i class="icon_s bi bi-heart-pulse-fill mx-auto"></i>
                <h5 class="card-title text-center">Cardiología</h5>
            </div>
            <div class="col-md-3 col-3 mx-auto card" style="width: 18rem;">
                <i class="icon_s bi bi-virus2 mx-auto"></i>
                <h5 class="card-title text-center">Vacunas</h5>
            </div>
            <div class="col-md-3 col-3 mx-auto card" style="width: 18rem;">
                <i class="icon_s bi bi-hospital mx-auto"></i>
                <h5 class="card-title text-center">Pedriatría</h5>
            </div>
            <div class="col-md-3 col-3 mx-auto card" style="width: 18rem;">
                <i class="icon_s bi bi-lungs mx-auto"></i>
                <h5 class="card-title text-center">Neumología</h5>
            </div>
        </main>
    </div>

    <?php include('../layout/footer.php'); ?>

</body>

</html>