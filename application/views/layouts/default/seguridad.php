<?php
$nivelDeUsuario = $_SESSION["nivel_usuario"];
if ($nivelDeUsuario != "Administrador")
    $estiloUsuario = "style='color:gray; cursor:no-drop; disabled:disabled;'";

if($nivelDeUsuario == "Administrador")
    $estiloEvaluacion = "style='color:gray; cursor:no-drop; pointer-events:none;'";

if($nivelDeUsuario == "Administrador")
    $estiloEvaluacion = "style='color:gray; cursor:no-drop; disabled:disabled'";

if($nivelDeUsuario == "Administrador")
    $estiloSeguridad = "style='color:gray; cursor:no-drop;'";

if($nivelDeUsuario == "Administrador")
    $estiloRendimiento = "style='color:gray; cursor:no-drop;'";

if($nivelDeUsuario == "Administrador")
    $estiloPruebas = "style='color:gray; cursor:no-drop;'";

if($nivelDeUsuario != "Administrador")
    $estiloHistorial = "style='color:gray; cursor:no-drop;'";
/*
if($nivelDeUsuario != "Administrador")
    $estiloConfiguracion = "style='color:gray; cursor:no-drop;'";*/
