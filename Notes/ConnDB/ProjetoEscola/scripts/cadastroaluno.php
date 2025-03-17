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
        echo "Usuario " . $alunonome . " | Inserido ao DB com ID: " . $alunocodigo;
    } else {
        echo "deu errado" . mysql_error();
    }
}

    if (isset($_POST['excluiraluno'])) {
    $cursocodigo = $_POST['alunocodigo'];

    $sql = "DELETE * FROM aluno WHERE codigo = $cursocodigo;"; 

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "ALUNO EXCLUIDO: " . $cursonome;
    } else {
        echo "deu errado" . mysql_error();
    }

}

if (isset($_POST['alteraraluno'])) {

    $alunocodigo = $_POST['alunocodigo'];
    $alunonome = $_POST['alunonome'];
    $codigocursoaluno = $_POST['codigocursoaluno'];
    $telefonealuno = $_POST['telefonealuno'];

    $sql = "UPDATE aluno SET 
    nome = '$alunonome', telefone = '$telefonealuno', codcurso = '$codigocursoaluno'
    WHERE codigo = $alunocodigo";
    

    $resultado = mysql_query($sql);

        
    if ($resultado == TRUE) {
        echo "Usuario " . $alunonome . " | Alterado ao DB com ID: " . $alunocodigo;
    } else {
        echo "deu errado" . mysql_error();
    }
}

if (isset($_POST['excluiraluno'])) {
    $alunocodigo = $_POST['alunocodigo'];


    $sql = "DELETE FROM aluno WHERE codigo = '$alunocodigo';";

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "aluno DELETADO: " . $alunocodigo;
    } else {
        echo "deu errado" . mysql_error();
    }

}


if (isset($_POST['pesquisaraluno'])) {
    $sql = "SELECT * FROM aluno";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '<div class="container mt-4">';
        echo '<h2>Lista de Alunos</h2>';
        echo '<table class="table table-striped">';
        echo '<thead class="table-dark"><tr><th>Codigo</th><th>Nome</th><th>Curso</th><th>Telefone</th></tr></thead>';
        echo '<tbody>';

        while ($linha = mysql_fetch_assoc($resultado)) {
            echo '<tr>';
            echo '<td>' . $linha['codigo'] . '</td>';
            echo '<td>' . $linha['nome'] . '</td>';
            echo '<td>' . $linha['codcurso'] . '</td>';
            echo '<td>' . $linha['telefone'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table></div>';
    } else {
        echo '<div class="alert alert-danger">Erro: ' . mysql_error() . '</div>';
    }
}


?>