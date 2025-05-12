<?php
// Configurações do banco de dados
$host = "localhost";
$user = "root";
$password = "";
$database = "livraria";

// Criar conexão
$conn = mysqli_connect($host, $user, $password, $database);

// Checar conexão
if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Configurar charset
mysqli_set_charset($conn, "utf8");
?> 