<?php
// Iniciar sessão
session_start();

// func para sanitizar inputs
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// func para verificar se usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// func para verificar se usuário é admin
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

// func para redirecionar
function redirect($url) {
    header("Location: $url");
    exit();
}

// func para buscar livros
function getLivros($filtro = "", $valor = "") {
    global $conn;
    
    $sql = "SELECT l.*, a.nome as autor, e.nome as editora, c.nome as categoria 
            FROM livros l
            LEFT JOIN autores a ON l.id_autor = a.id
            LEFT JOIN editoras e ON l.id_editora = e.id
            LEFT JOIN categorias c ON l.id_categoria = c.id";
    
    if (!empty($filtro) && !empty($valor)) {
        if ($filtro == "titulo") {
            $sql .= " WHERE l.titulo LIKE '%" . sanitize($valor) . "%'";
        } else if ($filtro == "autor") {
            $sql .= " WHERE a.nome LIKE '%" . sanitize($valor) . "%'";
        } else if ($filtro == "editora") {
            $sql .= " WHERE e.nome LIKE '%" . sanitize($valor) . "%'";
        } else if ($filtro == "categoria") {
            $sql .= " WHERE c.nome LIKE '%" . sanitize($valor) . "%'";
        }
    }
    
    $sql .= " ORDER BY l.titulo";
    
    $result = mysqli_query($conn, $sql);
    $livros = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $livros[] = $row;
        }
    }
    
    return $livros;
}

// func para buscar um livro pelo ID
function getLivroPorId($id) {
    global $conn;
    
    $id = sanitize($id);
    $sql = "SELECT l.*, a.nome as autor, e.nome as editora, c.nome as categoria 
            FROM livros l
            LEFT JOIN autores a ON l.id_autor = a.id
            LEFT JOIN editoras e ON l.id_editora = e.id
            LEFT JOIN categorias c ON l.id_categoria = c.id
            WHERE l.id = $id";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

// func para adicionar ao carrinho
function adicionarAoCarrinho($id_livro, $quantidade = 1) {
    global $conn;
    
    if (!isLoggedIn()) {
        return false;
    }
    
    $id_usuario = $_SESSION['user_id'];
    $id_livro = sanitize($id_livro);
    $quantidade = (int)$quantidade;
    
    // Verificar se o usuário já tem um carrinho aberto
    $sql = "SELECT id FROM carrinhos WHERE id_usuario = $id_usuario AND status = 'aberto'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_carrinho = $row['id'];
    } else {
        // Criar um novo carrinho
        $sql = "INSERT INTO carrinhos (id_usuario) VALUES ($id_usuario)";
        mysqli_query($conn, $sql);
        $id_carrinho = mysqli_insert_id($conn);
    }
    
    // Buscar o preço atual do livro
    $sql = "SELECT preco FROM livros WHERE id = $id_livro";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $preco = $row['preco'];
    
    // Verificar se o item já está no carrinho
    $sql = "SELECT id, quantidade FROM itens_carrinho WHERE id_carrinho = $id_carrinho AND id_livro = $id_livro";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Atualizar a quantidade
        $row = mysqli_fetch_assoc($result);
        $nova_quantidade = $row['quantidade'] + $quantidade;
        $id_item = $row['id'];
        
        $sql = "UPDATE itens_carrinho SET quantidade = $nova_quantidade WHERE id = $id_item";
        return mysqli_query($conn, $sql);
    } else {
        // Inserir novo item no carrinho
        $sql = "INSERT INTO itens_carrinho (id_carrinho, id_livro, quantidade, preco_unitario) 
                VALUES ($id_carrinho, $id_livro, $quantidade, $preco)";
        return mysqli_query($conn, $sql);
    }
}

// func para obter os itens do carrinho
function getCarrinho() {
    global $conn;
    
    if (!isLoggedIn()) {
        return array();
    }
    
    $id_usuario = $_SESSION['user_id'];
    
    $sql = "SELECT c.id as id_carrinho, ic.id as id_item, l.id as id_livro, 
                   l.titulo, l.imagem, ic.quantidade, ic.preco_unitario,
                   (ic.quantidade * ic.preco_unitario) as subtotal
            FROM carrinhos c
            JOIN itens_carrinho ic ON c.id = ic.id_carrinho
            JOIN livros l ON ic.id_livro = l.id
            WHERE c.id_usuario = $id_usuario AND c.status = 'aberto'";
    
    $result = mysqli_query($conn, $sql);
    $itens = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $itens[] = $row;
        }
    }
    
    return $itens;
}
?> 