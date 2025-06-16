<?php
require_once 'header.php';

if (!isLoggedIn()) {
    $_SESSION['error'] = "Você precisa fazer login para acessar o carrinho.";
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['atualizar'])) {
        $id_item = (int)$_POST['id_item'];
        $quantidade = (int)$_POST['quantidade'];
        
        if ($quantidade > 0) {
            $sql = "UPDATE itens_carrinho SET quantidade = $quantidade WHERE id = $id_item";
            
            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Carrinho atualizado com sucesso!";
            } else {
                $_SESSION['error'] = "Erro ao atualizar o carrinho.";
            }
        } else {
            $_SESSION['error'] = "Quantidade inválida.";
        }
    }
    
    if (isset($_POST['remover'])) {
        $id_item = (int)$_POST['id_item'];
        
        $sql = "DELETE FROM itens_carrinho WHERE id = $id_item";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Item removido do carrinho com sucesso!";
        } else {        
            $_SESSION['error'] = "Erro ao remover o item do carrinho.";
        }
    }
    
    if (isset($_POST['finalizar'])) {
        $id_carrinho = (int)$_POST['id_carrinho'];
        
        $sql = "SELECT COUNT(*) as total FROM itens_carrinho WHERE id_carrinho = $id_carrinho";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        
        if ($row['total'] > 0) {
            $sql = "UPDATE carrinhos SET status = 'finalizado' WHERE id = $id_carrinho";
            
            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Compra finalizada com sucesso! Obrigado pela preferência.";
                redirect('index.php');
            } else {
                $_SESSION['error'] = "Erro ao finalizar a compra.";
            }
        } else {
            $_SESSION['error'] = "Seu carrinho está vazio.";
        }
    }
    
    redirect('carrinho.php');
}

$itens = getCarrinho();
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 md:p-8">
        <h1 class="text-2xl font-bold mb-6">Meu Carrinho</h1>
        
        <?php if (empty($itens)): ?>
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-600">Seu carrinho está vazio.</p>
                <a href="index.php" class="inline-block mt-4 bg-blue-700 text-white px-6 py-2 rounded font-medium hover:bg-blue-800">
                    Continuar Comprando
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-3 px-4">Produto</th>
                            <th class="text-center py-3 px-4">Preço</th>
                            <th class="text-center py-3 px-4">Quantidade</th>
                            <th class="text-center py-3 px-4">Subtotal</th>
                            <th class="text-center py-3 px-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $id_carrinho = 0;
                        foreach ($itens as $item): 
                            $total += $item['subtotal'];
                            $id_carrinho = $item['id_carrinho'];
                        ?>
                            <tr class="border-b">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 mr-4 bg-gray-200 flex items-center justify-center rounded">
                                            <?php if (!empty($item['imagem']) && file_exists('assets/images/' . $item['imagem'])): ?>
                                                <img src="assets/images/<?php echo htmlspecialchars($item['imagem']); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['titulo']); ?>" 
                                                     class="h-full object-cover">
                                            <?php else: ?>
                                                <i class="fas fa-book text-gray-400"></i>
                                            <?php endif; ?>
                                        </div>
                                        <a href="livro.php?id=<?php echo $item['id_livro']; ?>" class="text-blue-700 hover:text-blue-900">
                                            <?php echo htmlspecialchars($item['titulo']); ?>
                                        </a>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <form method="post" action="" class="flex justify-center">
                                        <input type="hidden" name="id_item" value="<?php echo $item['id_item']; ?>">
                                        <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" 
                                               class="border border-gray-300 rounded px-3 py-1 w-16 text-center">
                                        <button type="submit" name="atualizar" class="ml-2 text-gray-600 hover:text-gray-900">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-4 text-center font-semibold">
                                    R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <form method="post" action="">
                                        <input type="hidden" name="id_item" value="<?php echo $item['id_item']; ?>">
                                        <button type="submit" name="remover" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 border-t pt-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xl font-semibold">Total:</span>
                    <span class="text-2xl font-bold text-blue-700">R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                </div>
                
                <div class="flex justify-between">
                    <a href="index.php" class="bg-gray-200 text-gray-800 px-6 py-2 rounded font-medium hover:bg-gray-300">
                        Continuar Comprando
                    </a>
                    
                    <form method="post" action="">
                        <input type="hidden" name="id_carrinho" value="<?php echo $id_carrinho; ?>">
                        <button type="submit" name="finalizar" class="bg-green-600 text-white px-6 py-2 rounded font-medium hover:bg-green-700">
                            Finalizar Compra
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?> 