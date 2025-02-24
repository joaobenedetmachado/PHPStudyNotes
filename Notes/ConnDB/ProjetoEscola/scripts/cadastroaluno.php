<?php 
$connect = mysql_connect("localhost", "root" ,"");
$banco = mysql_select_db("escola");

if (isset($_POST['cadastraraluno'])) {

    $alunocodigo = $_POST['alunocodigo'];
    $alunonome = $_POST['alunonome'];
    $codigocursoaluno = $_POST['codigocursoaluno'];
    $telefonealuno = $_POST['telefonealuno'];

    $sql = "INSERT INTO aluno (codigo, nome, codcurso, telefone) 
            VALUES ('$alunocodigo', '$alunonome', '$codigocursoaluno', '$telefonealuno')";

    $resultado = mysql_query($sql);
    
    if ($resultado == TRUE) {
        echo "deu certo";
    } else {
        echo "deu errado" . mysql_error();
    }



}
?>