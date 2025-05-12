<?php
require_once '../header.php';

if (!isAdmin()) {
    $_SESSION['error'] = "Acesso restrito a administradores.";
    redirect('../index.php');
}

$id = 0;
$titulo = '';
$id_autor = '';
$id_editora = '';
$id_categoria = '';
$preco = '';
$quantidade_estoque = '';
$ano_publicacao = '';
$isbn = '';
$descricao = '';
$imagem = '';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "SELECT * FROM livros WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $livro = mysqli_fetch_assoc($result);
        $titulo = $livro['titulo'];
        $id_autor = $livro['id_autor'];
        $id_editora = $livro['id_editora'];
        $id_categoria = $livro['id_categoria'];
        $preco = $livro['preco'];
        $quantidade_estoque = $livro['quantidade_estoque'];
        $ano_publicacao = $livro['ano_publicacao'];
        $isbn = $livro['isbn'];
        $descricao = $livro['descricao'];
        $imagem = $livro['imagem'];
    } else {
        $_SESSION['error'] = "Livro não encontrado.";
        redirect('livros.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = sanitize($_POST['titulo']);
    $id_autor = (int)$_POST['id_autor'];
    $id_editora = (int)$_POST['id_editora'];
    $id_categoria = (int)$_POST['id_categoria'];
    $preco = (float)str_replace(',', '.', $_POST['preco']);
    $quantidade_estoque = (int)$_POST['quantidade_estoque'];
    $ano_publicacao = (int)$_POST['ano_publicacao'];
    $isbn = sanitize($_POST['isbn']);
    $descricao = sanitize($_POST['descricao']);
    
    $imagem_atual = $imagem;
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $nome_arquivo = $_FILES['imagem']['name'];
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $extensoes_permitidas = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array(strtolower($extensao), $extensoes_permitidas)) {
            $novo_nome = uniqid() . '.' . $extensao;
            $destino = '../assets/images/' . $novo_nome;
            
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                $imagem = $novo_nome;
            } else {
                $_SESSION['error'] = "Erro ao fazer upload da imagem.";
            }
        } else {
            $_SESSION['error'] = "Formato de imagem não permitido. Use jpg, jpeg, png ou gif.";
        }
    }
    
    if ($id > 0) {
        $sql = "UPDATE livros SET 
                titulo = '$titulo',
                id_autor = $id_autor,
                id_editora = $id_editora,
                id_categoria = $id_categoria,
                preco = $preco,
                quantidade_estoque = $quantidade_estoque,
                ano_publicacao = $ano_publicacao,
                isbn = '$isbn',
                descricao = '$descricao'";
        
        if ($imagem != $imagem_atual) {
            $sql .= ", imagem = '$imagem'";
        }
        
        $sql .= " WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Livro atualizado com sucesso!";
            redirect('livros.php');
        } else {
            $_SESSION['error'] = "Erro ao atualizar o livro: " . mysqli_error($conn);
        }
    } else {
        // adaddd novo livro
        $sql = "INSERT INTO livros (titulo, id_autor, id_editora, id_categoria, preco, quantidade_estoque, ano_publicacao, isbn, descricao, imagem)
                VALUES ('$titulo', $id_autor, $id_editora, $id_categoria, $preco, $quantidade_estoque, $ano_publicacao, '$isbn', '$descricao', '$imagem')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Livro adicionado com sucesso!";
            redirect('livros.php');
        } else {
            $_SESSION['error'] = "Erro ao adicionar o livro: " . mysqli_error($conn);
        }
    }
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <h1 class="text-2xl font-bold mb-6"><?php echo $id > 0 ? 'Editar' : 'Adicionar'; ?> Livro</h1>
        
        <form method="post" action="" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="titulo" class="block text-gray-700 font-medium mb-1">Título</label>
                    <input type="text" name="titulo" id="titulo" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           value="<?php echo htmlspecialchars($titulo); ?>">
                </div>
                
                <div>
                    <label for="id_autor" class="block text-gray-700 font-medium mb-1">Autor</label>
                    <select name="id_autor" id="id_autor" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Selecione um autor</option>
                        <?php
                        $sql = "SELECT id, nome FROM autores ORDER BY nome";
                        $result = mysqli_query($conn, $sql);
                        
                        while ($autor = mysqli_fetch_assoc($result)) {
                            $selected = ($autor['id'] == $id_autor) ? 'selected' : '';
                            echo '<option value="' . $autor['id'] . '" ' . $selected . '>' . htmlspecialchars($autor['nome']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div>
                    <label for="id_editora" class="block text-gray-700 font-medium mb-1">Editora</label>
                    <select name="id_editora" id="id_editora" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Selecione uma editora</option>
                        <?php
                        $sql = "SELECT id, nome FROM editoras ORDER BY nome";
                        $result = mysqli_query($conn, $sql);
                        
                        while ($editora = mysqli_fetch_assoc($result)) {
                            $selected = ($editora['id'] == $id_editora) ? 'selected' : '';
                            echo '<option value="' . $editora['id'] . '" ' . $selected . '>' . htmlspecialchars($editora['nome']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div>
                    <label for="id_categoria" class="block text-gray-700 font-medium mb-1">Categoria</label>
                    <select name="id_categoria" id="id_categoria" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Selecione uma categoria</option>
                        <?php
                        $sql = "SELECT id, nome FROM categorias ORDER BY nome";
                        $result = mysqli_query($conn, $sql);
                        
                        while ($categoria = mysqli_fetch_assoc($result)) {
                            $selected = ($categoria['id'] == $id_categoria) ? 'selected' : '';
                            echo '<option value="' . $categoria['id'] . '" ' . $selected . '>' . htmlspecialchars($categoria['nome']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div>
                    <label for="preco" class="block text-gray-700 font-medium mb-1">Preço</label>
                    <input type="text" name="preco" id="preco" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           value="<?php echo htmlspecialchars($preco); ?>">
                </div>
                
                <div>
                    <label for="quantidade_estoque" class="block text-gray-700 font-medium mb-1">Quantidade em Estoque</label>
                    <input type="number" name="quantidade_estoque" id="quantidade_estoque" required min="0"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           value="<?php echo htmlspecialchars($quantidade_estoque); ?>">
                </div>
                
                <div>
                    <label for="ano_publicacao" class="block text-gray-700 font-medium mb-1">Ano de Publicação</label>
                    <input type="number" name="ano_publicacao" id="ano_publicacao" required min="1800" max="<?php echo date('Y'); ?>"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           value="<?php echo htmlspecialchars($ano_publicacao); ?>">
                </div>
                
                <div>
                    <label for="isbn" class="block text-gray-700 font-medium mb-1">ISBN</label>
                    <input type="text" name="isbn" id="isbn"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           value="<?php echo htmlspecialchars($isbn); ?>">
                </div>
                
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-gray-700 font-medium mb-1">Descrição</label>
                    <textarea name="descricao" id="descricao" rows="5"
                              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($descricao); ?></textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label for="imagem" class="block text-gray-700 font-medium mb-1">Imagem</label>
                    <?php if (!empty($imagem) && file_exists('../assets/images/' . $imagem)): ?>
                        <div class="mb-2">
                            <img src="../assets/images/<?php echo htmlspecialchars($imagem); ?>" alt="Imagem atual" class="h-40">
                            <p class="text-sm text-gray-500">Imagem atual</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="imagem" id="imagem"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Formatos permitidos: JPG, JPEG, PNG, GIF</p>
                </div>
            </div>
            
            <div class="flex justify-between">
                <a href="livros.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                    <?php echo $id > 0 ? 'Atualizar' : 'Adicionar'; ?> Livro
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?> 