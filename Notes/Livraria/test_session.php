<?php
// Teste de inicialização de sessão compatível com PHP 5.3

// Verifica se a sessão já está iniciada
if (!isset($_SESSION)) {
    echo "Sessão não iniciada. Iniciando agora...<br>";
    session_start();
} else {
    echo "Sessão já estava iniciada.<br>";
}

// Define uma variável de sessão para teste
$_SESSION['test'] = "Funcionando!";
echo "Variável de sessão definida: " . $_SESSION['test'] . "<br>";

// Exibe todas as variáveis de sessão ativas
echo "Variáveis de sessão ativas: <pre>";
print_r($_SESSION);
echo "</pre>";

echo "Teste de sessão concluído!";
?> 