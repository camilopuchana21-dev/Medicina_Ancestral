<?php
// pages/planta-detalle.php
$titulo = 'Detalle de Planta';
require_once '../config/supabase.php';
include '../includes/header.php';

$id = $_GET['id'] ?? 1;

$planta = $supabase
    ->from('Plantas')
    ->select('*')
    ->eq('id', $id)
    ->execute();

$planta = $planta[0] ?? null;

if (!$planta) {
    echo "<div class='error'>Planta no encontrada</div>";
    include '../includes/footer.php';
    exit;
}

$propiedades = obtenerPropiedadesPlanta($id);
$usos = obtenerUsosPlanta($id);
?>

<div class="planta-detalle-page">
    <div class="planta-detalle-container">
        <div class="planta-detalle-grid">
            <div class="planta-detalle-imagen">
                <img src="<?php echo $planta['imagen_principal'] ?? 'https://images.unsplash.com/photo-1589917991891-3d3f9b3b7e5a'; ?>" alt="<?php echo $planta['nombre_comun']; ?>">
            </div>
            
            <div class="planta-detalle-info">
                <h1><?php echo $planta['nombre_comun']; ?></h1>
                <p class="scientific-name"><?php echo $planta['nombre_cientifico']; ?></p>
                
                <div class="rating">
                    <?php 
                    $rating = round($planta['rating'] ?? 4.5);
                    for($i = 1; $i <= 5; $i++) {
                        if($i <= $rating) {
                            echo '<i class="fas fa-star"></i>';
                        } elseif($i - 0.5 <= $rating) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    ?>
                    <span><?php echo $planta['rating'] ?? '4.5'; ?> (120 reseñas)</span>
                </div>

                <div class="planta-detalle-tags">
                    <?php foreach($propiedades as $prop): ?>
                        <span class="tag"><?php echo $prop; ?></span>
                    <?php endforeach; ?>
                </div>

                <div class="planta-detalle-descripcion">
                    <h3>🌿 Descripción</h3>
                    <p><?php echo $planta['descripcion_general'] ?? 'Descripción no disponible'; ?></p>
                </div>

                <?php if(!empty($usos)): ?>
                <div class="planta-detalle-usos">
                    <h3>💚 Usos tradicionales</h3>
                    <ul>
                        <?php foreach($usos as $uso): ?>
                            <li><i class="fas fa-check-circle"></i> <?php echo $uso['nombre']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>