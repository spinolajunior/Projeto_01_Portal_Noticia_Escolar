USE portal_aristides_maltez;

INSERT INTO credenciais (usuario, senha, criado_em, last_login, ativo) VALUES
('ana.clara.souza', SHA2('Girassol#2026', 256), '2026-01-10', '2026-07-20', TRUE),
('bruno.henrique', SHA2('Caderno!Azul91', 256), '2026-01-11', '2026-07-21', TRUE),
('camila.rocha', SHA2('Lua&Estrela2026', 256), '2026-01-12', '2026-07-22', TRUE),
('davi.martins', SHA2('Ponte$Verde18', 256), '2026-01-13', '2026-07-23', TRUE),
('eloisa.nascimento', SHA2('Jardim@Flor88', 256), '2026-01-14', '2026-07-24', TRUE),
('felipe.almeida', SHA2('Cultura#Bahia09', 256), '2026-01-15', '2026-07-25', TRUE),
('gabriela.santos', SHA2('Lapis!Roxo47', 256), '2026-01-16', '2026-07-26', TRUE),
('heitor.costa', SHA2('Marinho$Sol52', 256), '2026-01-17', '2026-07-26', TRUE),
('isadora.lima', SHA2('Borboleta@31', 256), '2026-01-18', NULL, TRUE),
('joao.miguel', SHA2('Aventura#2026', 256), '2026-01-19', NULL, TRUE),

('marina.ferreira', SHA2('Gestao!Escolar01', 256), '2026-01-20', '2026-07-26', TRUE),
('carlos.oliveira', SHA2('Direcao#Ativa02', 256), '2026-01-21', '2026-07-26', TRUE),
('renata.moraes', SHA2('Pedagogia@2026', 256), '2026-01-22', '2026-07-26', TRUE),
('paulo.ribeiro', SHA2('Secretaria$03', 256), '2026-01-23', '2026-07-26', TRUE),
('luciana.barros', SHA2('Educacao!04', 256), '2026-01-24', '2026-07-26', TRUE),
('marcos.silveira', SHA2('Biblioteca#05', 256), '2026-01-25', NULL, TRUE),
('patricia.andrade', SHA2('Orientacao@06', 256), '2026-01-26', NULL, TRUE),
('ricardo.gomes', SHA2('Tecnologia$07', 256), '2026-01-27', NULL, TRUE),
('vanessa.freitas', SHA2('Atendimento!08', 256), '2026-01-28', NULL, TRUE),
('thiago.mendes', SHA2('Escola#Segura09', 256), '2026-01-29', NULL, TRUE);

INSERT INTO tipo_adm (cargo, nivel_acesso) VALUES
('Diretora Geral', 10),
('Vice-Diretor', 9),
('Coordenadora Pedagógica', 8),
('Secretário Escolar', 7),
('Professora', 6),
('Bibliotecário', 5),
('Orientadora Educacional', 5),
('Assistente Administrativo', 4),
('Técnico de Informática', 4),
('Recepcionista', 3);

INSERT INTO contato (tel, email) VALUES
('71991234501', 'marina.ferreira@portal-aristides.edu.br'),
('71991234502', 'carlos.oliveira@portal-aristides.edu.br'),
('71991234503', 'renata.moraes@portal-aristides.edu.br'),
('71991234504', 'paulo.ribeiro@portal-aristides.edu.br'),
('71991234505', 'luciana.barros@portal-aristides.edu.br'),
('71991234506', 'marcos.silveira@portal-aristides.edu.br'),
('71991234507', 'patricia.andrade@portal-aristides.edu.br'),
('71991234508', 'ricardo.gomes@portal-aristides.edu.br'),
('71991234509', 'vanessa.freitas@portal-aristides.edu.br'),
('71991234510', 'thiago.mendes@portal-aristides.edu.br'),
('7132011001', 'contato@emjoaodasilva.edu.br'),
('7132011002', 'contato@empaulofreire.edu.br'),
('7132011003', 'contato@emcorafernandes.edu.br'),
('7132011004', 'contato@emcastelobranco.edu.br'),
('7132011005', 'contato@emdorivalcaymmi.edu.br'),
('7132011006', 'contato@emmanuelzubeda.edu.br'),
('7132011007', 'contato@emmiltonmattos.edu.br'),
('7132011008', 'contato@emrubemsbraga.edu.br'),
('7132011009', 'contato@emsergiobuarque.edu.br'),
('7132011010', 'contato@emzildaarns.edu.br');

INSERT INTO endereco (cidade, cep, bairro, rua, complemento) VALUES
('Salvador', '40110-010', 'Graça', 'Rua Almirante Barroso', 'Casa 45'),
('Salvador', '40230-120', 'Brotas', 'Rua Waldemar Falcão', 'Apartamento 302'),
('Salvador', '40170-030', 'Federação', 'Rua Professor Sabino Silva', 'Casa 18'),
('Salvador', '40301-110', 'Nazaré', 'Rua do Carro', 'Casa 90'),
('Salvador', '41810-150', 'Pituba', 'Rua das Hortênsias', 'Apartamento 504'),
('Salvador', '40155-220', 'Barra', 'Rua Marquês de Caravelas', 'Casa 12'),
('Salvador', '41150-060', 'Cabula', 'Rua Silveira Martins', 'Casa 210'),
('Salvador', '41940-470', 'Itapuã', 'Rua da Música', 'Casa 33'),
('Salvador', '40323-280', 'Liberdade', 'Rua Lima e Silva', 'Casa 71'),
('Salvador', '40140-330', 'Ondina', 'Avenida Oceânica', 'Apartamento 205'),
('Salvador', '40015-080', 'Centro', 'Rua Chile', 'Prédio Escolar'),
('Salvador', '40285-010', 'Brotas', 'Avenida Dom João VI', 'Unidade Escolar'),
('Salvador', '41745-020', 'Piatã', 'Rua da Esperança', 'Prédio Principal'),
('Salvador', '40415-180', 'Ribeira', 'Rua do Imperador', 'Unidade Escolar'),
('Salvador', '40170-110', 'Federação', 'Rua Basílio da Gama', 'Prédio Principal'),
('Salvador', '40370-150', 'Lapinha', 'Rua do Carmo', 'Unidade Escolar'),
('Salvador', '41195-060', 'Sussuarana', 'Rua das Palmeiras', 'Prédio Principal'),
('Salvador', '41830-490', 'Costa Azul', 'Rua do Canal', 'Unidade Escolar'),
('Salvador', '40301-030', 'Barris', 'Rua General Labatut', 'Prédio Principal'),
('Salvador', '40150-250', 'Canela', 'Rua Augusto Viana', 'Unidade Escolar');

INSERT INTO aluno (nome, matricula, data_nascimento, serie, id_credenciais) VALUES
('Ana Clara Souza', '2026A701', '2012-03-14', '7º Ano', 1),
('Bruno Henrique Santos', '2026A802', '2011-08-22', '8º Ano', 2),
('Camila Rocha Nascimento', '2026A603', '2013-01-30', '6º Ano', 3),
('Davi Martins Pereira', '2026A904', '2010-05-18', '9º Ano', 4),
('Eloísa Nascimento Lima', '2026A705', '2012-11-05', '7º Ano', 5),
('Felipe Almeida Costa', '2026A806', '2011-09-12', '8º Ano', 6),
('Gabriela Santos Araújo', '2026A607', '2013-06-25', '6º Ano', 7),
('Heitor Costa Ribeiro', '2026A908', '2010-02-10', '9º Ano', 8),
('Isadora Lima Ferreira', '2026A709', '2012-07-19', '7º Ano', 9),
('João Miguel Reis', '2026A810', '2011-12-03', '8º Ano', 10);

INSERT INTO administrador
(nome, matricula, cpf, id_credenciais, id_tipo_adm, id_contato, id_endereco) VALUES
('Marina Ferreira Costa', 'ADM2026001', '100.000.000-01', 11, 1, 1, 1),
('Carlos Eduardo Oliveira', 'ADM2026002', '100.000.000-02', 12, 2, 2, 2),
('Renata Moraes Almeida', 'ADM2026003', '100.000.000-03', 13, 3, 3, 3),
('Paulo Henrique Ribeiro', 'ADM2026004', '100.000.000-04', 14, 4, 4, 4),
('Luciana Barros Santos', 'ADM2026005', '100.000.000-05', 15, 5, 5, 5),
('Marcos Vinícius Silveira', 'ADM2026006', '100.000.000-06', 16, 6, 6, 6),
('Patrícia Andrade Lima', 'ADM2026007', '100.000.000-07', 17, 7, 7, 7),
('Ricardo Gomes Nunes', 'ADM2026008', '100.000.000-08', 18, 8, 8, 8),
('Vanessa Freitas Rocha', 'ADM2026009', '100.000.000-09', 19, 9, 9, 9),
('Thiago Mendes Carvalho', 'ADM2026010', '100.000.000-10', 20, 10, 10, 10);

-- GIF de demonstração armazenado como BLOB
INSERT INTO noticia (titulo, subtitulo, descricao, imagem, data_pub, status, id_administrador) VALUES
('Matrículas para 2026 estão abertas', 'Inscrições para novos estudantes', 'As famílias interessadas já podem procurar a secretaria com os documentos necessários.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-01-15', TRUE, 1),
('Reunião de responsáveis', 'Encontro do primeiro trimestre', 'A reunião acontecerá no auditório principal às 18h30.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-02-10', TRUE, 2),
('Feira de Ciências recebe inscrições', 'Projetos até 20 de março', 'Estudantes poderão apresentar experimentos e pesquisas desenvolvidas durante o semestre.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-03-01', TRUE, 3),
('Biblioteca amplia acervo juvenil', 'Novos títulos disponíveis', 'A biblioteca recebeu obras de literatura brasileira, aventura e ficção científica.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-03-18', TRUE, 6),
('Jogos Interclasses começam em abril', 'Esporte e integração', 'As turmas participarão de competições de futsal, vôlei e atletismo.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-04-02', TRUE, 5),
('Oficina de programação para estudantes', 'Tecnologia na escola', 'O laboratório promoverá uma oficina introdutória de lógica e programação.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-04-22', TRUE, 8),
('Campanha do agasalho é iniciada', 'Solidariedade', 'A comunidade escolar pode entregar roupas e cobertores na recepção.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-05-05', TRUE, 7),
('Festa junina terá apresentações', 'Cultura popular', 'As turmas estão preparando danças, comidas típicas e brincadeiras para as famílias.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-06-01', TRUE, 4),
('Olimpíada de Matemática premia alunos', 'Destaque acadêmico', 'Estudantes receberam certificados pelo desempenho na etapa municipal.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-06-25', TRUE, 9),
('Recesso escolar de julho', 'Calendário acadêmico', 'As atividades serão retomadas no primeiro dia útil após o período de recesso.', X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', '2026-07-01', TRUE, 10);

INSERT INTO aviso (titulo, descricao, data_aviso, validade, prioridade, status, id_administrador) VALUES
('Uso obrigatório do uniforme', 'Todos os estudantes devem utilizar o uniforme completo durante o período de aula.', '2026-02-01', '2026-12-20', 3, TRUE, 1),
('Atualização de dados cadastrais', 'Responsáveis devem revisar telefone, endereço e contatos de emergência na secretaria.', '2026-02-05', '2026-03-15', 2, TRUE, 2),
('Prazo para rematrícula', 'A rematrícula deve ser confirmada até a data informada pela secretaria escolar.', '2026-03-10', '2026-04-10', 3, TRUE, 4),
('Entrega de boletins', 'Os boletins do primeiro trimestre serão disponibilizados aos responsáveis.', '2026-04-01', '2026-04-20', 2, TRUE, 3),
('Manutenção do portal', 'O portal poderá apresentar instabilidade durante a manutenção programada.', '2026-04-20', '2026-04-21', 1, TRUE, 8),
('Documentação pendente', 'Verifique na secretaria se há documentos pendentes no cadastro do estudante.', '2026-05-01', '2026-05-31', 2, TRUE, 4),
('Campanha de vacinação', 'Entregue uma cópia atualizada da carteira de vacinação quando solicitado.', '2026-05-15', '2026-06-15', 3, TRUE, 7),
('Devolução de livros', 'Livros emprestados pela biblioteca devem ser devolvidos até a data prevista.', '2026-06-01', '2026-06-30', 2, TRUE, 6),
('Conselho de classe', 'As aulas serão encerradas mais cedo para realização do conselho de classe.', '2026-06-20', '2026-06-21', 2, TRUE, 5),
('Período de recesso', 'A secretaria terá atendimento reduzido durante o recesso escolar de julho.', '2026-07-01', '2026-07-15', 3, TRUE, 10);

INSERT INTO escola
(nome, cod_inep, ano_letivo, logo_img, id_contato, id_endereco) VALUES
('Escola Municipal João da Silva', '2900000001', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 11, 11),
('Escola Municipal Paulo Freire', '2900000002', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 12, 12),
('Escola Municipal Cora Fernandes', '2900000003', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 13, 13),
('Escola Municipal Castelo Branco', '2900000004', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 14, 14),
('Escola Municipal Dorival Caymmi', '2900000005', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 15, 15),
('Escola Municipal Zilda Arns', '2900000006', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 16, 16),
('Escola Municipal Milton Mattos', '2900000007', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 17, 17),
('Escola Municipal Rubem Braga', '2900000008', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 18, 18),
('Escola Municipal Sérgio Buarque', '2900000009', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 19, 19),
('Escola Municipal Anísio Teixeira', '2900000010', 2026, X'47494638396101000100800000FFFFFF00000021F90401000000002C00000000010001000002024401003B', 20, 20);