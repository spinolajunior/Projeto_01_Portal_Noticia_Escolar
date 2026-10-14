create database portal_CETIDAM charset utf8mb4 collate utf8mb4_bin;
use portal_CETIDAM;

CREATE TABLE credenciais (
    id int AUTO_INCREMENT NOT NULL,
	usuario varchar(100) NOT NULL,
    senha varchar(255) NOT NULL,
    criado_em datetime DEFAULT CURRENT_TIMESTAMP,
    last_login datetime,
    ativo bool default true,
    PRIMARY KEY(id),
    UNIQUE(senha),
    UNIQUE(usuario)
);

CREATE TABLE logs(
	id int auto_increment,
    evento varchar(100) not null,
    descricao longtext,
    ip varchar(45),
    horario datetime default current_timestamp,
    id_credenciais int,
    primary key(id),
    foreign key(id_credenciais) references credenciais(id)
    );
CREATE TABLE ano (
	id int AUTO_INCREMENT NOT NULL,
    nome varchar(100) not null,
    PRIMARY KEY(id));

CREATE TABLE aluno (
    id int AUTO_INCREMENT NOT NULL,
    nome varchar(100) NOT NULL,
    matricula varchar(100) NOT NULL,
    data_nascimento date NOT NULL,
    id_credenciais int not null,
    id_ano int not null,
    PRIMARY KEY(id),
    FOREIGN KEY(id_credenciais) REFERENCES credenciais(id),
    FOREIGN KEY(id_ano) REFERENCES ano(id),
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


CREATE TABLE administrador (
    id int AUTO_INCREMENT NOT NULL,
    nome varchar(100) NOT NULL,
    matricula varchar(100) NOT NULL,
    cpf varchar(50) NOT NULL,
    id_credenciais int,
    id_tipo_adm int,
    PRIMARY KEY(id),
    FOREIGN KEY(id_credenciais) REFERENCES credenciais(id),
    FOREIGN KEY(id_tipo_adm) REFERENCES tipo_adm(id),
    UNIQUE(matricula),
    UNIQUE(cpf),
    UNIQUE(id_credenciais)
);

CREATE TABLE noticia (
    id int AUTO_INCREMENT NOT NULL,
    titulo varchar(50) NOT NULL,
    subtitulo varchar(100),
    descricao longtext NOT NULL,
    imagem mediumblob NOT NULL,
    data_pub datetime DEFAULT CURRENT_TIMESTAMP,
    status bool,
    id_administrador int NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(id_administrador) REFERENCES administrador(id)
);

CREATE TABLE aviso (
    id int AUTO_INCREMENT NOT NULL,
    titulo varchar(50) NOT NULL,
    descricao longtext NOT NULL,
    ativo bool default true,
    id_administrador int not null,
    PRIMARY KEY(id),
    FOREIGN KEY(id_administrador) REFERENCES administrador(id)
);

CREATE TABLE evento(
id int AUTO_INCREMENT NOT NULL,
titulo varchar(50),
data_evento date not null,
id_administrador int not null,
PRIMARY KEY(id),
foreign key(id_administrador) references administrador(id)

);

CREATE TABLE escola (
    id int AUTO_INCREMENT NOT NULL,
    nome varchar(100) NOT NULL,
    cod_inep varchar(100) NOT NULL,
    ano_letivo year NOT NULL,
    logo_img mediumblob,
    PRIMARY KEY(id),
    UNIQUE(cod_inep)
);
 