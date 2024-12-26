<?php include('../layout/header.php'); ?>
<?php require('../config/db.php'); ?>

<!-- c_form = Container Fomulario -->
<main class="container c_form">

    <hr>
    <h2>Registro de nuevos usuarios en el portal del paciente</h2>
    <hr>

    <!-- text1-r = Texto 1 Registro -->
    <p class="text1-r">Regístrese para gestionar sus citas online y disfrute de las ventajas de ser usuario de R&G Salud</p>

    <!-- text2-r = Texto 2 Registro -->
    <p class="text2-r"><i class="bi bi-exclamation-circle-fill"></i> Los campos marcados con <span>*</span>(asterisco) son <span>obligatorios</span></p>

    <?php require('../includes/validacion-form.php'); ?>

    <!-- form_r = Formulario Registro -->
    <form id="registroForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="row g-3 form_r">

        <hr>
        <h4 class="opacity-75">Datos personales</h4>

        <!-- Campo oculto para la acción -->
        <input type="hidden" name="accion" value="registrar">

        <!-- DATOS PERSONALES -->

        <div class="col-lg-4 col-12">
            <label for="nombre">Nombre(s)<span>*</span></label>
            <input type="text" class="form-control" id="nombre" value="<?php if (isset($nombre)) echo $nombre ?>" name="nombre" placeholder="NOMBRE">
        </div>

        <div class="col-lg-8 col-12">
            <label for="apellidos">Apellido(s)<span>*</span></label>
            <input type="text" class="form-control" id="apellidos" value="<?php if (isset($apellidos)) echo $apellidos ?>" name="apellidos" placeholder="APELLIDOS">
        </div>

        <div class="col-md-4 col-12">
            <label for="fechanacimiento">Fecha de nacimiento<span>*</span></label>
            <input type="date" class="form-control" id="fechanacimiento" value="<?php if (isset($fechaNacimiento)) echo $fechaNacimiento; ?>" name="fechaNacimiento" placeholder="dd/mm/aa">
        </div>

        <div class="col-md-4 col-12">
            <label for="phone">Teléfono móvil<span>*</span></label>
            <input type="number" value="<?php if (isset($numPhone)) echo $numPhone ?>" class="form-control" id="phone" name="numPhone" placeholder="TELÉFONO MÓVIL">
        </div>

        <?php $sexoSeleccionado = isset($_POST['sexo']) ? $_POST['sexo'] : ''; ?>
        <!-- con_sx = Container Sexo -->
        <div class="col-md-4 col-12 c_s-in">
            <label for="sexo">Sexo<span>*</span></label>
            <select id="sexo" name="sexo">
                <option value="" disabled <?php echo ($sexoSeleccionado === '') ? 'selected' : ''; ?>>---</option>
                <option value="masculino" <?php echo ($sexoSeleccionado === 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                <option value="femenino" <?php echo ($sexoSeleccionado === 'femenino') ? 'selected' : ''; ?>>Femenino</option>
                <option value="otro" <?php echo ($sexoSeleccionado === 'otro') ? 'selected' : ''; ?>>Otro</option>
            </select>
        </div>

        <!-- DATOS DE CONTACTO -->

        <hr>
        <h4 class="opacity-75">Datos de contacto</h4>


        <div class="col-lg-6 col-12">
            <label for="email">Correo electrónico<span>*</span></label>
            <input type="email" value="<?php if (isset($email)) echo $email ?>" class="form-control" id="email" name="email" placeholder="example@gmail.com">
        </div>

        <div class="col-lg-6 col-12">
            <label for="direccion">Dirección<span>*</span></label>
            <input type="text" value="<?php if (isset($direccion)) echo $direccion ?>" class="form-control" id="direccion" name="direccion" placeholder="DIRECCIÓN DE REFERENCIA">
        </div>

        <!-- PASSWORD -->

        <hr>
        <h4 class="opacity-75">Usuario y clave de acceso</h4>

        <div class="col-12">
            <label for="nombreUsuario">Nombre de usuario<span>*</span></label>
            <input type="text" value="<?php if (isset($nombreUsuario)) echo $nombreUsuario ?>" name="nombreUsuario" class="form-control" id="nombreUsuario" placeholder="Nombre de usuario">
        </div>


        <div class="col-md-6 col-12">
            <label for="password1">Contraseña<span>*</span></label>
            <input type="password" value="<?php if (isset($passw1)) echo $passw1 ?>" name="passw1" class="form-control" id="password1" placeholder="Contraseña">
        </div>

        <div class="col-md-6 col-12">
            <label for="password2">Repetir contraseña<span>*</span></label>
            <input type="password" value="<?php if (isset($passw2)) echo $passw2 ?>" name="passw2" class="form-control" id="password2" placeholder="Repetir contraseña">
        </div>

        <!-- text2-r-pass = Text2 Registro Password -->
        <p class="text2-r-pass"><i class="bi bi-exclamation-circle-fill"></i> Por seguridad introducir 8 caracteres como mínimo y, como mínimo, una mayúscula, minúscula y un número</p>

        <hr>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="consentimiento" value="1" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                Declaro haber leído y consiento <a href="politicas-privacidad.php">el tratamiento de datos</a> en los términos expuestos.<span>*</span>
            </label>
        </div>

        <hr>

        <!-- c_submit = Container Submit -->
        <div class="container mx-auto text-center c_submit">
            <div class="container mx-auto text-center c_submit">
                <label for="btn"></label>
                <input class="btn btn-primary" type="submit" Value="REGISTRARSE" name="submit">
            </div>
        </div>

    </form>

</main>


<?php include('../layout/footer.php'); ?>