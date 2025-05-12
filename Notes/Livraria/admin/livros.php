<?php
require_once '../header.php';

if (!isAdmin()) {
    $_SESSION['error'] = "Acesso restrito a administradores.";
    redirect('../index.php');
}

if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    
    $sql = "DELETE FROM livros WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Livro excluído com sucesso!";
    } else {
        $_SESSION['error'] = "Erro ao excluir o livro: " . mysqli_error($conn);
    }
    
    redirect('livros.php');
}

$sql = "SELECT l.*, a.nome as autor, e.nome as editora, c.nome as categoria 
        FROM livros l
        LEFT JOIN autores a ON l.id_autor = a.id
        LEFT JOIN editoras e ON l.id_editora = e.id
        LEFT JOIN categorias c ON l.id_categoria = c.id
        ORDER BY l.titulo";
$result = mysqli_query($conn, $sql);
$livros = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $livros[] = $row;
    }
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Gerenciar Livros</h1>
            <a href="livro_form.php" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                <i class="fas fa-plus mr-1"></i> Novo Livro
            </a>
        </div>
        
        <?php if (empty($livros)): ?>
            <p class="text-center py-4">Nenhum livro cadastrado.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Título</th>
                            <th class="py-3 px-4 text-left">Autor</th>
                            <th class="py-3 px-4 text-left">Editora</th>
                            <th class="py-3 px-4 text-left">Categoria</th>
                            <th class="py-3 px-4 text-center">Preço</th>
                            <th class="py-3 px-4 text-center">Estoque</th>
                            <th class="py-3 px-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livros as $livro): ?>
                            <tr class="border-b">
                                <td class="py-3 px-4"><?php echo htmlspecialchars($livro['titulo']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($livro['autor']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($livro['editora']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($livro['categoria']); ?></td>
                                <td class="py-3 px-4 text-center">R$ <?php echo number_format($livro['preco'], 2, ',', '.'); ?></td>
                                <td class="py-3 px-4 text-center"><?php echo $livro['quantidade_estoque']; ?></td>
                                <td class="py-3 px-4 text-center">
                                    <a href="livro_form.php?id=<?php echo $livro['id']; ?>" class="text-blue-700 hover:text-blue-900 mx-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="confirmarExclusao(<?php echo $livro['id']; ?>)" class="text-red-600 hover:text-red-800 mx-1">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <div class="mt-6">
            <a href="index.php" class="text-blue-700 hover:text-blue-900">
                <i class="fas fa-arrow-left mr-1"></i> Voltar para o Painel
            </a>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm("Tem certeza que deseja excluir este livro?")) {
        window.location.href = "livros.php?excluir=" + id;
    }
}
</script>

<?php require_once '../footer.php'; ?> 