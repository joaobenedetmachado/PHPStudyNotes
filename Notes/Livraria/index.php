<?php
require_once 'header.php';

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$livros = getLivros($filtro, $busca);
?>

<div class="bg-blue-700 text-white py-12 px-4 mb-8 rounded-lg">
    <div class="container mx-auto text-center">
        <h1 class="text-4xl font-bold mb-4">Bem-vindo à Livraria Online</h1>
        <p class="text-xl mb-6">Descubra novos mundos através dos livros</p>
        <div class="flex justify-center">
            <a href="#livros" class="bg-white text-blue-700 px-6 py-2 rounded-lg font-semibold hover:bg-blue-100 transition duration-300">Ver Livros</a>
        </div>
    </div>
</div>


<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Categorias</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <?php
        $sql = "SELECT * FROM categorias ORDER BY nome";
        $result = mysqli_query($conn, $sql);
        
        while ($categoria = mysqli_fetch_assoc($result)) {
            echo '<a href="index.php?filtro=categoria&busca=' . urlencode($categoria['nome']) . '" 
                     class="bg-white rounded-lg p-4 shadow hover:shadow-md transition duration-300 text-center">
                    <h3 class="font-semibold">' . htmlspecialchars($categoria['nome']) . '</h3>
                 </a>';
        }
        ?>
    </div>
</div>

<!-- Livros cadastrados e tals -->
<div id="livros" class="mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Nossos Livros</h2>
        <?php if ($busca): ?>
            <p>Resultados para: "<?php echo htmlspecialchars($busca); ?>" em <?php echo htmlspecialchars($filtro); ?></p>
        <?php endif; ?>
    </div>
    
    <?php if (empty($livros)): ?>
        <p class="text-center py-12 bg-white rounded-lg shadow">Nenhum livro encontrado.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($livros as $livro): ?>
                <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition duration-300">
                    <div class="h-56 bg-gray-200 flex items-center justify-center">
                        <?php if (!empty($livro['imagem']) && file_exists('assets/images/' . $livro['imagem'])): ?>
                            <img src="assets/images/<?php echo htmlspecialchars($livro['imagem']); ?>" 
                                 alt="<?php echo htmlspecialchars($livro['titulo']); ?>" 
                                 class="h-full object-cover">
                        <?php else: ?>
                            <div class="text-gray-400 text-center">
                                <img class="text-4xl mb-2" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAA5klEQVR4nL3UMQrCMBTG8T84CR0cnMRJb6CLiO52ETyAp/IErs7i6KAgeAhB20kQ0ckxUugQ0tgkjekHgdIX8iMv8KCmNIAl0A0FzAAB3IBeCKAJHEMjixxQkRHwlmqmlQKxDrgoG32QRAf82tjP60Pg6YBYAcIDsQaEpl0vpT4GJj6AMCDZ4VNfQDi2qxIgHBBr4F7hTayBXT6nNtK/hyViBK5AWxolZ+AEtIC9RbtKgQ8wUOrZpO3k3xFwMNykFFhjTiQhiQYxtsh1JQpSSPpnpJC4DsQnkfQmW6+TKEdWwDwUUH++pkQAD9BJySkAAAAASUVORK5CYII=" alt="no-image">
                                <p>Imagem não disponível</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                        <p class="text-gray-600 mb-1">Autor: <?php echo htmlspecialchars($livro['autor']); ?></p>
                        <p class="text-gray-600 mb-2">Categoria: <?php echo htmlspecialchars($livro['categoria']); ?></p>
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-blue-700 font-bold">R$ <?php echo number_format($livro['preco'], 2, ',', '.'); ?></span>
                            <a href="livro.php?id=<?php echo $livro['id']; ?>" class="text-blue-700 hover:text-blue-900">
                                Ver detalhes <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Editoras -->
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Nossas Editoras</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $sql = "SELECT * FROM editoras ORDER BY nome";
        $result = mysqli_query($conn, $sql);
        
        while ($editora = mysqli_fetch_assoc($result)) {
            echo '<a href="index.php?filtro=editora&busca=' . urlencode($editora['nome']) . '" 
                     class="bg-white rounded-lg p-4 shadow hover:shadow-md transition duration-300 text-center">
                    <h3 class="font-semibold">' . htmlspecialchars($editora['nome']) . '</h3>
                 </a>';
        }
        ?>
    </div>
</div>

<?php require_once 'footer.php'; ?> 