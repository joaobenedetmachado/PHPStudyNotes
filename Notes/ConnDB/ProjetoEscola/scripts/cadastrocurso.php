<?php 
$connect = mysql_connect("localhost", "root" ,"");
$banco = mysql_select_db("escola");

if (isset($_POST['cadastrarcurso'])) {

    $cursocodigo = $_POST['cursocodigo'];
    $cursonome = $_POST['cursonome'];
    $cursocoordenador = $_POST['cursocoordenador'];

    $sql = "INSERT INTO curso (codigo, nome, codcoordenador) 
            VALUES ('$cursocodigo', '$cursonome', '$cursocoordenador')";

    $resultado = mysql_query($sql);
    
    if ($resultado == TRUE) {
        echo "Curso: " . $cursonome . " | Inserido ao DB com ID: " . $cursocodigo . " | Cordenador Codigo: " . $cursocoordenador;
    } else {
        echo "deu errado" . mysql_error();
    }
}

if (isset($_POST['alterarcurso'])) {
    $cursocodigo = $_POST['cursocodigo'];
    $cursonome = $_POST['cursonome'];
    $cursocoordenador = $_POST['cursocoordenador'];

    $sql = "UPDATE curso 
    SET nome = '$cursonome', codcoordenador = '$cursocoordenador' 
    WHERE codigo = '$cursocodigo';";

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "Curso ALTERADO: " . $cursonome . " | Inserido ao DB com ID: " . $cursocodigo . " | Cordenador Codigo: " . $cursocoordenador;
    } else {
        echo "deu errado" . mysql_error();
    }

}

if (isset($_POST['excluircurso'])) {
    $cursocodigo = $_POST['cursocodigo'];

    $sql = "DELETE FROM curso WHERE codigo = '$cursocodigo';"; 

    $resultado = mysql_query($sql);

    if ($resultado == TRUE) {
        echo "Curso EXCLUIDO: " . $cursonome;
    } else {
        echo "deu errado" . mysql_error();
    }

}

if (isset($_POST['pesquisarcurso'])) {
    $sql = "SELECT * FROM curso";
    $resultado = mysql_query($sql);

    if ($resultado) {
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '<div class="container mt-4">';
        echo '<h2>Lista de Cursos</h2>';
        echo '<table class="table table-striped">';
        echo '<thead class="table-dark"><tr><th>Código</th><th>Nome</th><th>Coordenador</th></tr></thead>';
        echo '<tbody>';

        while ($linha = mysql_fetch_assoc($resultado)) {
            echo '<tr>';
            echo '<td>' . $linha['codigo'] . '</td>';
            echo '<td>' . $linha['nome'] . '</td>';
            echo '<td>' . $linha['codcoordenador'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table></div>';
    } else {
        echo '<div class="alert alert-danger">Erro: ' . mysql_error() . '</div>';
    }
}
?>