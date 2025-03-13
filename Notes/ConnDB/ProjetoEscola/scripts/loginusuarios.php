<?php 
$connect = mysql_connect("localhost", "root" ,"");
$banco = mysql_select_db("escola");

if (isset($_POST['logincurso'])) {

    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $sql = "SELECT id FROM usuarios WHERE login = '$login' and senha = '$senha';";

    $resultado = mysql_query($sql);
    
    
    if (mysql_num_rows($resultado) > 0) {
        header("location:../menu.html");
        exit(); 
    } else {
        echo "deu errado";
    }
}

?>