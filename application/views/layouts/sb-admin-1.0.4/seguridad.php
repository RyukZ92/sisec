<?php
$nivelDeUsuario = $_SESSION["nivel_usuario"];
/*
if ($nivelDeUsuario == "Administrador")
    $estiloConfig = "style='color:#454545; cursor:no-drop;'";
*/
if ($nivelDeUsuario != "Administrador")
    $estiloUsuario = "style='color:#454545; cursor:no-drop;'";
/*
if($nivelDeUsuario == "Administrador")
    $estiloEvaluacion = "style='color:#454545; cursor:no-drop;'";

if($nivelDeUsuario == "Administrador")
    $estiloSeguridad = "style='color:#454545; cursor:no-drop;'";

if($nivelDeUsuario == "Administrador")
    $estiloRendimiento = "style='color:#454545; cursor:no-drop;'";

if($nivelDeUsuario == "Administrador")
    $estiloExtra = "style='color:#454545; cursor:no-drop;'";

if($nivelDeUsuario != "Administrador" and $nivelDeUsuario != "Evaluador")
    $estiloResultado= "style='color:#454545; cursor:no-drop;'";
*/

if($nivelDeUsuario != "Administrador")
    $estiloHistorial = "style='color:#454545; cursor:no-drop;'";
/*
if($nivelDeUsuario != "Administrador")
    $estiloConfiguracion = "style='color:#454545; cursor:no-drop;'";*/
