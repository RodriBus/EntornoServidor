<?php

function procesForm() {
    global $enlace, $conexion;
    $camposObligatorios = array("nombre", "dni");
    $campospendientes = array();
    $camposerroneos = array();
    foreach ($camposObligatorios as $campoObligatorio) {
        if (!isset($_POST[$campoObligatorio]) or ! $_POST[$campoObligatorio]) {
            $campospendientes[] = $campoObligatorio;
        }
    }
    if (isset($_POST["nombre"]) && !empty($_POST["nombre"]) && !preg_match("/^[a-zA-Z][a-zA-Z ]+$/", $_POST["nombre"])) {
        $camposerroneos[] = "nombre";
    }
    if (isset($_POST["dni"]) && !empty($_POST["dni"]) && !preg_match("/^[0-9]{8}[a-zA-Z]$/", $_POST["dni"])) {
        $camposerroneos[] = "dni";
    }
    if ($campospendientes or $camposerroneos) {
        displayForm($camposerroneos, $campospendientes);
    } else {
//        $conexion = conexion();
        $valores_campos['nombre'] = $_POST['nombre'];
        $valores_campos['dni'] = $_POST['dni'];
        if ($_POST['salario'] == '') {
            $valores_campos['salario'] = null;
        } else {
            $valores_campos['salario'] = $_POST['salario'];
        }
        guardarUsuario($valores_campos, TABLA1);

        $valores_campos2['id'] = $_POST['nombre'];
        $valores_campos2['dnitrabajador'] = $_POST['dni'];
        if ($_POST['telf'] == '') {
            $valores_campos2['telefono'] = null;
        } else {
            $valores_campos2['telefono'] = (int)$_POST['telf'];
        }
        guardarUsuario($valores_campos2, TABLA2);
        cerrarConexion();
        $enlace = "<a href='index.php'>Ir al formulario de introducción de datos</a>";
        visualizarDatos();
    }
}

function guardarUsuario($valores_campos, $tabla) {
    global $conexion, $tipos;
    addTable($tabla);
    setFuncion("insert");
    foreach ($valores_campos as $campo => $valor) {
        addSelect($campo);
        addValue("?");
        addTipo($valor);
    }
    $sql_insertar = generar();
    ejecutar($sql_insertar, $valores_campos, $tabla);
}

function addTipo($campo) {
    global $tipos;
    switch (gettype($campo)) {
        case "integer":
            $tipos.="i";
            break;
        case "double":
            $tipos.="d";
            break;
        case "string":
            $tipos.="s";
            break;
    }
}
