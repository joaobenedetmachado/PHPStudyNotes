<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

$connect = mysql_connect("localhost", "root", "");
$banco = mysql_select_db("loja");

if (!$connect) {
    die("Erro na conexão com o MySQL: " . mysql_error());
}

if (!$banco) {
    die("Erro ao selecionar o banco de dados: " . mysql_error());
}

$categoriaAtual = isset($_GET['categoria']) ? mysql_real_escape_string($_GET['categoria']) : "Masculino";
$marcaAtual = isset($_GET['marca']) ? mysql_real_escape_string($_GET['marca']) : "";

$queryproduto = "SELECT p.* FROM produto p
                JOIN categoria c ON p.codcategoria = c.codigo";

if (!empty($marcaAtual)) {
    $queryproduto .= " JOIN marca m ON p.codmarca = m.codigo
                      WHERE c.nome = '$categoriaAtual' AND m.nome = '$marcaAtual'";
} else {
    $queryproduto .= " WHERE c.nome = '$categoriaAtual'";
}

$result = mysql_query($queryproduto);

if (!$result) {
    die("Erro na consulta de produtos: " . mysql_error());
}

$produtos = array();
while ($row = mysql_fetch_assoc($result)) {
    $produtos[] = $row;
}

$querymarca = "SELECT * FROM marca"; 
$resultmarca = mysql_query($querymarca);

if (!$resultmarca) {
    die("Erro na consulta de marcas: " . mysql_error());
}

$marcas = array();
while ($row = mysql_fetch_assoc($resultmarca)) {
    $marcas[] = $row;
}

$querycategoria = "SELECT * FROM categoria"; 
$resultcategoria = mysql_query($querycategoria);

if (!$resultcategoria) {
    die("Erro na consulta de categorias: " . mysql_error());
}

$categorias = array();
while ($row = mysql_fetch_assoc($resultcategoria)) {
    $categorias[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportStyle Pro - Loja de Artigos Esportivos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icons i {
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .header-icons i:hover {
            color: #3498db;
        }

        .navbar {
            background-color: #34495e;
            padding: 10px 0;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            list-style: none;
            gap: 30px;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .nav-menu a:hover {
            color: #3498db;
        }

        .category-tabs {
            display: flex;
            justify-content: center;
            background-color: #ecf0f1;
            padding: 15px 0;
            margin-top: 20px;
        }

        .category-tab {
            margin: 0 15px;
            cursor: pointer;
            font-weight: bold;
            color: #7f8c8d;
            position: relative;
            padding-bottom: 10px;
            text-decoration: none;
        }

        .category-tab.active {
            color: #2c3e50;
        }

        .category-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #3498db;
        }

        .brand-tabs {
            display: flex;
            justify-content: center;
            background-color: #f5f6fa;
            padding: 15px 0;
        }

        .brand-tab {
            margin: 0 15px;
            cursor: pointer;
            font-weight: bold;
            color: #7f8c8d;
            position: relative;
            padding-bottom: 10px;
            text-decoration: none;
        }

        .brand-tab.active {
            color: #2c3e50;
        }

        .brand-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #e74c3c;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            position: relative;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .product-details {
            padding: 15px;
        }

        .product-details h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .product-price {
            font-weight: bold;
            color: #2ecc71;
            font-size: 20px;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .btn-buy, .btn-cart {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-buy {
            background-color: #3498db;
            color: white;
        }

        .btn-cart {
            background-color: #2ecc71;
            color: white;
        }

        .btn-buy:hover {
            background-color: #2980b9;
        }

        .btn-cart:hover {
            background-color: #27ae60;
        }

        .product-discount {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-input {
            width: 100%;
            max-width: 600px;
            padding: 10px;
            border: 2px solid #3498db;
            border-radius: 20px;
            font-size: 16px;
        }

        .filter-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-btn {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .filter-btn:hover {
            background-color: #2980b9;
        }

        .clear-filters {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            margin-left: 20px;
            display: inline-block;
            padding: 10px;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            padding: 30px 0;
            margin-top: 30px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
        }

        .footer-section {
            flex: 1;
            margin: 0 15px;
        }

        .footer-section h4 {
            margin-bottom: 15px;
            color: #3498db;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
        }

        /* Cart Modal Styles */
        .cart-modal {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            height: 100%;
            background-color: white;
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
            z-index: 1000;
            padding: 20px;
            overflow-y: auto;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: #2ecc71;
            font-weight: bold;
        }

        .remove-item {
            color: #e74c3c;
            cursor: pointer;
            margin-left: 10px;
        }

        .cart-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-weight: bold;
            font-size: 18px;
        }

        .close-cart {
            cursor: pointer;
            font-size: 24px;
        }

        .cart-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }

        /* Pop-up notification styles */
        .popup-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: none;
            animation: slideIn 0.5s ease-out;
            z-index: 1001;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">SportStyle Pro</div>
            <div class="header-icons">
                <i class="fas fa-search"></i>
                <a href="./cadastro.html"><i class="fas fa-user"></i></a>
                <a href="#" id="cart-icon" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Pesquise por produtos, marcas...">
        </div>

        <div class="category-tabs">
            <?php foreach ($categorias as $categoria): ?>
                <a href="?categoria=<?php echo urlencode($categoria['nome']); ?><?php echo !empty($marcaAtual) ? '&marca='.urlencode($marcaAtual) : ''; ?>" 
                   class="category-tab <?php echo ($categoria['nome'] == $categoriaAtual) ? 'active' : ''; ?>">
                    <?php echo $categoria['nome']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="brand-tabs">
            <a href="?categoria=<?php echo urlencode($categoriaAtual); ?>" 
               class="brand-tab <?php echo (empty($marcaAtual)) ? 'active' : ''; ?>">
                Todas as Marcas
            </a>
            <?php foreach ($marcas as $marca): ?>
                <a href="?categoria=<?php echo urlencode($categoriaAtual); ?>&marca=<?php echo urlencode($marca['nome']); ?>" 
                   class="brand-tab <?php echo ($marca['nome'] == $marcaAtual) ? 'active' : ''; ?>">
                    <?php echo $marca['nome']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="product-grid">
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="product-card">
                        <img src="fotos/<?php echo $produto['foto1']; ?>" alt="<?php echo htmlspecialchars($produto['descricao']); ?>">
                        <div class="product-details">
                            <h3><?php echo htmlspecialchars($produto['descricao']); ?></h3>
                            <p class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                            <div class="product-actions">
                                <button class="btn-buy">Comprar</button>
                                <button class="btn-cart">Carrinho</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <h3>Nenhum produto encontrado para esta combinação de filtros</h3>
                    <a href="?categoria=<?php echo urlencode($categoriaAtual); ?>" class="clear-filters">Limpar filtro de marca</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container footer-content">
            <div class="footer-section">
                <h4>Institucional</h4>
                <div class="footer-links">
                    <a href="#">Sobre Nós</a>
                    <a href="#">Política de Privacidade</a>
                    <a href="#">Termos de Uso</a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Atendimento</h4>
                <div class="footer-links">
                    <a href="#">Fale Conosco</a>
                    <a href="#">Central de Ajuda</a>
                    <a href="#">Trocas e Devoluções</a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Redes Sociais</h4>
                <div class="footer-links">
                    <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                </div>
            </div>
        </div>
    </footer>

    <div class="cart-overlay"></div>
    <div class="cart-modal">
        <div class="cart-header">
            <h3>Seu Carrinho</h3>
            <span class="close-cart">&times;</span>
        </div>
        <div class="cart-items">
        </div>
        <div class="cart-total">
            Total: R$ <span class="total-value">0,00</span>
        </div>
        <button id="checkout-button" class="btn-buy" style="width: 100%; margin-top: 15px; padding: 12px 0;">Finalizar Compra</button>
    </div>

    <!-- Pop-up notification -->
    <div class="popup-notification">
        Produto adicionado ao carrinho!
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('pk_test_51NzQwqSDn30SRrLFYgAVlLcPnWQb7NN2mywhJHyb1CRXvQhW0rHJxbwF51jSbTd51HDuULHBUh2KRK5iqZZsj0sZ00DlLcJgxc');
        const searchInput = document.querySelector('.search-input');
        const productCards = document.querySelectorAll('.product-card');

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            productCards.forEach(card => {
                const productName = card.querySelector('h3').textContent.toLowerCase();
                card.style.display = productName.includes(searchTerm) ? 'block' : 'none';
            });
        });

        const cartIcon = document.getElementById('cart-icon');
        const cartModal = document.querySelector('.cart-modal');
        const cartOverlay = document.querySelector('.cart-overlay');
        const closeCart = document.querySelector('.close-cart');
        const cartItems = document.querySelector('.cart-items');
        const totalValue = document.querySelector('.total-value');
        const popupNotification = document.querySelector('.popup-notification');

        function showPopup() {
            popupNotification.style.display = 'block';
            setTimeout(() => {
                popupNotification.style.animation = 'slideOut 0.5s ease-out';
                setTimeout(() => {
                    popupNotification.style.display = 'none';
                    popupNotification.style.animation = 'slideIn 0.5s ease-out';
                }, 500);
            }, 2000);
        }

        function openCart() {
            console.log('Opening cart...');
            cartModal.style.display = 'block';
            cartOverlay.style.display = 'block';
            loadCartItems();
        }

        function closeCartModal() {
            console.log('Closing cart...');
            cartModal.style.display = 'none';
            cartOverlay.style.display = 'none';
        }

        if (cartIcon) {
            console.log('Cart icon found');
            cartIcon.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Cart icon clicked');
                openCart();
            });
        } else {
            console.error('Cart icon not found');
        }

        closeCart.addEventListener('click', closeCartModal);
        cartOverlay.addEventListener('click', closeCartModal);

        function addToCart(productId) {
            console.log('Adding product to cart:', productId);
            fetch('carrinho.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `acao=adicionar&id_produto=${productId}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Cart response:', data);
                if (data.success) {
                    showPopup();
                    loadCartItems();
                } else {
                    alert('Erro ao adicionar produto ao carrinho: ' + (data.error || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                alert('Erro ao adicionar produto ao carrinho');
            });
        }

        function removeFromCart(productId) {
            console.log('Removing product from cart:', productId);
            fetch('carrinho.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `acao=remover&id_produto=${productId}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Remove response:', data);
                if (data.success) {
                    loadCartItems();
                } else {
                    alert('Erro ao remover produto do carrinho: ' + (data.error || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Error removing from cart:', error);
                alert('Erro ao remover produto do carrinho');
            });
        }

        function loadCartItems() {
            console.log('Loading cart items');
            fetch('carrinho.php?acao=listar')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.error('Response content type:', contentType);
                        throw new TypeError("Oops, we haven't got JSON!");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Cart items:', data);
                    let total = 0;
                    cartItems.innerHTML = '';
                    
                    if (!data.success) {
                        console.error('Error from server:', data.error);
                        cartItems.innerHTML = '<p>Erro ao carregar carrinho: ' + (data.error || 'Erro desconhecido') + '</p>';
                        return;
                    }

                    if (!data.produtos || data.produtos.length === 0) {
                        cartItems.innerHTML = '<p>Carrinho vazio</p>';
                        totalValue.textContent = '0,00';
                        return;
                    }

                    data.produtos.forEach(produto => {
                        total += parseFloat(produto.preco);
                        const item = document.createElement('div');
                        item.className = 'cart-item';
                        item.innerHTML = `
                            <img src="fotos/${produto.foto1}" alt="${produto.descricao}">
                            <div class="cart-item-details">
                                <div class="cart-item-title">${produto.descricao}</div>
                                <div class="cart-item-price">R$ ${parseFloat(produto.preco).toFixed(2)}</div>
                            </div>
                            <span class="remove-item" onclick="removeFromCart(${produto.codigo})">&times;</span>
                        `;
                        cartItems.appendChild(item);
                    });

                    totalValue.textContent = total.toFixed(2);
                })
                .catch(error => {
                    console.error('Error loading cart items:', error);
                    cartItems.innerHTML = '<p>Erro ao carregar carrinho. Por favor, tente novamente.</p>';
                    totalValue.textContent = '0,00';
                });
        }

        <?php
        echo "const productIds = [";
        foreach ($produtos as $produto) {
            echo $produto['codigo'] . ",";
        }
        echo "];\n";
        ?>

        document.querySelectorAll('.product-card').forEach((card, index) => {
            if (productIds[index]) {
                card.dataset.productId = productIds[index];
                console.log('Added product ID to card:', productIds[index]);
            }
        });

        document.querySelectorAll('.btn-cart').forEach((button, index) => {
            button.addEventListener('click', function() {
                const productCard = this.closest('.product-card');
                const productId = productCard.dataset.productId;
                if (productId) {
                    console.log('Cart button clicked for product:', productId);
                    addToCart(productId);
                } else {
                    console.error('No product ID found for this card');
                }
            });
        });

        document.getElementById('checkout-button').addEventListener('click', function() {
            if (document.querySelector('.cart-items').children.length === 0 || 
                document.querySelector('.cart-items').innerHTML.includes('Carrinho vazio')) {
                alert('Seu carrinho está vazio!');
                return;
            }
            
            fetch('carrinho.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'acao=pagar'
            })
            .then(response => response.json())
            .then(session => {
                if (session.success === false) {
                    alert('Erro: ' + session.error);
                    return;
                }
                return stripe.redirectToCheckout({ sessionId: session.id });
            })
            .then(result => {
                if (result && result.error) {
                    alert(result.error.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocorreu um erro ao processar o pagamento. Tente novamente.');
            });
        });
    </script>
</body>
</html>