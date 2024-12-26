<?php require '../config/db.php'; ?>

<?php
// Obtener las 3 últimas noticias ordenadas por fecha
$query = "SELECT * FROM noticias ORDER BY fecha DESC LIMIT 3";
$result = $conn->query($query);
?>

<aside class="container col-md-4 col-12 new_sc">
    <h2>Últimas Noticias</h2>
    <div class="container mx-auto row c_n">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <figure class="figure col-md-12 col-4">
                    <img src="../img/noticias/<?php echo $row['imagen']; ?>" class="figure-img img-fluid img_n" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
                    <div class="t_c">
                        <figcaption class="figure-caption text-start t-c_1"><?php echo htmlspecialchars($row['titulo']); ?></figcaption>
                        <!-- Enlace a la noticia detallada en noticias.php con el ID de la noticia -->
                        <a href="../views/noticias.php?id=<?php echo $row['idNoticia']; ?>" class="btn_vm btn">Ver más</a>
                    </div>
                </figure>
        <?php
            }
        } else {
            echo "<p>No hay noticias disponibles.</p>";
        }
        ?>
    </div>
</aside>

<?php $conn->close(); ?>