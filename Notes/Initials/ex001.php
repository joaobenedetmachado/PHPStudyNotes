<?php 
//capturar os dados do frontEnd
$nome = $_POST['name'];
$email = $_POST['email'];
$mensagem = $_POST['mensagem'];

//mostrar na tela
echo "Nome: " . $nome . " Email: " . $email . " Mensagem: " . $mensagem;
?>