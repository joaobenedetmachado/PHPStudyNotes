<?php
require_once 'database.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraria Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
        .swiper-container {
            overflow: hidden;
        }
        .swiper-button-next,
        .swiper-button-prev {
            color: #1d4ed8 !important;
        }
        .swiper-pagination-bullet-active {
            background: #1d4ed8 !important;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-blue-800 text-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <img style="height: 60px" src="https://em-content.zobj.net/source/apple/81/books_1f4da.png" alt="Logo Livraria">
                    <a href="index.php" class="text-2xl font-bold">Livraria Online</a>
                </div>
                <div class="flex items-center space-x-4">
                    <form action="index.php" method="GET" class="flex">
                        <input type="text" name="busca" placeholder="Pesquisar livros..." 
                               class="px-4 py-2 rounded-l-md text-black focus:outline-none" 
                               value="<?php echo isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : ''; ?>">
                        <select name="filtro" class="px-2 py-2 bg-white text-black">
                            <option value="titulo" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'titulo') ? 'selected' : ''; ?>>Título</option>
                            <option value="autor" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'autor') ? 'selected' : ''; ?>>Autor</option>
                            <option value="editora" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'editora') ? 'selected' : ''; ?>>Editora</option>
                            <option value="categoria" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'categoria') ? 'selected' : ''; ?>>Categoria</option>
                        </select>
                        <button type="submit" class="bg-blue-600 px-4 py-2 rounded-r-md hover:bg-blue-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <nav class="flex items-center space-x-4">
                        <a href="index.php" class="hover:text-blue-200">Home</a>
                        
                        <?php if (isLoggedIn()): ?>
                            <a href="carrinho.php" class="hover:text-blue-200 relative">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    <?php 
                                    $itens = getCarrinho();
                                    echo count($itens);
                                    ?>
                                </span>
                            </a>
                            <?php if (isAdmin()): ?>
                                <a href="admin/index.php" class="hover:text-blue-200">Admin</a>
                            <?php endif; ?>
                            <a href="logout.php" class="hover:text-blue-200">Sair</a>
                        <?php else: ?>
                            <a href="login.php" class="hover:text-blue-200">Login</a>
                            <a href="registro.php" class="hover:text-blue-200">Registrar</a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    
    <main class="container mx-auto flex-grow p-4">
        <!-- Flash Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?> 
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.swiper-container', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
</body>
</html> 