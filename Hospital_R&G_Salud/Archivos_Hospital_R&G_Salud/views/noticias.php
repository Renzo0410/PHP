<?php
include('../layout/header.php');
require('../config/db.php');
?>

<div class="container error-form">
    <?php
    $query = "SELECT noticias.*, users_login.usuario 
    FROM noticias 
    JOIN users_data ON noticias.idUser = users_data.idUser 
    JOIN users_login ON users_data.idUser = users_login.idUser 
    ORDER BY noticias.fecha DESC";

    $result = $conn->query($query);
    ?>
</div>

<main class="container c_notice" id="noticias">
    <hr>
    <h2>Noticias sobre R&G Salud</h2>
    <hr>

    <?php
    // Verifica si hay resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="../img/noticias/<?php echo htmlspecialchars($row['imagen']); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['titulo']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['texto']); ?></p>
                            <p class="card-text text-end"><small class="text-body-secondary"><?php echo date("d/m/Y", strtotime($row['fecha'])); ?> | Por: <?php echo htmlspecialchars($row['usuario']); ?></small></p>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    } else {
        echo "<p>No hay noticias disponibles.</p>";
    }
    ?>

</main>

<?php include('../layout/footer.php'); ?>