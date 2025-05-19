<?php
require_once 'functions.php';
require_once 'database.php';

// Iniciar a sessão (se não foi iniciada)
// No PHP 5.3 não existe session_status(), então usamos uma abordagem diferente
if (!isset($_SESSION)) {
    session_start();
}

// Limpar todas as variáveis de sessão
$_SESSION = array();

// Destruir a sessão
session_destroy();

// Redirecionar para a página inicial
header("Location: index.php");
exit();
?> 