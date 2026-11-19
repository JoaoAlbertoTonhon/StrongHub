-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/11/2026 às 11:37
-- Versão do servidor: 10.11.16-MariaDB
-- Versão do PHP: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `wellhub`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `academia`
--

CREATE TABLE `academia` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `horarioAbertura` time DEFAULT NULL,
  `horarioFechamento` time DEFAULT NULL,
  `imagem` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `academia`
--

INSERT INTO `academia` (`id`, `nome`, `endereco`, `cidade`, `horarioAbertura`, `horarioFechamento`, `imagem`) VALUES
(1, 'Black Eagle Training Center', 'R. Donato Armelin, 1174 - Vila Liberdade', 'Presidente Prudente', '06:00:00', '22:00:00', 'black-eagle.jpg'),
(2, 'B3 Box Academia', 'Av. Quatorze de Setembro, 136 - Uep1-S.2', 'Presidente Prudente', '06:00:00', '22:00:00', 'b3-box.jpg'),
(3, 'Malibu Presidente Prudente', 'Av. Brasil, 60 - Vila Jesus', 'Presidente Prudente', '06:00:00', '22:00:00', 'malibu.jpg'),
(4, 'BlueFit 24h', 'Av. Cel. José Soares Marcondes, 3139 - Jardim Bongiovani', 'Presidente Prudente', '00:00:00', '23:59:00', 'bluefit.jpg'),
(5, 'Academia Cross Point', 'R. Risick Buchalla, 41 - Jardim dos Pioneiros', 'Presidente Prudente', '06:00:00', '22:00:00', 'cross-point.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) DEFAULT NULL,
  `aula_id` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `dataNascimento` date DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `senha` varchar(20) DEFAULT NULL,
  `objetivo` varchar(30) DEFAULT NULL,
  `nivel` varchar(20) DEFAULT NULL,
  `altura` decimal(4,2) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `condicoesSaude` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`id`, `nome`, `cpf`, `dataNascimento`, `idade`, `endereco`, `telefone`, `email`, `senha`, `objetivo`, `nivel`, `altura`, `peso`, `condicoesSaude`) VALUES
(1, 'Aluno Teste', '000.000.000-00', '2000-01-01', 26, 'Rua Exemplo, 100', '18999999999', 'aluno@teste.com', '1234', 'hipertrofia', 'iniciante', 1.79, 77.00, 'sem limitações'),
(2, 'Aluno Basico', '111.111.111-11', '2000-02-02', 26, 'Av. Teste, 200', '18988888888', 'basico@teste.com', '1234', 'emagrecimento', 'iniciante', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `assinatura`
--

CREATE TABLE `assinatura` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) DEFAULT NULL,
  `plano_id` int(11) DEFAULT NULL,
  `dataInicio` date DEFAULT NULL,
  `dataFim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `assinatura`
--

INSERT INTO `assinatura` (`id`, `aluno_id`, `plano_id`, `dataInicio`, `dataFim`) VALUES
(1, 1, 2, '2026-11-19', '2026-12-19'),
(2, 2, 1, '2026-11-19', '2026-12-19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `aula`
--

CREATE TABLE `aula` (
  `id` int(11) NOT NULL,
  `academia_id` int(11) DEFAULT NULL,
  `modalidade` varchar(50) DEFAULT NULL,
  `diaSemana` varchar(15) DEFAULT NULL,
  `horario` time DEFAULT NULL,
  `vagas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aula`
--

INSERT INTO `aula` (`id`, `academia_id`, `modalidade`, `diaSemana`, `horario`, `vagas`) VALUES
(1, 1, 'Musculação orientada', 'segunda', '07:00:00', 10),
(2, 1, 'Treinamento funcional', 'quarta', '18:00:00', 12),
(3, 1, 'Treinamento funcional', 'sexta', '07:00:00', 12),
(4, 2, 'Crossfit', 'terca', '19:00:00', 15),
(5, 2, 'Crossfit', 'quinta', '19:00:00', 15),
(6, 2, 'Crossfit', 'sabado', '09:00:00', 12),
(7, 3, 'Treino híbrido', 'segunda', '18:30:00', 14),
(8, 3, 'Spinning', 'quarta', '07:00:00', 10),
(9, 3, 'Treino híbrido', 'sexta', '18:30:00', 14),
(10, 4, 'Musculação com peso livre', 'terca', '10:00:00', 20),
(11, 4, 'Cardio', 'quinta', '06:00:00', 20),
(12, 4, 'Musculação com peso livre', 'sabado', '14:00:00', 20),
(13, 5, 'Funcional HIIT', 'segunda', '06:30:00', 12),
(14, 5, 'Funcional HIIT', 'quarta', '06:30:00', 12),
(15, 5, 'Funcional HIIT', 'sexta', '18:00:00', 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `checkin`
--

CREATE TABLE `checkin` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) DEFAULT NULL,
  `academia_id` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `disponibilidade_aluno`
--

CREATE TABLE `disponibilidade_aluno` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `diaSemana` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `disponibilidade_aluno`
--

INSERT INTO `disponibilidade_aluno` (`id`, `aluno_id`, `diaSemana`) VALUES
(13, 1, 'quarta'),
(14, 1, 'quinta'),
(11, 1, 'segunda'),
(15, 1, 'sexta'),
(12, 1, 'terca');

-- --------------------------------------------------------

--
-- Estrutura para tabela `plano`
--

CREATE TABLE `plano` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `valor` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `plano`
--

INSERT INTO `plano` (`id`, `nome`, `descricao`, `valor`) VALUES
(1, 'Plano Básico', 'Acesso às academias básicas parceiras', 69.9),
(2, 'Plano Premium', 'Acesso a todas as academias parceiras', 149.9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `plano_academia`
--

CREATE TABLE `plano_academia` (
  `id` int(11) NOT NULL,
  `plano_id` int(11) DEFAULT NULL,
  `academia_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `plano_academia`
--

INSERT INTO `plano_academia` (`id`, `plano_id`, `academia_id`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 2, 3),
(4, 2, 4),
(5, 2, 5),
(8, 1, 1),
(9, 1, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `treino`
--

CREATE TABLE `treino` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `arquivo` varchar(255) NOT NULL,
  `dataCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `academia`
--
ALTER TABLE `academia`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`),
  ADD KEY `aula_id` (`aula_id`);

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `assinatura`
--
ALTER TABLE `assinatura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`),
  ADD KEY `plano_id` (`plano_id`);

--
-- Índices de tabela `aula`
--
ALTER TABLE `aula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academia_id` (`academia_id`);

--
-- Índices de tabela `checkin`
--
ALTER TABLE `checkin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`),
  ADD KEY `academia_id` (`academia_id`);

--
-- Índices de tabela `disponibilidade_aluno`
--
ALTER TABLE `disponibilidade_aluno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_aluno_dia` (`aluno_id`,`diaSemana`);

--
-- Índices de tabela `plano`
--
ALTER TABLE `plano`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `plano_academia`
--
ALTER TABLE `plano_academia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plano_id` (`plano_id`),
  ADD KEY `academia_id` (`academia_id`);

--
-- Índices de tabela `treino`
--
ALTER TABLE `treino`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `academia`
--
ALTER TABLE `academia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `assinatura`
--
ALTER TABLE `assinatura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `aula`
--
ALTER TABLE `aula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `checkin`
--
ALTER TABLE `checkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `disponibilidade_aluno`
--
ALTER TABLE `disponibilidade_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `plano`
--
ALTER TABLE `plano`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `plano_academia`
--
ALTER TABLE `plano_academia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `treino`
--
ALTER TABLE `treino`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`aula_id`) REFERENCES `aula` (`id`);

--
-- Restrições para tabelas `assinatura`
--
ALTER TABLE `assinatura`
  ADD CONSTRAINT `assinatura_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `assinatura_ibfk_2` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`);

--
-- Restrições para tabelas `aula`
--
ALTER TABLE `aula`
  ADD CONSTRAINT `aula_ibfk_1` FOREIGN KEY (`academia_id`) REFERENCES `academia` (`id`);

--
-- Restrições para tabelas `checkin`
--
ALTER TABLE `checkin`
  ADD CONSTRAINT `checkin_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `checkin_ibfk_2` FOREIGN KEY (`academia_id`) REFERENCES `academia` (`id`);

--
-- Restrições para tabelas `disponibilidade_aluno`
--
ALTER TABLE `disponibilidade_aluno`
  ADD CONSTRAINT `disponibilidade_aluno_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `plano_academia`
--
ALTER TABLE `plano_academia`
  ADD CONSTRAINT `plano_academia_ibfk_1` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`),
  ADD CONSTRAINT `plano_academia_ibfk_2` FOREIGN KEY (`academia_id`) REFERENCES `academia` (`id`);

--
-- Restrições para tabelas `treino`
--
ALTER TABLE `treino`
  ADD CONSTRAINT `treino_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
