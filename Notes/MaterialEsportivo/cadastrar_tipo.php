<?php 
$connect = mysql_connect("localhost", "root" ,"");
$banco = mysql_select_db("loja");

if(isset($_POST['enviar'])) {
    $codigo = $_POST['codigotipo'];
    $nome = $_POST['nometipo'];

    $sql = "INSERT INTO tipo (codigo, nome) VALUES ('$codigo', '$nome')";
    mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        echo "<script>alert('Cadastro atualizado com sucesso!'); window.location='cadastro.html';</script>";
    } else {
        echo "<script>alert('Não foi possível atualizar o cadastro." . mysql_error() ."'); window.location='cadastro.html';</script>";
    }
}

if(isset($_POST['editar'])) {
    $codigo = $_POST['codigotipo'];
    $novo_nome = $_POST['nometipo'];

    $sql = "UPDATE tipo SET nome = '$novo_nome' WHERE codigo = '$codigo'";
    mysql_query($sql);
    
    if (mysql_affected_rows() > 0) {
        echo "<script>alert('Cadastro atualizado com sucesso!'); window.location='cadastro_tipo.php';</script>";
    } else {
        echo "<script>alert('Não foi possível atualizar o cadastro.'); window.location='cadastro_tipo.php';</script>";
    }
} 

if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigotipo'];

    $sql = "DELETE FROM tipo WHERE codigo = '$codigo'";
    mysql_query($sql);

    if (mysql_affected_rows() > 0) {
        echo "<script>alert('Cadastro excluído com sucesso!'); window.location='cadastro.html';</script>";
    } else {        
        echo "<script>alert('Não foi possível excluir o cadastro.'); window.location='cadastro.html';</script>";
    }
}


?>