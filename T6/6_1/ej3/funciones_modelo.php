<?php

/*
 * Autor = Diego Rodríguez Suárez-Bustillo
 * Fecha = 30-ene-2015
 * Licencia = gpl30 
 * Version = 1.0
 * Descripcion = 
 */

/*
 * Copyright (C) 2015 Diego Rodríguez Suárez-Bustillo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function conexion() {
    global $mensajeAbrirConexion;
    $conexion = @mysqli_connect(SERVIDOR, USUARIO, PASSWORD);
    $errorNo = mysqli_connect_errno();
    $errorMsg = mysqli_connect_error();
    if ($errorNo == 0) {
        $mensajeAbrirConexion = "<h2>Conexión establecida con el servidor</h2>";
    } else {
        $mensajeAbrirConexion = "<h2>No se ha podido establecer la conexión con el servidor. Se ha producido un error nº $errorNo que corresponde a: $errorMsg</h2>";
    }
    return $conexion;
}

function crearBD() {
    $operacion = true;
    global $conexion, $mensajeBD;
    $consulta = "SELECT COUNT(*) as existe_bd FROM" .
            "INFORMATION_SCHEMA.schemata" .
            "WHERE schema_name='" . BD . "'";
    $resultado = mysqli_query($conexion, $consulta);
    $valor = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
    if (!$valor['existe_bd']) {
        //si la base de datos no existe la creamos y escribimos el mensaje de exito
        $consulta = "CREATE DATABASE IF NOT EXISTS " . BD;
        mysqli_query($conexion, $consulta);
        $errorNo = mysqli_errno($conexion);
        $errorMsg = mysqli_error($conexion);
        if ($errorNo == 0) {
            $mensajeBD .= "<h2>Base de datos creada correctamente.</h2>";
        } else {
            $operacion = false;
            $mensajeBD .= "<h2>Error al crear la base de datos.Se ha producido un error nº $errorNo que corresponde a: $errorMsg </h2>";
        }
    } else {
        //si existe, avisamos de su existencia y evitamos intentar crearla
        $mensajeBD .= "<h2> La base de datos ya existe</h2>";
    }
    return($operacion);
}

function crearTabla($tabla, $query) {
    $operacion = true;
    global $conexion;
    global $mensajeTabla;
    //comprobamos si existe la tabla
    $consulta = "SELECT COUNT(*) as existe_tabla FROM" .
            "INFORMATION_SCHEMA.tables" .
            "WHERE table_schema='" . BD . "' and table_name='" . $tabla . "'";
    $resultado = mysqli_query($conexion, $consulta);
    $valor = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
    if (!$valor['existe_tabla']) {
        //si la tabla no existe la creamos y escribimos el mensaje de exito
        @mysqli_query($conexion, $query);
        $errorNo = mysqli_errno($conexion);
        $errorMsg = mysqli_error($conexion);
        if ($errorNo == 0) {
            $mensajeTabla .= "<h2>Tabla $tabla creada correctamente.</h2>";
        } else {
            $operacion = false;
            $mensajeTabla .= "<h2>Error al crear la tabla $tabla. Se ha producido un error nº $errorNo que corresponde a: $errorMsg</h2>";
        }

    } else {
        //si existe, avisamos de su existencia y evitamos intentar crearla
        $mensajeTabla .= "<h2> La tabla $tabla ya existe</h2>";
    }
    return($operacion);
}

function cerrarConexion() {
    global $conexion, $mensajeCerrarConexion;
    $operacion = true;
    if (@mysqli_close($conexion)) {
        $mensajeCerrarConexion .= "<h2> Conexión cerrada con exito</h2>";
    } else {
        $mensajeCerrarConexion .= "<h2> No se ha podido cerrar la conexión</h2>";
    }
}

function insertar($query) {
    global $conexion;
    global $mensajeInsertar;
    //query devuelve false si la insercion falla
    $resultado = @mysqli_query($conexion, $query);
//    $errorNo = mysqli_connect_errno();
//    $errorMsg = mysqli_connect_error();
    $errorNo = mysqli_errno($conexion);
    $errorMsg = mysqli_error($conexion);
    if ($resultado) {
        $mensajeInsertar .= "<h2>Datos almacenados correctamente: [$errorNo - $errorMsg]</h2>";
    } else {
        $mensajeInsertar .= "<h2>Se ha producido un error nº $errorNo que corresponde a: $errorMsg </h2>";
    }
}
