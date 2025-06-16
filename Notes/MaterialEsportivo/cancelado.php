<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Cancelado - SportStyle Pro</title>
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

        .cancel-icon {
            font-size: 80px;
            color: #e74c3c;
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

        .btn-home, .btn-retry {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
            margin: 0 10px;
        }

        .btn-home {
            background-color: #3498db;
            color: white;
        }

        .btn-retry {
            background-color: #2ecc71;
            color: white;
        }

        .btn-home:hover {
            background-color: #2980b9;
        }

        .btn-retry:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <i class="fas fa-times-circle cancel-icon"></i>
        <h1>Pagamento Cancelado</h1>
        <p>O seu pagamento foi cancelado. Nenhum valor foi cobrado.</p>
        <p>Se houve algum problema ou se você precisar de ajuda, entre em contato com nosso suporte.</p>
        <div>
            <a href="home.php" class="btn-home">Voltar para a Loja</a>
            <a href="javascript:history.back()" class="btn-retry">Tentar Novamente</a>
        </div>
    </div>
</body>
</html> 