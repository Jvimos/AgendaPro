<?php

function guardarMensaje($tipo, $texto)
{
    $_SESSION["mensaje"] = [
        "tipo" => $tipo,
        "texto" => $texto
    ];
}

function mostrarMensaje()
{
    if (!isset($_SESSION["mensaje"])) {
        return;
    }

    $mensaje = $_SESSION["mensaje"];
    unset($_SESSION["mensaje"]);

    $clase = $mensaje["tipo"] === "exito"
        ? "mensaje-exito"
        : "mensaje-error";

    echo '<div class="' . $clase . '" role="alert">';
    echo htmlspecialchars(
        $mensaje["texto"],
        ENT_QUOTES,
        "UTF-8"
    );
    echo '</div>';
}