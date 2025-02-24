<?php 
$connect = mysql_connect("localhost", "root" ,"");
$banco = mysql_select_db("escola");

if (isset($_POST['cadastrarcurso'])) {

    $cursocodigo = $_POST['cursocodigo'];
    $cursonome = $_POST['cursonome'];
    $cursocoordenador = $_POST['cursocoordenador'];

    $sql = "INSERT INTO curso (codigo, nome, coordenador) 
            VALUES ('$cursocodigo', '$cursonome', '$cursocoordenador')";

    $resultado = mysql_query($sql);
    
    if ($resultado == TRUE) {
        echo "deu certo";
    } else {
        echo "deu errado" . mysql_error();
    }



}
?>