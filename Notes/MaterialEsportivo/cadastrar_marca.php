<?php 
$connect = mysql_connect("localhost", "root" ,"");
$banco = mysql_select_db("loja");

if(isset($_POST['enviar'])) {
    $codigo = $_POST['codigomarca'];
    $nome = $_POST['nomemarca'];

    $sql = "INSERT INTO marca (codigo, nome) VALUES ('$codigo', '$nome')";
    mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        echo "<script>alert('Cadastro atualizado com sucesso!'); window.location='cadastro.html';</script>";
    } else {
        echo "<script>alert('Não foi possível atualizar o cadastro." . mysql_error() ."'); window.location='cadastro.html';</script>";
    }
}

if(isset($_POST['editar'])) {
    $codigo = $_POST['codigomarca'];
    $novo_nome = $_POST['nomemarca'];

    $sql = "UPDATE marca SET nome = '$novo_nome' WHERE codigo = '$codigo'";
    mysql_query($sql);
    
    if (mysql_affected_rows() > 0) {
        echo "<script>alert('Edição atualizado com sucesso!'); window.location='cadastro.html';</script>";
    } else {
        echo "<script>alert('Não foi possível atualizar o campo." . mysql_error() ."'); window.location='cadastro.html';</script>";
    }
} 

if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigomarca'];

    $sql = "DELETE FROM marca WHERE codigo = $codigo";
    mysql_query($sql);

    if (mysql_affected_rows() > 0) {
        echo "<script>alert('Cadastro excluído com sucesso!'); window.location='cadastro.html';</script>";
    } else {        
        echo "<script>alert('Não foi possível excluir o cadastro.'); window.location='cadastro.html';</script>";
    }
}


?>