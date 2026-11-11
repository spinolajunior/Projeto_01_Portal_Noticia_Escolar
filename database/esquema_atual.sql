-- Esquema após as migrações 001 e 002; sem dados.

CREATE TABLE `administrador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `matricula` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `id_credenciais` int DEFAULT NULL,
  `id_tipo_adm` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_credenciais` (`id_credenciais`),
  KEY `id_tipo_adm` (`id_tipo_adm`),
  CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_credenciais`) REFERENCES `credenciais` (`id`),
  CONSTRAINT `administrador_ibfk_2` FOREIGN KEY (`id_tipo_adm`) REFERENCES `tipo_adm` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `aluno` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `matricula` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `data_nascimento` date NOT NULL,
  `id_credenciais` int NOT NULL,
  `id_serie` int DEFAULT NULL,
  `id_tipo_adm` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_credenciais` (`id_credenciais`),
  UNIQUE KEY `uq_aluno_matricula` (`matricula`),
  KEY `id_serie` (`id_serie`),
  KEY `id_tipo_adm` (`id_tipo_adm`),
  CONSTRAINT `aluno_ibfk_1` FOREIGN KEY (`id_credenciais`) REFERENCES `credenciais` (`id`),
  CONSTRAINT `aluno_ibfk_3` FOREIGN KEY (`id_serie`) REFERENCES `serie` (`id`),
  CONSTRAINT `aluno_ibfk_4` FOREIGN KEY (`id_tipo_adm`) REFERENCES `tipo_adm` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `aviso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) COLLATE utf8mb4_bin NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `id_administrador` int DEFAULT NULL,
  `id_aluno_adm` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_administrador` (`id_administrador`),
  KEY `id_aluno_adm` (`id_aluno_adm`),
  CONSTRAINT `aviso_ibfk_1` FOREIGN KEY (`id_administrador`) REFERENCES `administrador` (`id`),
  CONSTRAINT `aviso_ibfk_2` FOREIGN KEY (`id_aluno_adm`) REFERENCES `aluno` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `credenciais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `foto` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `evento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `data_evento` date NOT NULL,
  `id_administrador` int DEFAULT NULL,
  `id_aluno_adm` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_administrador` (`id_administrador`),
  KEY `id_aluno_adm` (`id_aluno_adm`),
  CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`id_administrador`) REFERENCES `administrador` (`id`),
  CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`id_aluno_adm`) REFERENCES `aluno` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `noticia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `subtitulo` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `descricao` longtext COLLATE utf8mb4_bin NOT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `data_pub` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT NULL,
  `id_administrador` int DEFAULT NULL,
  `id_aluno_adm` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_administrador` (`id_administrador`),
  KEY `id_aluno_adm` (`id_aluno_adm`),
  CONSTRAINT `noticia_ibfk_1` FOREIGN KEY (`id_administrador`) REFERENCES `administrador` (`id`),
  CONSTRAINT `noticia_ibfk_2` FOREIGN KEY (`id_aluno_adm`) REFERENCES `aluno` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `serie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `tipo_adm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cargo` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `nivel_acesso` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cargo` (`cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
