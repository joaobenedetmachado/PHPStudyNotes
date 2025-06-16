<?php
require_once '../header.php';

if (!isAdmin()) {
    $_SESSION['error'] = "Acesso restrito a administradores.";
    redirect('../index.php');
}

if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    
    $sql = "SELECT COUNT(*) as total FROM livros WHERE id_autor = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['total'] > 0) {
        $_SESSION['error'] = "Este autor não pode ser excluído pois está sendo usado por " . $row['total'] . " livro(s).";
    } else {
        $sql = "DELETE FROM autores WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Autor excluído com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao excluir o autor: " . mysqli_error($conn);
        }
    }
    
    redirect('autores.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nome = sanitize($_POST['nome']);
    $biografia = sanitize($_POST['biografia']);
    
    if (empty($nome)) {
        $_SESSION['error'] = "O nome do autor é obrigatório.";
    } else {
        if ($id > 0) {
            // Atualizar
            $sql = "UPDATE autores SET nome = '$nome', biografia = '$biografia' WHERE id = $id";
            $mensagem = "Autor atualizado com sucesso!";
        } else {
            // Inserir
            $sql = "INSERT INTO autores (nome, biografia) VALUES ('$nome', '$biografia')";
            $mensagem = "Autor adicionado com sucesso!";
        }
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = $mensagem;
        } else {
            $_SESSION['error'] = "Erro ao salvar o autor: " . mysqli_error($conn);
        }
    }
    
    redirect('autores.php');
}

$autor_edicao = null;
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    
    $sql = "SELECT * FROM autores WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $autor_edicao = mysqli_fetch_assoc($result);
    }
}

$sql = "SELECT a.*, (SELECT COUNT(*) FROM livros WHERE id_autor = a.id) as total_livros 
        FROM autores a ORDER BY a.nome";
$result = mysqli_query($conn, $sql);
$autores = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $autores[] = $row;
    }
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <h1 class="text-2xl font-bold mb-6">Gerenciar Autores</h1>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h2 class="text-xl font-semibold mb-4"><?php echo $autor_edicao ? 'Editar' : 'Adicionar'; ?> Autor</h2>
            
            <form method="post" action="">
                <?php if ($autor_edicao): ?>
                    <input type="hidden" name="id" value="<?php echo $autor_edicao['id']; ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="nome" class="block text-gray-700 font-medium mb-1">Nome</label>
                        <input type="text" name="nome" id="nome" required
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                               value="<?php echo $autor_edicao ? htmlspecialchars($autor_edicao['nome']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="biografia" class="block text-gray-700 font-medium mb-1">Biografia</label>
                        <textarea name="biografia" id="biografia" rows="3"
                                  class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"><?php echo $autor_edicao ? htmlspecialchars($autor_edicao['biografia']) : ''; ?></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <?php if ($autor_edicao): ?>
                        <a href="autores.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 mr-2">
                            Cancelar
                        </a>
                    <?php endif; ?>
                    
                    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                        <?php echo $autor_edicao ? 'Atualizar' : 'Adicionar'; ?> Autor
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Lista de autores -->
        <h2 class="text-xl font-semibold mb-4">Autores Cadastrados</h2>
        
        <?php if (empty($autores)): ?>
            <p class="text-center py-4">Nenhum autor cadastrado.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Nome</th>
                            <th class="py-3 px-4 text-left">Biografia</th>
                            <th class="py-3 px-4 text-center">Livros</th>
                            <th class="py-3 px-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($autores as $autor): ?>
                            <tr class="border-b">
                                <td class="py-3 px-4"><?php echo htmlspecialchars($autor['nome']); ?></td>
                                <td class="py-3 px-4">
                                    <?php 
                                    $biografia = htmlspecialchars($autor['biografia']);
                                    echo (strlen($biografia) > 100) ? substr($biografia, 0, 100) . '...' : $biografia; 
                                    ?>
                                </td>
                                <td class="py-3 px-4 text-center"><?php echo $autor['total_livros']; ?></td>
                                <td class="py-3 px-4 text-center">
                                    <a href="autores.php?editar=<?php echo $autor['id']; ?>" class="text-blue-700 hover:text-blue-900 mx-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($autor['total_livros'] == 0): ?>
                                        <a href="javascript:void(0);" 
                                           onclick="confirmarExclusao(<?php echo $autor['id']; ?>, '<?php echo htmlspecialchars($autor['nome']); ?>')" 
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
    if (confirm("Tem certeza que deseja excluir o autor '" + nome + "'? Esta ação não pode ser desfeita.")) {
        window.location.href = "autores.php?excluir=" + id;
    }
}
</script>

<?php require_once '../footer.php'; ?> 