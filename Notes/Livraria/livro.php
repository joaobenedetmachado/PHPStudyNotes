<?php
require_once 'header.php';

// Verificar se foi fornecido um ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID do livro não fornecido.";
    redirect('index.php');
}

$id = $_GET['id'];
$livro = getLivroPorId($id);

// Verificar se o livro existe
if (!$livro) {
    $_SESSION['error'] = "Livro não encontrado.";
    redirect('index.php');
}

// Processar adição ao carrinho
if (isset($_POST['adicionar_carrinho'])) {
    if (!isLoggedIn()) {
        $_SESSION['error'] = "Você precisa fazer login para adicionar ao carrinho.";
        redirect('login.php');
    }
    
    $quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 1;
    
    if ($quantidade > 0 && $quantidade <= $livro['quantidade_estoque']) {
        if (adicionarAoCarrinho($livro['id'], $quantidade)) {
            $_SESSION['message'] = "Livro adicionado ao carrinho com sucesso!";
            redirect('livro.php?id=' . $livro['id']);
        } else {
            $_SESSION['error'] = "Erro ao adicionar ao carrinho.";
        }
    } else {
        $_SESSION['error'] = "Quantidade inválida ou fora de estoque.";
    }
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <a href="index.php" class="text-blue-700 hover:text-blue-900 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Voltar para a lista de livros
        </a>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
            <div class="flex justify-center">
                <div class="w-full max-w-md h-96 bg-gray-200 flex items-center justify-center rounded-lg overflow-hidden">
                    <?php if (!empty($livro['imagem']) && file_exists('assets/images/' . $livro['imagem'])): ?>
                        <img src="assets/images/<?php echo htmlspecialchars($livro['imagem']); ?>" 
                             alt="<?php echo htmlspecialchars($livro['titulo']); ?>" 
                             class="h-full object-cover">
                    <?php else: ?>
                        <div class="text-gray-400 text-center">
                            <i class="fas fa-book text-6xl mb-4"></i>
                            <p>Imagem não disponível</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($livro['titulo']); ?></h1>
                
                <p class="text-gray-600 mb-2">
                    <span class="font-semibold">Autor:</span> 
                    <a href="index.php?filtro=autor&busca=<?php echo urlencode($livro['autor']); ?>" class="text-blue-700 hover:text-blue-900">
                        <?php echo htmlspecialchars($livro['autor']); ?>
                    </a>
                </p>
                
                <p class="text-gray-600 mb-2">
                    <span class="font-semibold">Editora:</span>
                    <a href="index.php?filtro=editora&busca=<?php echo urlencode($livro['editora']); ?>" class="text-blue-700 hover:text-blue-900">
                        <?php echo htmlspecialchars($livro['editora']); ?>
                    </a>
                </p>
                
                <p class="text-gray-600 mb-2">
                    <span class="font-semibold">Categoria:</span>
                    <a href="index.php?filtro=categoria&busca=<?php echo urlencode($livro['categoria']); ?>" class="text-blue-700 hover:text-blue-900">
                        <?php echo htmlspecialchars($livro['categoria']); ?>
                    </a>
                </p>
                
                <p class="text-gray-600 mb-2">
                    <span class="font-semibold">Ano de Publicação:</span> 
                    <?php echo $livro['ano_publicacao']; ?>
                </p>
                
                <p class="text-gray-600 mb-2">
                    <span class="font-semibold">ISBN:</span> 
                    <?php echo htmlspecialchars($livro['isbn']); ?>
                </p>
                
                <p class="text-gray-600 mb-4">
                    <span class="font-semibold">Disponibilidade:</span> 
                    <?php if ($livro['quantidade_estoque'] > 0): ?>
                        <span class="text-green-600">Em estoque (<?php echo $livro['quantidade_estoque']; ?> unidades)</span>
                    <?php else: ?>
                        <span class="text-red-600">Fora de estoque</span>
                    <?php endif; ?>
                </p>
                
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <p class="text-2xl font-bold text-blue-700">
                        R$ <?php echo number_format($livro['preco'], 2, ',', '.'); ?>
                    </p>
                </div>
                
                <?php if ($livro['quantidade_estoque'] > 0): ?>
                    <form method="post" action="" class="flex items-end">
                        <div class="mr-2">
                            <label for="quantidade" class="block text-gray-700 font-medium mb-1">Quantidade:</label>
                            <input type="number" name="quantidade" id="quantidade" value="1" min="1" max="<?php echo $livro['quantidade_estoque']; ?>" 
                                   class="border border-gray-300 rounded px-3 py-2 w-20">
                        </div>
                        <button type="submit" name="adicionar_carrinho" class="bg-blue-700 text-white px-6 py-2 rounded font-medium hover:bg-blue-800">
                            <i class="fas fa-shopping-cart mr-2"></i> Adicionar ao Carrinho
                        </button>
                    </form>
                <?php else: ?>
                    <button class="bg-gray-400 text-white px-6 py-2 rounded font-medium cursor-not-allowed">
                        <i class="fas fa-shopping-cart mr-2"></i> Indisponível
                    </button>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Descrição do livro -->
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Descrição</h2>
            <div class="bg-gray-50 p-6 rounded-lg">
                <?php if (!empty($livro['descricao'])): ?>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($livro['descricao'])); ?></p>
                <?php else: ?>
                    <p class="text-gray-500 italic">Descrição não disponível.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?> 