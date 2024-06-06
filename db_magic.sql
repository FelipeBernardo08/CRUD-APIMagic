-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06-Jun-2024 às 05:29
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_magic`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carta`
--

CREATE TABLE `carta` (
  `id` int(11) NOT NULL,
  `id_edicao` int(11) NOT NULL,
  `nomePtBr` varchar(50) NOT NULL,
  `nomeIng` varchar(50) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `artista` varchar(50) NOT NULL,
  `raridade` varchar(50) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `qtd_estoque` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `edicao`
--

CREATE TABLE `edicao` (
  `id` int(11) NOT NULL,
  `nomePtBr` varchar(50) NOT NULL,
  `nomeIng` varchar(50) NOT NULL,
  `data_lancamento` varchar(10) NOT NULL,
  `quantidade_lancamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagens_carta`
--

CREATE TABLE `imagens_carta` (
  `id` int(11) NOT NULL,
  `img` varchar(100) NOT NULL,
  `id_carta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `token` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `nome`, `email`, `password`, `token`) VALUES
(1, 'ADM', 'adm@gmail.com', '$2y$10$P7s1mhUD6LiXHrOTyZyZ8ezWVTU3NfViZIrC3mmlL9xlUSltnWMcW', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carta`
--
ALTER TABLE `carta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_edicao` (`id_edicao`);

--
-- Índices para tabela `edicao`
--
ALTER TABLE `edicao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `imagens_carta`
--
ALTER TABLE `imagens_carta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_carta` (`id_carta`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carta`
--
ALTER TABLE `carta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `edicao`
--
ALTER TABLE `edicao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `imagens_carta`
--
ALTER TABLE `imagens_carta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `carta`
--
ALTER TABLE `carta`
  ADD CONSTRAINT `fk_edicao` FOREIGN KEY (`id_edicao`) REFERENCES `edicao` (`id`);

--
-- Limitadores para a tabela `imagens_carta`
--
ALTER TABLE `imagens_carta`
  ADD CONSTRAINT `fk_carta` FOREIGN KEY (`id_carta`) REFERENCES `carta` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
