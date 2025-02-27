<?php 
$connect = mysql_connect("localhost", "root" ,"");
$banco = mysql_select_db("escola");

if (isset($_POST['cadastrarcoordenador'])) {

    $coordenadorcodigo = $_POST['coordenadorcodigo'];
    $coordenadornome = $_POST['coordenadornome'];


    $sql = "INSERT INTO coordenador (codigo, nome) 
            VALUES ('$coordenadorcodigo', '$coordenadornome')";

    $resultado = mysql_query($sql);
    
    if ($resultado == TRUE) {
        echo "Usuario " . $coordenadornome . " | Inserido ao DB com ID: " . $coordenadorcodigo;
    } else {
        echo "Error> " . mysql_error();
    }
}
    if (isset($_POST['alterarcoordenador'])) {

        $coordenadorcodigo = $_POST['coordenadorcodigo'];
        $coordenadornome = $_POST['coordenadornome'];

    
        $sql = "UPDATE coordenador SET nome = '$coordenadornome' WHERE codigo = '$coordenadorcodigo'";

        
    
        $resultado = mysql_query($sql);
    
            
        if ($resultado == TRUE) {
            echo "Usuario ALTERADO " . $coordenadornome . " | Alterado ao DB com ID: " . $coordenadorcodigo;
        } else {
            echo "deu errado" . mysql_error();
        }
    }
    if (isset($_POST['excluircoordenador'])) {
        $coordenadorcodigo = $_POST['coordenadorcodigo'];
    
    
        $sql = "DELETE FROM coordenador WHERE codigo = '$coordenadorcodigo';";
    
        $resultado = mysql_query($sql);
    
        if ($resultado == TRUE) {
            echo "Coordenador DELETADO: " . $coordenadorcodigo;
        } else {
            echo "deu errado" . mysql_error();
        }
    
    }

    if (isset($_POST['pesquisarcoordenador'])) {
        $sql = "SELECT * FROM coordenador";
        $resultado = mysql_query($sql);
    
        if ($resultado) {
            echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
            echo '<div class="container mt-4">';
            echo '<h2>Lista de Alunos</h2>';
            echo '<table class="table table-striped">';
            echo '<thead class="table-dark"><tr><th>Codigo</th><th>Nome</th></thead>';
            echo '<tbody>';
    
            while ($linha = mysql_fetch_assoc($resultado)) {
                echo '<tr>';
                echo '<td>' . $linha['codigo'] . '</td>';
                echo '<td>' . $linha['nome'] . '</td>';
                echo '</tr>';
            }
    
            echo '</tbody></table></div>';
        } else {
            echo '<div class="alert alert-danger">Erro: ' . mysql_error() . '</div>';
        }
    }
    



?>