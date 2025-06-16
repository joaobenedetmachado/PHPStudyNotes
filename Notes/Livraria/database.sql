-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS livraria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE livraria;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    admin TINYINT(1) DEFAULT 0,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS editoras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    biografia TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS livros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    id_autor INT,
    id_editora INT,
    id_categoria INT,
    preco DECIMAL(10,2) NOT NULL,
    quantidade_estoque INT DEFAULT 0,
    ano_publicacao INT,
    isbn VARCHAR(20),
    descricao TEXT,
    imagem VARCHAR(255),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_autor) REFERENCES autores(id),
    FOREIGN KEY (id_editora) REFERENCES editoras(id),
    FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

CREATE TABLE IF NOT EXISTS carrinhos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('aberto', 'finalizado') DEFAULT 'aberto',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS itens_carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_carrinho INT,
    id_livro INT,
    quantidade INT DEFAULT 1,
    preco_unitario DECIMAL(10,2),
    FOREIGN KEY (id_carrinho) REFERENCES carrinhos(id),
    FOREIGN KEY (id_livro) REFERENCES livros(id)
);

INSERT INTO usuarios (nome, email, senha, admin) VALUES 
('Administrador', 'admin@livraria.com', '0192023a7bbd73250516f069df18b500', 1);

INSERT INTO categorias (nome, descricao) VALUES 
('Ficção', 'Livros de ficção em geral'),
('Não-Ficção', 'Livros de não-ficção em geral'),
('Infantil', 'Livros para o público infantil'),
('Romance', 'Livros de romance'),
('Fantasia', 'Livros de fantasia'),
('Ciência', 'Livros de ciência');

INSERT INTO editoras (nome, descricao) VALUES 
('Companhia das Letras', 'Editora brasileira de renome'),
('Rocco', 'Editora especializada em ficção'),
('Saraiva', 'Editora com amplo catálogo'),
('Intrínseca', 'Editora de livros jovens e best-sellers');

INSERT INTO autores (nome, biografia) VALUES 
('J.K. Rowling', 'Autora da série Harry Potter'),
('Machado de Assis', 'Escritor brasileiro, fundador da Academia Brasileira de Letras'),
('Clarice Lispector', 'Escritora ucraniana-brasileira aclamada por seus romances'),
('George R.R. Martin', 'Autor da série As Crônicas de Gelo e Fogo');

INSERT INTO livros (titulo, id_autor, id_editora, id_categoria, preco, quantidade_estoque, ano_publicacao, isbn, descricao, imagem) VALUES 
('Harry Potter e a Pedra Filosofal', 1, 2, 5, 49.90, 20, 1997, '9788532511010', 'O primeiro livro da série Harry Potter', 'harry_potter_1.jpg'),
('Dom Casmurro', 2, 1, 4, 35.90, 15, 1899, '9788535910934', 'Um dos romances mais importantes da literatura brasileira', 'dom_casmurro.jpg'),
('A Hora da Estrela', 3, 1, 4, 29.90, 10, 1977, '9788535911862', 'Último romance publicado por Clarice Lispector', 'hora_estrela.jpg'),
('A Guerra dos Tronos', 4, 4, 5, 69.90, 8, 1996, '9788580443295', 'Primeiro volume da série As Crônicas de Gelo e Fogo', 'got_1.jpg'); 