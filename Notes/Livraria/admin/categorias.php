<?php
require_once '../header.php';

if (!isAdmin()) {
    $_SESSION['error'] = "Acesso restrito a administradores.";
    redirect('../index.php');
}

if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    
    $sql = "SELECT COUNT(*) as total FROM livros WHERE id_categoria = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['total'] > 0) {
        $_SESSION['error'] = "Esta categoria não pode ser excluída pois está sendo usada por " . $row['total'] . " livro(s).";
    } else {
        $sql = "DELETE FROM categorias WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Categoria excluída com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao excluir a categoria: " . mysqli_error($conn);
        }
    }
    
    redirect('categorias.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nome = sanitize($_POST['nome']);
    $descricao = sanitize($_POST['descricao']);
    
    if (empty($nome)) {
        $_SESSION['error'] = "O nome da categoria é obrigatório.";
    } else {
        if ($id > 0) {
            // Atualizar
            $sql = "UPDATE categorias SET nome = '$nome', descricao = '$descricao' WHERE id = $id";
            $mensagem = "Categoria atualizada com sucesso!";
        } else {
            // Inserir
            $sql = "INSERT INTO categorias (nome, descricao) VALUES ('$nome', '$descricao')";
            $mensagem = "Categoria adicionada com sucesso!";
        }
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = $mensagem;
        } else {
            $_SESSION['error'] = "Erro ao salvar a categoria: " . mysqli_error($conn);
        }
    }
    
    redirect('categorias.php');
}

$categoria_edicao = null;
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    
    $sql = "SELECT * FROM categorias WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $categoria_edicao = mysqli_fetch_assoc($result);
    }
}

$sql = "SELECT c.*, (SELECT COUNT(*) FROM livros WHERE id_categoria = c.id) as total_livros 
        FROM categorias c ORDER BY c.nome";
$result = mysqli_query($conn, $sql);
$categorias = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categorias[] = $row;
    }
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <h1 class="text-2xl font-bold mb-6">Gerenciar Categorias</h1>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h2 class="text-xl font-semibold mb-4"><?php echo $categoria_edicao ? 'Editar' : 'Adicionar'; ?> Categoria</h2>
            
            <form method="post" action="">
                <?php if ($categoria_edicao): ?>
                    <input type="hidden" name="id" value="<?php echo $categoria_edicao['id']; ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="nome" class="block text-gray-700 font-medium mb-1">Nome</label>
                        <input type="text" name="nome" id="nome" required
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                               value="<?php echo $categoria_edicao ? htmlspecialchars($categoria_edicao['nome']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="descricao" class="block text-gray-700 font-medium mb-1">Descrição</label>
                        <input type="text" name="descricao" id="descricao"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                               value="<?php echo $categoria_edicao ? htmlspecialchars($categoria_edicao['descricao']) : ''; ?>">
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <?php if ($categoria_edicao): ?>
                        <a href="categorias.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 mr-2">
                            Cancelar
                        </a>
                    <?php endif; ?>
                    
                    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                        <?php echo $categoria_edicao ? 'Atualizar' : 'Adicionar'; ?> Categoria
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Lista de categorias -->
        <h2 class="text-xl font-semibold mb-4">Categorias Cadastradas</h2>
        
        <?php if (empty($categorias)): ?>
            <p class="text-center py-4">Nenhuma categoria cadastrada.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Nome</th>
                            <th class="py-3 px-4 text-left">Descrição</th>
                            <th class="py-3 px-4 text-center">Livros</th>
                            <th class="py-3 px-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr class="border-b">
                                <td class="py-3 px-4"><?php echo htmlspecialchars($categoria['nome']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($categoria['descricao']); ?></td>
                                <td class="py-3 px-4 text-center"><?php echo $categoria['total_livros']; ?></td>
                                <td class="py-3 px-4 text-center">
                                    <a href="categorias.php?editar=<?php echo $categoria['id']; ?>" class="text-blue-700 hover:text-blue-900 mx-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($categoria['total_livros'] == 0): ?>
                                        <a href="javascript:void(0);" 
                                           onclick="confirmarExclusao(<?php echo $categoria['id']; ?>, '<?php echo htmlspecialchars($categoria['nome']); ?>')" 
                                           class="text-red-600 hover:text-red-800 mx-1" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400 mx-1" title="Não é possível excluir. Existem livros associados.">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                    <?php endif; ?>
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

<!-- Script para confirmar exclusão -->
<script>
function confirmarExclusao(id, nome) {
    if (confirm("Tem certeza que deseja excluir a categoria '" + nome + "'? Esta ação não pode ser desfeita.")) {
        window.location.href = "categorias.php?excluir=" + id;
    }
}
</script>

<?php require_once '../footer.php'; ?> 