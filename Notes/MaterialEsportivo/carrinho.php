<?php
session_start();
header('Content-Type: application/json');

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
        
    default:
        echo json_encode(array('success' => false, 'error' => 'Ação não reconhecida'));
}
?>