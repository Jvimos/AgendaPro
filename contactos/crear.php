<?php
/**
 * Formulario para Crear Nuevo Contacto
 * Incluye Categorías (Opción C) y Favoritos (Opción D)
 */

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

// Obtener las categorías de la base de datos (Opción C)
$stmt = $conexion->query("SELECT id, nombre FROM categorias ORDER BY id ASC");
$categorias = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo contacto | AgendaPro</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>

    <header class="navbar">
        <div class="contenedor navbar-contenido">
            <a href="listar.php" class="navbar-logo">
                Agenda<span>Pro</span>
            </a>

            <div class="navbar-usuario">
                <span>
                    <?= htmlspecialchars($_SESSION["usuario_nombre"], ENT_QUOTES, "UTF-8") ?>
                    <span style="display: block; font-size: 12px; font-weight: normal; color: var(--gris-texto);">
                        <?= htmlspecialchars($_SESSION["usuario_correo"] ?? "", ENT_QUOTES, "UTF-8") ?>
                    </span>
                </span>
                <a href="../auth/logout.php">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="contenedor dashboard">

        <section class="formulario-card">

            <div class="formulario-cabecera">
                <h1>Nuevo contacto</h1>
                <p>Agrega una persona a tu agenda personal con categoría y favoritos.</p>
            </div>

            <?php mostrarMensaje(); ?> 
            
            <form action="guardar.php" method="POST">

                <div class="form-grupo">
                    <label for="nombre">Nombre completo *</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Ej. Juan Pérez"
                        maxlength="150"
                        required
                    >
                </div>

                <!-- Selector de Categoría (Opción C) -->
                <div class="form-grupo">
                    <label for="id_categoria">🏷️ Categoría *</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">-- Selecciona una categoría --</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" <?= ($cat['nombre'] === 'Otro') ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre'], ENT_QUOTES, "UTF-8") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="telefono">Teléfono *</label>
                    <input
                        type="tel"
                        id="telefono"
                        name="telefono"
                        placeholder="Ej. 0991234567"
                        maxlength="30"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="correo">Correo electrónico *</label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="Ej. juan@correo.com"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="ciudad">Ciudad *</label>
                    <input
                        type="text"
                        id="ciudad"
                        name="ciudad"
                        placeholder="Ej. Macas"
                        maxlength="100"
                        required
                    >
                </div>

                <!-- Checkbox de Favorito (Opción D) -->
                <div class="form-grupo form-grupo-checkbox">
                    <label class="checkbox-contenedor">
                        <input type="checkbox" id="es_favorito" name="es_favorito" value="1">
                        <span class="checkbox-etiqueta">
                            ⭐ Marcar como contacto favorito
                        </span>
                    </label>
                    <small style="color: var(--gris-texto); display: block; margin-top: 4px;">
                        Permite filtrar y acceder rápidamente a este contacto desde la vista principal.
                    </small>
                </div>

                <div class="formulario-acciones">
                    <a href="listar.php" class="btn btn-secundario">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-principal">
                        Guardar contacto
                    </button>
                </div>

            </form>

        </section>

    </main>

</body>
</html>
