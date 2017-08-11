<?php

#session_set_cookie_params(1800);
session_start();
$configSistema["nombre"] = "sisec";
ob_start();
require_once "config/config.global.php";
require_once "db/dbPdo.php";
require_once "application/views/mensajes/mensajes.phtml";
require_once "application/views/layouts/sb-admin-1.0.4/index.phtml";
#require_once "application/views/layouts/default/default.php";

ob_end_flush();
