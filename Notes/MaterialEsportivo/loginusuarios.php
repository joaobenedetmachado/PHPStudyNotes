<?php
session_start();

$connect = mysql_connect("localhost", "root", "");
$banco = mysql_select_db("loja");

if (!$connect) {
    die("Erro na conexão com o MySQL: " . mysql_error());
}

if (!$banco) {
    die("Erro ao selecionar o banco de dados: " . mysql_error());
}

if (isset($_POST['logincurso'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT id, senha, tipo FROM usuario WHERE email = '$email'";
    $resultado = mysql_query($sql);

    if (mysql_num_rows($resultado) > 0) {
        $usuario = mysql_fetch_assoc($resultado);
        
        if ($senha === $usuario['senha']) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            header("Location: home.php");
            exit();
        } else {
            $_SESSION['erro'] = "Email ou senha incorretos";
            header("Location: login.html");
            exit();
        }
    } else {
        $_SESSION['erro'] = "Email ou senha incorretos";
        header("Location: login.html");
        exit();
    }
}
?>