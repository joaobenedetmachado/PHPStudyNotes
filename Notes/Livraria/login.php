<?php
require_once 'header.php';

if (isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    
    if (empty($email) || empty($senha)) {
        $_SESSION['error'] = "Preencha todos os campos.";
    } else {
        $sql = "SELECT id, nome, senha, admin FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) === 1) {
            $usuario = mysqli_fetch_assoc($result);
            
            if (md5($senha) === $usuario['senha']) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['nome'];
                $_SESSION['is_admin'] = $usuario['admin'];
                
                $_SESSION['message'] = "Login efetuado com sucesso!";
                redirect('index.php');
            } else {
                $_SESSION['error'] = "Senha incorreta.";
            }
        } else {
            $_SESSION['error'] = "Usuário não encontrado.";
        }
    }
}
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden mt-8">
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
        
        <form method="post" action="">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="mb-6">
                <label for="senha" class="block text-gray-700 font-medium mb-1">Senha</label>
                <input type="password" name="senha" id="senha" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>
            
            <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded font-medium hover:bg-blue-800 transition duration-300">
                Entrar
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600">Ainda não tem uma conta? <a href="registro.php" class="text-blue-700 font-medium hover:text-blue-900">Registre-se</a></p>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?> 