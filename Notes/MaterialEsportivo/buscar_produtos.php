<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    die(json_encode(['error' => 'Usuário não autenticado']));
}

$connect = mysql_connect("localhost", "root", "");
$banco = mysql_select_db("loja");

if (!$connect || !$banco) {
    die(json_encode(['error' => 'Erro de conexão com o banco de dados']));
}

$termo = isset($_GET['termo']) ? mysql_real_escape_string($_GET['termo']) : '';
$categoria = isset($_GET['categoria']) ? mysql_real_escape_string($_GET['categoria']) : '';
$marca = isset($_GET['marca']) ? mysql_real_escape_string($_GET['marca']) : '';

$query = "SELECT p.*, c.nome as categoria_nome, m.nome as marca_nome 
          FROM produto p
          JOIN categoria c ON p.codcategoria = c.codigo
          JOIN marca m ON p.codmarca = m.codigo
          WHERE 1=1";

if (!empty($termo)) {
    $query .= " AND (p.descricao LIKE '%$termo%' OR m.nome LIKE '%$termo%')";
}

if (!empty($categoria)) {
    $query .= " AND c.nome = '$categoria'";
}

if (!empty($marca)) {
    $query .= " AND m.nome = '$marca'";
}

$result = mysql_query($query);

if (!$result) {
    die(json_encode(['error' => 'Erro na consulta: ' . mysql_error()]));
}

$produtos = array();
while ($row = mysql_fetch_assoc($result)) {
    $produtos[] = [
        'id' => $row['codigo'],
        'descricao' => $row['descricao'],
        'preco' => number_format($row['preco'], 2, ',', '.'),
        'foto' => $row['foto1'],
        'categoria' => $row['categoria_nome'],
        'marca' => $row['marca_nome']
    ];
}

echo json_encode(['produtos' => $produtos]);
?> 