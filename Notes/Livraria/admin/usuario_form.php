<?php
require_once '../header.php';

if (!isAdmin()) {
    $_SESSION['error'] = "Acesso restrito a administradores.";
    redirect('../index.php');
}

$id = 0;
$nome = '';
$email = '';
$admin = 0;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if ($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "Você não pode editar sua própria conta aqui.";
        redirect('usuarios.php');
    }
    
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
        $nome = $usuario['nome'];
        $email = $usuario['email'];
        $admin = $usuario['admin'];
    } else {
        $_SESSION['error'] = "Usuário não encontrado.";
        redirect('usuarios.php');
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize($_POST['nome']);
    $email = sanitize($_POST['email']);
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    $admin = isset($_POST['admin']) ? 1 : 0;
    
    $errors = array();
    
    if (empty($nome)) {
        $errors[] = "Nome é obrigatório.";
    }
    
    if (empty($email)) {
        $errors[] = "Email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    }
    
    $sql = "SELECT id FROM usuarios WHERE email = '$email'";
    if ($id > 0) {
        $sql .= " AND id != $id";
    }
    
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Este email já está sendo usado por outro usuário.";
    }
    
    if ($id == 0 && empty($senha)) {
        $errors[] = "Senha é obrigatória para novos usuários.";
    }
    
    if (empty($errors)) {
        if ($id > 0) {
            if (!empty($senha)) {
                $senha_hash = md5($senha);
                $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', senha = '$senha_hash', admin = $admin WHERE id = $id";
            } else {
                $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', admin = $admin WHERE id = $id";
            }
            
            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Usuário atualizado com sucesso!";
                redirect('usuarios.php');
            } else {
                $errors[] = "Erro ao atualizar o usuário: " . mysqli_error($conn);
            }
        } else {
            $senha_hash = md5($senha);
            $sql = "INSERT INTO usuarios (nome, email, senha, admin) VALUES ('$nome', '$email', '$senha_hash', $admin)";
            
            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Usuário adicionado com sucesso!";
                redirect('usuarios.php');
            } else {
                $errors[] = "Erro ao adicionar o usuário: " . mysqli_error($conn);
            }
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <h1 class="text-2xl font-bold mb-6"><?php echo $id > 0 ? 'Editar' : 'Adicionar'; ?> Usuário</h1>
        
        <form method="post" action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nome" class="block text-gray-700 font-medium mb-1">Nome</label>
                    <input type="text" name="nome" id="nome" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           value="<?php echo htmlspecialchars($nome); ?>">
                </div>
                
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           value="<?php echo htmlspecialchars($email); ?>">
                </div>
                
                <div>
                    <label for="senha" class="block text-gray-700 font-medium mb-1">
                        <?php echo $id > 0 ? 'Nova Senha (deixe em branco para manter a atual)' : 'Senha'; ?>
                    </label>
                    <input type="password" name="senha" id="senha" <?php echo $id == 0 ? 'required' : ''; ?>
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <?php if ($id == 0): ?>
                        <p class="text-sm text-gray-500 mt-1">Mínimo de 6 caracteres recomendado.</p>
                    <?php endif; ?>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="admin" id="admin" value="1" 
                           <?php echo $admin == 1 ? 'checked' : ''; ?>
                           class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="admin" class="ml-2 block text-gray-700">
                        Usuário Administrador
                    </label>
                </div>
            </div>
            
            <div class="flex justify-between">
                <a href="usuarios.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                    <?php echo $id > 0 ? 'Atualizar' : 'Adicionar'; ?> Usuário
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?> 