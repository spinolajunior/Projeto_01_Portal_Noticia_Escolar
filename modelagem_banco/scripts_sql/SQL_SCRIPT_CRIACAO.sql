
use portal_aristides_maltez;

CREATE TABLE credenciais (
    id int AUTO_INCREMENT NOT NULL,
	usuario varchar(100) NOT NULL,
    senha varchar(255) NOT NULL,
    criado_em datetime DEFAULT CURRENT_TIMESTAMP,
    last_login datetime,
    ativo bool default true,
    PRIMARY KEY(id),
    UNIQUE(usuario),
    UNIQUE(senha)
);

CREATE TABLE aluno (
    id int AUTO_INCREMENT NOT NULL,
    nome varchar(100) NOT NULL,
    matricula varchar(100) NOT NULL,
    data_nascimento date NOT NULL,
    serie varchar(100) NOT NULL,
    id_credenciais int,
    PRIMARY KEY(id),
    FOREIGN KEY(id_credenciais) REFERENCES credenciais(id),
    UNIQUE(matricula),
    UNIQUE(id_credenciais)
);

CREATE TABLE tipo_adm (
    id int AUTO_INCREMENT NOT NULL,
    cargo varchar(100) NOT NULL,
    nivel_acesso int NOT NULL,
    PRIMARY KEY(id),
    UNIQUE(cargo)
);

CREATE TABLE contato (
    id int AUTO_INCREMENT NOT NULL,
    tel varchar(50) NOT NULL,
    email varchar(150) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE(tel),
    UNIQUE(email)
);

CREATE TABLE endereco (
    id int AUTO_INCREMENT NOT NULL,
    cidade varchar(50) NOT NULL,
    cep varchar(50) NOT NULL,
    bairro varchar(50) NOT NULL,
    rua varchar(50)NOT NULL,
    complemento varchar(100),
    PRIMARY KEY(id)
);

CREATE TABLE administrador (
    id int AUTO_INCREMENT NOT NULL,
    nome varchar(100) NOT NULL,
    matricula varchar(100) NOT NULL,
    cpf varchar(50) NOT NULL,
    id_credenciais int,
    id_tipo_adm int,
    id_contato int,
    id_endereco int,
    PRIMARY KEY(id),
    FOREIGN KEY(id_credenciais) REFERENCES credenciais(id),
    FOREIGN KEY(id_contato) REFERENCES contato(id),
    FOREIGN KEY(id_tipo_adm) REFERENCES tipo_adm(id),
    FOREIGN KEY(id_endereco) REFERENCES endereco(id),
    UNIQUE(matricula),
    UNIQUE(cpf),
    UNIQUE(id_credenciais),
    UNIQUE(id_endereco),
    UNIQUE(id_contato)
);

CREATE TABLE noticia (
    id int AUTO_INCREMENT NOT NULL,
    titulo varchar(50) NOT NULL,
    subtitulo varchar(100),
    descricao longtext NOT NULL,
    imagem mediumblob NOT NULL,
    data_pub datetime DEFAULT CURRENT_TIMESTAMP,
    status bool,
    id_administrador int,
    PRIMARY KEY(id),
    FOREIGN KEY(id_administrador) REFERENCES administrador(id)
);

CREATE TABLE aviso (
    id int AUTO_INCREMENT NOT NULL,
    titulo varchar(50) NOT NULL,
    descricao longtext NOT NULL,
    data_aviso date,
    validade date NOT NULL,
    prioridade int NOT NULL,
    status bool DEFAULT TRUE,
    id_administrador int,
    PRIMARY KEY(id),
    FOREIGN KEY(id_administrador) REFERENCES administrador(id)
);

CREATE TABLE escola (
    id int AUTO_INCREMENT NOT NULL,
    nome varchar(100) NOT NULL,
    cod_inep varchar(100) NOT NULL,
    ano_letivo year NOT NULL,
    logo_img mediumblob,
    id_contato int NOT NULL,
    id_endereco int NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(id_contato) REFERENCES contato(id),
    FOREIGN KEY(id_endereco) REFERENCES endereco(id),
    UNIQUE(cod_inep),
    UNIQUE(id_contato),
    UNIQUE(id_endereco)
);
 
