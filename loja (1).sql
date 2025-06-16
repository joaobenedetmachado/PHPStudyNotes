
CREATE DATABASE 'loja'



CREATE TABLE IF NOT EXISTS `carrinho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_produto` (`id_produto`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `categoria` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;



INSERT INTO `categoria` (`codigo`, `nome`) VALUES
(1, 'Masculino'),
(2, 'Feminino'),
(3, 'Infantil');



CREATE TABLE IF NOT EXISTS `marca` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=322 ;

INSERT INTO `marca` (`codigo`, `nome`) VALUES
(1, 'Adidas'),
(2, 'Nike'),
(3, 'Channel'),
(123, 'Vans'),
(321, 'Chicco');



CREATE TABLE IF NOT EXISTS `produto` (
  `codigo` int(5) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `tamanho` varchar(10) NOT NULL,
  `preco` float(10,2) NOT NULL,
  `codmarca` int(5) NOT NULL,
  `codcategoria` int(5) NOT NULL,
  `codtipo` int(5) NOT NULL,
  `foto1` varchar(100) NOT NULL,
  `foto2` varchar(100) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `codmarca` (`codmarca`),
  KEY `codtipo` (`codtipo`),
  KEY `codcategoria` (`codcategoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6545 ;



INSERT INTO `produto` (`codigo`, `descricao`, `cor`, `tamanho`, `preco`, `codmarca`, `codcategoria`, `codtipo`, `foto1`, `foto2`) VALUES
(2, 'Camiseta', 'Preta', 'G', 23.00, 1, 1, 1, 'c33a3ac60add535c1da5980da78ff449', '152cda0d40d90f25a6bb3ac882806490'),
(4, 'Body Lindo', 'Rosa', 'PP', 199.00, 3, 2, 1, 'f5ea081e285505a2b4bc563d2023d6a4', 'ebecf3a2f7696c030b87afc84081d166'),
(123, 'Tenis Vans', 'Preto', '40', 199.00, 123, 1, 1, 'a9a0e167c4237d4cf3acc3e242b6f3d5', '100194a1357b31ebe311a98364e77574'),
(543, 'Leg Linderrima de esportes', 'Rosa', 'P', 67.00, 3, 2, 1, '4f5f3b5be15ae2ec40603278015ae7e4', '5fa3a1bec1b6ef37e6568e2309dd5138'),
(856, 'Chupeta FOTIBOLISTICA', 'azul', '10cm', 18.00, 321, 3, 1, 'ae627e6a332737357cec481d0f995b3d', '14a613473a35edf3f90cd1f4a1b8e8fa'),
(6544, 'Conjunto Baby', 'Branco', 'PP', 42.00, 321, 3, 1, 'c8b124ef5087aa6af436fe3363c084a9', '1c997be04395ea4a8111175aee2eee2d');



CREATE TABLE IF NOT EXISTS `tipo` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;



INSERT INTO `tipo` (`codigo`, `nome`) VALUES
(1, 'Roupas'),
(2, '');

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `usuario` (`id`, `email`, `senha`, `tipo`) VALUES
(1, 'jvbm2177@gmail.com', '123123', 'admin');


ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`codigo`),
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`codmarca`) REFERENCES `marca` (`codigo`),
  ADD CONSTRAINT `produto_ibfk_2` FOREIGN KEY (`codcategoria`) REFERENCES `categoria` (`codigo`),
  ADD CONSTRAINT `produto_ibfk_3` FOREIGN KEY (`codtipo`) REFERENCES `tipo` (`codigo`);
