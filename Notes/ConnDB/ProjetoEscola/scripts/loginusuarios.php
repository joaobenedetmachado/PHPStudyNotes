<?php 
$connect = mysql_connect("localhost", "root" ,"");
$banco = mysql_select_db("escola");

if (isset($_POST['logincurso'])) {

    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $sql = "SELECT id FROM usuarios WHERE login = '$login' and senha = '$senha';";

    $resultado = mysql_query($sql);
    
    if ($resultado == TRUE) {
        echo "deu certo, usuario encontrado " . $resultado;
        header("location:../home.html");
        setcookie("login",$login)
        exit(); 
    } else {
        echo "<script language='javascript'>alert("deu errado filho da puta $mysql_error()" )</script>";
    }
}

?>