<?php
require_once '../header.php';

if (!isAdmin()) {
    $_SESSION['error'] = "Acesso restrito a administradores.";
    redirect('../index.php');
}

if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    
    if ($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "Você não pode excluir sua própria conta.";
    } else {
        $sql = "DELETE FROM usuarios WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Usuário excluído com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao excluir o usuário: " . mysqli_error($conn);
        }
    }
    
    redirect('usuarios.php');
}

if (isset($_GET['tornar_admin'])) {
    $id = (int)$_GET['tornar_admin'];
    $tipo = (isset($_GET['tipo']) && $_GET['tipo'] == 1) ? 1 : 0;
    
    $sql = "UPDATE usuarios SET admin = $tipo WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Tipo de usuário alterado com sucesso!";
    } else {
        $_SESSION['error'] = "Erro ao alterar o tipo de usuário: " . mysqli_error($conn);
    }
    
    redirect('usuarios.php');
}

$sql = "SELECT * FROM usuarios ORDER BY nome";
$result = mysqli_query($conn, $sql);
$usuarios = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row;
    }
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Gerenciar Usuários</h1>
            <a href="usuario_form.php" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                <i class="fas fa-plus mr-1"></i> Novo Usuário
            </a>
        </div>
        
        <?php if (empty($usuarios)): ?>
            <p class="text-center py-4">Nenhum usuário cadastrado.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Nome</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-center">Tipo</th>
                            <th class="py-3 px-4 text-center">Data de Cadastro</th>
                            <th class="py-3 px-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr class="border-b">
                                <td class="py-3 px-4"><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td class="py-3 px-4 text-center">
                                    <?php if ($usuario['admin'] == 1): ?>
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Admin</span>
                                    <?php else: ?>
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">Usuário</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?php echo date('d/m/Y H:i', strtotime($usuario['data_cadastro'])); ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?php if ($usuario['id'] != $_SESSION['user_id']): ?>
                                        <?php if ($usuario['admin'] == 0): ?>
                                            <a href="usuarios.php?tornar_admin=<?php echo $usuario['id']; ?>&tipo=1" 
                                               class="text-green-600 hover:text-green-800 mx-1" title="Tornar Administrador">
                                                <i class="fas fa-user-shield"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="usuarios.php?tornar_admin=<?php echo $usuario['id']; ?>&tipo=0" 
                                               class="text-yellow-600 hover:text-yellow-800 mx-1" title="Tornar Usuário Normal">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="javascript:void(0);" 
                                           onclick="confirmarExclusao(<?php echo $usuario['id']; ?>)" 
                                           class="text-red-600 hover:text-red-800 mx-1" title="Excluir Usuário">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400" title="Não é possível editar sua própria conta aqui">
                                            <i class="fas fa-lock"></i>
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
function confirmarExclusao(id) {
    if (confirm("Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.")) {
        window.location.href = "usuarios.php?excluir=" + id;
    }
}
</script>

<?php require_once '../footer.php'; ?> 