<?php
// Incluir os arquivos necessários
require_once 'database.php';
require_once 'functions.php';

// Testar a conexão com o banco
if ($conn) {
    echo "Conexão com o banco de dados estabelecida com sucesso! <br>";
} else {
    echo "Erro de conexão: " . mysqli_connect_error() . "<br>";
}

// Testar a função getLivros
try {
    $livros = getLivros();
    echo "Função getLivros executada com sucesso! <br>";
    echo "Total de livros: " . count($livros) . "<br>";
} catch (Exception $e) {
    echo "Erro ao executar getLivros: " . $e->getMessage() . "<br>";
}

echo "Teste concluído!";
?> 