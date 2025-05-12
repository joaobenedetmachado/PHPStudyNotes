<?php
require_once '../header.php';

// Verif se o usuário é administrador
if (!isAdmin()) {
    $_SESSION['error'] = "Acesso restrito a administradores.";
    redirect('../index.php');
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <h1 class="text-2xl font-bold mb-6">Painel de Administração</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="livros.php" class="bg-blue-100 p-6 rounded-lg hover:bg-blue-200 transition duration-300">
                <i class="fas fa-book text-4xl text-blue-700 mb-4"></i>
                <h2 class="text-xl font-bold">Livros</h2>
                <p class="text-gray-600">Gerenciar livros</p>
            </a>
            
            <a href="autores.php" class="bg-green-100 p-6 rounded-lg hover:bg-green-200 transition duration-300">
                <i class="fas fa-user-edit text-4xl text-green-700 mb-4"></i>
                <h2 class="text-xl font-bold">Autores</h2>
                <p class="text-gray-600">Gerenciar autores</p>
            </a>
            
            <a href="editoras.php" class="bg-purple-100 p-6 rounded-lg hover:bg-purple-200 transition duration-300">
                <i class="fas fa-building text-4xl text-purple-700 mb-4"></i>
                <h2 class="text-xl font-bold">Editoras</h2>
                <p class="text-gray-600">Gerenciar editoras</p>
            </a>
            
            <a href="categorias.php" class="bg-yellow-100 p-6 rounded-lg hover:bg-yellow-200 transition duration-300">
                <i class="fas fa-tag text-4xl text-yellow-700 mb-4"></i>
                <h2 class="text-xl font-bold">Categorias</h2>
                <p class="text-gray-600">Gerenciar categorias</p>
            </a>
            
            <a href="usuarios.php" class="bg-red-100 p-6 rounded-lg hover:bg-red-200 transition duration-300">
                <i class="fas fa-users text-4xl text-red-700 mb-4"></i>
                <h2 class="text-xl font-bold">Usuários</h2>
                <p class="text-gray-600">Gerenciar usuários</p>
            </a>
            
            <a href="../index.php" class="bg-gray-100 p-6 rounded-lg hover:bg-gray-200 transition duration-300">
                <i class="fas fa-home text-4xl text-gray-700 mb-4"></i>
                <h2 class="text-xl font-bold">Voltar para o site</h2>
                <p class="text-gray-600">Visualizar o site</p>
            </a>
        </div>
        
        <!-- Resumo do sistema -->
        <div class="mt-10">
            <h2 class="text-xl font-bold mb-4">Resumo do Sistema</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                // Total de livros
                $sql = "SELECT COUNT(*) as total FROM livros";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                ?>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700">Total de Livros</h3>
                    <p class="text-2xl font-bold text-blue-700"><?php echo $row['total']; ?></p>
                </div>
                
                <?php
                // Total de usuuarios
                $sql = "SELECT COUNT(*) as total FROM usuarios";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                ?>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700">Total de Usuários</h3>
                    <p class="text-2xl font-bold text-blue-700"><?php echo $row['total']; ?></p>
                </div>
                
                <?php
                // Total 
                $sql = "SELECT COUNT(*) as total FROM carrinhos WHERE status = 'finalizado'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                ?>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700">Total de Pedidos</h3>
                    <p class="text-2xl font-bold text-blue-700"><?php echo $row['total']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?> 