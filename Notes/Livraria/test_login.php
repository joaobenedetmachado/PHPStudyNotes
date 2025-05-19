<?php
// Teste de hash MD5 para senha
$senha = "admin123";
$hash = md5($senha);

echo "Senha original: $senha<br>";
echo "Hash MD5: $hash<br>";
echo "Este hash deve ser igual a: 0192023a7bbd73250516f069df18b500<br>";
echo "Verificação: " . ($hash === "0192023a7bbd73250516f069df18b500" ? "CORRETO" : "INCORRETO") . "<br>";

// Simular verificação de senha
$senha_informada = "admin123";
$senha_armazenada = "0192023a7bbd73250516f069df18b500"; // MD5 de "admin123"

$verificacao = md5($senha_informada) === $senha_armazenada;
echo "Verificação de senha: " . ($verificacao ? "SUCESSO" : "FALHA") . "<br>";
?> 