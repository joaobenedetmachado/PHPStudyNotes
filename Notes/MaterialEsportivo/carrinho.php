<?php
session_start();
header('Content-Type: application/json');

// Incluir a biblioteca Stripe
require_once('stripe-php/init.php');

// Configurar a chave secreta da Stripe
$stripe_secret_key = 'sk_test_51NzQwqSDn30SRrLFYBaEmNxqQhRcmJYlBmf4P2GFVTtk9hbLVJjbNyC7yIl7dLMrOlVRp2TlJ7HlVAoddxQ5aUFs00S71QCYkTsk_test_51QlexyFZ60R1TyV82hYpovkknsd5bJdXumoXMriE1h5mgxpCbh0AqQbtkKmzmxkXL0OgZK4CgG2ZoS9HWhqRq4WT00kszZK83B';
\Stripe\Stripe::setApiKey($stripe_secret_key);

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = array();
}

$connect = mysql_connect("localhost", "root", "");
$banco = mysql_select_db("loja");

if (!$connect) {
    echo json_encode(array('success' => false, 'error' => 'Erro na conexão com o MySQL: ' . mysql_error()));
    exit;
}

if (!$banco) {
    echo json_encode(array('success' => false, 'error' => 'Erro ao selecionar o banco de dados: ' . mysql_error()));
    exit;
}

$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

switch ($acao) {
    case 'adicionar':
        if (isset($_POST['id_produto'])) {
            $id_produto = (int)$_POST['id_produto'];
            
            $query = "SELECT * FROM produto WHERE codigo = $id_produto";
            $result = mysql_query($query);
            
            if (!$result) {
                echo json_encode(array('success' => false, 'error' => 'Erro ao buscar produto: ' . mysql_error()));
                exit;
            }
            
            if (mysql_num_rows($result) > 0) {
                // add ao carrinho
                if (!in_array($id_produto, $_SESSION['carrinho'])) {
                    $_SESSION['carrinho'][] = $id_produto;
                }
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'error' => 'Produto não encontrado'));
            }
        } else {
            echo json_encode(array('success' => false, 'error' => 'ID do produto não informado'));
        }
        break;
        
    case 'remover':
        if (isset($_POST['id_produto'])) {
            $id_produto = (int)$_POST['id_produto'];
            
            $key = array_search($id_produto, $_SESSION['carrinho']);
            if ($key !== false) {
                unset($_SESSION['carrinho'][$key]);
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'error' => 'Produto não encontrado no carrinho'));
            }
        } else {
            echo json_encode(array('success' => false, 'error' => 'ID do produto não informado'));
        }
        break;
        
    case 'listar':
        if (!empty($_SESSION['carrinho'])) {
            $ids = implode(',', $_SESSION['carrinho']);
            $query = "SELECT * FROM produto WHERE codigo IN ($ids)";
            $result = mysql_query($query);
            
            if (!$result) {
                echo json_encode(array('success' => false, 'error' => 'Erro ao buscar produtos: ' . mysql_error()));
                exit;
            }
            
            $produtos = array();
            while ($row = mysql_fetch_assoc($result)) {
                $produtos[] = $row;
            }
            
            echo json_encode(array('success' => true, 'produtos' => $produtos));
        } else {
            echo json_encode(array('success' => true, 'produtos' => array()));
        }
        break;
        
    case 'pagar':
        // Verificar se existem itens no carrinho
        if (empty($_SESSION['carrinho'])) {
            echo json_encode(array('success' => false, 'error' => 'Carrinho vazio'));
            exit;
        }
        
        try {
            // Buscar os produtos do carrinho
            $ids = implode(',', $_SESSION['carrinho']);
            $query = "SELECT * FROM produto WHERE codigo IN ($ids)";
            $result = mysql_query($query);
            
            if (!$result) {
                echo json_encode(array('success' => false, 'error' => 'Erro ao buscar produtos: ' . mysql_error()));
                exit;
            }
            
            // Preparar os itens para o checkout
            $line_items = array();
            $total = 0;
            
            while ($produto = mysql_fetch_assoc($result)) {
                // Converter o preço para centavos (Stripe trabalha com a menor unidade monetária)
                $preco_centavos = round($produto['preco'] * 100);
                $total += $produto['preco'];
                
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'brl',
                        'product_data' => [
                            'name' => $produto['descricao'],
                            'images' => ['https://' . $_SERVER['HTTP_HOST'] . '/fotos/' . $produto['foto1']],
                        ],
                        'unit_amount' => $preco_centavos,
                    ],
                    'quantity' => 1,
                ];
            }
            
            // Obter o domínio do site para os URLs de redirecionamento
            $domain = 'http://' . $_SERVER['HTTP_HOST'];
            
            // Criar a sessão de checkout
            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => $domain . '/sucesso.php?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $domain . '/cancelado.php',
                'metadata' => [
                    'produtos_ids' => $ids,
                    'total' => $total
                ]
            ]);
            
            // Retornar o ID da sessão para o frontend
            echo json_encode([
                'success' => true,
                'id' => $checkout_session->id
            ]);
            
            // Opcional: Registrar o pedido no banco de dados
            $usuario_id = $_SESSION['usuario_id'];
            $session_id = $checkout_session->id;
            $data_atual = date('Y-m-d H:i:s');
            
            $query_pedido = "INSERT INTO pedidos (usuario_id, valor_total, session_id, status, data_atualizacao) 
                            VALUES ('$usuario_id', $total, '$session_id', 'pendente', '$data_atual')";
            mysql_query($query_pedido);
            
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'error' => $e->getMessage()));
        }
        break;
        
    default:
        echo json_encode(array('success' => false, 'error' => 'Ação não reconhecida'));
}
?>