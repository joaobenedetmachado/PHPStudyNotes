<?php
require_once 'header.php';

if (isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? sanitize($_POST['nome']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    $confirmar_senha = isset($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';
    
    $errors = array();
    
    if (empty($nome)) {
        $errors[] = "Nome é obrigatório.";
    }
    
    if (empty($email)) {
        $errors[] = "Email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    }
    
    if (empty($senha)) {
        $errors[] = "Senha é obrigatória.";
    } elseif (strlen($senha) < 6) {
        $errors[] = "A senha deve ter pelo menos 6 caracteres.";
    }
    
    if ($senha !== $confirmar_senha) {
        $errors[] = "As senhas não coincidem.";
    }
    
    $sql = "SELECT id FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Este email já está cadastrado.";
    }
    
    if (empty($errors)) {
        $senha_hash = md5($senha);
        
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Cadastro realizado com sucesso! Faça login para continuar.";
            redirect('login.php');
        } else {
            $errors[] = "Erro ao cadastrar: " . mysqli_error($conn);
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden mt-8">
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Registro</h2>
        
        <form method="post" action="">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-medium mb-1">Nome</label>
                <input type="text" name="nome" id="nome" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                       value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="mb-4">
                <label for="senha" class="block text-gray-700 font-medium mb-1">Senha</label>
                <input type="password" name="senha" id="senha" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                <p class="text-sm text-gray-500 mt-1">A senha deve ter pelo menos 6 caracteres.</p>
            </div>
            
            <div class="mb-6">
                <label for="confirmar_senha" class="block text-gray-700 font-medium mb-1">Confirmar Senha</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>
            
            <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded font-medium hover:bg-blue-800 transition duration-300">
                Registrar
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600">Já tem uma conta? <a href="login.php" class="text-blue-700 font-medium hover:text-blue-900">Faça login</a></p>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?> 