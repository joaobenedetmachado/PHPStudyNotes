<?php
session_start();
require_once('stripe-php/init.php');

// Configurar a chave secreta da Stripe
$stripe_secret_key = 'sk_test_51QlexyFZ60R1TyV82hYpovkknsd5bJdXumoXMriE1h5mgxpCbh0AqQbtkKmzmxkXL0OgZK4CgG2ZoS9HWhqRq4WT00kszZK83B';
\Stripe\Stripe::setApiKey($stripe_secret_key);

$session_id = $_GET['session_id'] ?? '';

// Verificar se o ID da sessão foi fornecido
if (empty($session_id)) {
    header("Location: home.php");
    exit();
}

try {
    // Recuperar informações da sessão
    $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);
    
    // Conectar ao banco de dados
    $connect = mysql_connect("localhost", "root", "");
    $banco = mysql_select_db("loja");

    if (!$connect || !$banco) {
        die("Erro na conexão com o banco de dados");
    }
    
    // Atualizar o status do pedido no banco de dados
    $data_atual = date('Y-m-d H:i:s');
    $query = "UPDATE pedidos SET status = 'pago', data_atualizacao = '$data_atual' WHERE session_id = '$session_id'";
    mysql_query($query);
    
    // Limpar o carrinho após o pagamento bem-sucedido
    $_SESSION['carrinho'] = array();
    
} catch (Exception $e) {
    // Lidar com erros (registrar em log, etc.)
    error_log("Erro ao processar o pagamento: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Concluído - SportStyle Pro</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .success-icon {
            font-size: 80px;
            color: #2ecc71;
            margin-bottom: 20px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-home {
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-home:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Pagamento Concluído com Sucesso!</h1>
        <p>Obrigado por sua compra. O seu pedido foi confirmado e está sendo processado.</p>
        <p>Você receberá uma confirmação por e-mail em breve.</p>
        <a href="home.php" class="btn-home">Voltar para a Loja</a>
    </div>
</body>
</html> 