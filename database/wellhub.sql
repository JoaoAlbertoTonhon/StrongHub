CREATE DATABASE IF NOT EXISTS wellhub;
USE wellhub;

CREATE TABLE aluno (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(50),
  cpf VARCHAR(15),
  dataNascimento DATE,
  idade INT,
  endereco VARCHAR(100),
  telefone VARCHAR(15),
  email VARCHAR(50),
  senha VARCHAR(20),
  objetivo VARCHAR(30),
  nivel VARCHAR(20),
  altura DECIMAL(4,2),
  peso DECIMAL(5,2),
  condicoesSaude TEXT
);

CREATE TABLE plano (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(50),
  descricao VARCHAR(150),
  valor FLOAT
);

CREATE TABLE academia (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(50),
  endereco VARCHAR(100),
  cidade VARCHAR(50),
  horarioAbertura TIME,
  horarioFechamento TIME,
  imagem VARCHAR(100)
);

CREATE TABLE assinatura (
  id INT PRIMARY KEY AUTO_INCREMENT,
  aluno_id INT,
  plano_id INT,
  dataInicio DATE,
  dataFim DATE,
  FOREIGN KEY (aluno_id) REFERENCES aluno(id),
  FOREIGN KEY (plano_id) REFERENCES plano(id)
);

CREATE TABLE plano_academia (
  id INT PRIMARY KEY AUTO_INCREMENT,
  plano_id INT,
  academia_id INT,
  FOREIGN KEY (plano_id) REFERENCES plano(id),
  FOREIGN KEY (academia_id) REFERENCES academia(id)
);

CREATE TABLE checkin (
  id INT PRIMARY KEY AUTO_INCREMENT,
  aluno_id INT,
  academia_id INT,
  data DATE,
  hora TIME,
  FOREIGN KEY (aluno_id) REFERENCES aluno(id),
  FOREIGN KEY (academia_id) REFERENCES academia(id)
);

CREATE TABLE aula (
  id INT PRIMARY KEY AUTO_INCREMENT,
  academia_id INT,
  modalidade VARCHAR(50),
  diaSemana VARCHAR(15),
  horario TIME,
  vagas INT,
  FOREIGN KEY (academia_id) REFERENCES academia(id)
);

CREATE TABLE agendamento (
  id INT PRIMARY KEY AUTO_INCREMENT,
  aluno_id INT,
  aula_id INT,
  data DATE,
  FOREIGN KEY (aluno_id) REFERENCES aluno(id),
  FOREIGN KEY (aula_id) REFERENCES aula(id)
);

CREATE TABLE disponibilidade_aluno (
    id INT PRIMARY KEY AUTO_INCREMENT,
    aluno_id INT NOT NULL,
    diaSemana VARCHAR(15) NOT NULL,
    UNIQUE KEY uq_aluno_dia (aluno_id, diaSemana),
    FOREIGN KEY (aluno_id)
    REFERENCES aluno(id)
    ON DELETE CASCADE
);

CREATE TABLE treino (
    id INT PRIMARY KEY AUTO_INCREMENT,
    aluno_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    arquivo VARCHAR(255) NOT NULL,
    dataCadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id)
    REFERENCES aluno(id)
    ON DELETE CASCADE
);

INSERT INTO plano (nome, descricao, valor) VALUES
('Plano Básico', 'Acesso às academias básicas parceiras', 69.90),
('Plano Premium', 'Acesso a todas as academias parceiras', 149.90);

INSERT INTO academia (nome, endereco, cidade, horarioAbertura, horarioFechamento, imagem) VALUES
('Black Eagle Training Center', 'R. Donato Armelin, 1174 - Vila Liberdade', 'Presidente Prudente', '06:00:00', '22:00:00', 'black-eagle.jpg'),
('B3 Box Academia', 'Av. Quatorze de Setembro, 136 - Uep1-S.2', 'Presidente Prudente', '06:00:00', '22:00:00', 'b3-box.jpg'),
('Malibu Presidente Prudente', 'Av. Brasil, 60 - Vila Jesus', 'Presidente Prudente', '06:00:00', '22:00:00', 'malibu.jpg'),
('BlueFit 24h', 'Av. Cel. José Soares Marcondes, 3139 - Jardim Bongiovani', 'Presidente Prudente', '00:00:00', '23:59:00', 'bluefit.jpg'),
('Academia Cross Point', 'R. Risick Buchalla, 41 - Jardim dos Pioneiros', 'Presidente Prudente', '06:00:00', '22:00:00', 'cross-point.jpg');

INSERT INTO plano_academia (plano_id, academia_id)
SELECT p.id, a.id FROM plano p JOIN academia a
WHERE p.nome = 'Plano Premium'
AND a.nome IN ('Black Eagle Training Center', 'B3 Box Academia', 'Malibu Presidente Prudente', 'BlueFit 24h', 'Academia Cross Point');

INSERT INTO plano_academia (plano_id, academia_id)
SELECT p.id, a.id FROM plano p JOIN academia a
WHERE p.nome = 'Plano Básico'
AND a.nome IN ('Black Eagle Training Center', 'Academia Cross Point');

INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Musculação orientada', 'segunda', '07:00:00', 10 FROM academia WHERE nome = 'Black Eagle Training Center';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Treinamento funcional', 'quarta', '18:00:00', 12 FROM academia WHERE nome = 'Black Eagle Training Center';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Treinamento funcional', 'sexta', '07:00:00', 12 FROM academia WHERE nome = 'Black Eagle Training Center';

INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Crossfit', 'terca', '19:00:00', 15 FROM academia WHERE nome = 'B3 Box Academia';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Crossfit', 'quinta', '19:00:00', 15 FROM academia WHERE nome = 'B3 Box Academia';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Crossfit', 'sabado', '09:00:00', 12 FROM academia WHERE nome = 'B3 Box Academia';

INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Treino híbrido', 'segunda', '18:30:00', 14 FROM academia WHERE nome = 'Malibu Presidente Prudente';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Spinning', 'quarta', '07:00:00', 10 FROM academia WHERE nome = 'Malibu Presidente Prudente';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Treino híbrido', 'sexta', '18:30:00', 14 FROM academia WHERE nome = 'Malibu Presidente Prudente';

INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Musculação com peso livre', 'terca', '10:00:00', 20 FROM academia WHERE nome = 'BlueFit 24h';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Cardio', 'quinta', '06:00:00', 20 FROM academia WHERE nome = 'BlueFit 24h';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Musculação com peso livre', 'sabado', '14:00:00', 20 FROM academia WHERE nome = 'BlueFit 24h';

INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Funcional HIIT', 'segunda', '06:30:00', 12 FROM academia WHERE nome = 'Academia Cross Point';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Funcional HIIT', 'quarta', '06:30:00', 12 FROM academia WHERE nome = 'Academia Cross Point';
INSERT INTO aula (academia_id, modalidade, diaSemana, horario, vagas)
SELECT id, 'Funcional HIIT', 'sexta', '18:00:00', 12 FROM academia WHERE nome = 'Academia Cross Point';

INSERT INTO aluno (nome, cpf, dataNascimento, idade, endereco, telefone, email, senha, objetivo, nivel) VALUES
('Aluno Teste', '000.000.000-00', '2000-01-01', 26, 'Rua Exemplo, 100', '18999999999', 'aluno@teste.com', '1234', 'hipertrofia', 'iniciante'),
('Aluno Basico', '111.111.111-11', '2000-02-02', 26, 'Av. Teste, 200', '18988888888', 'basico@teste.com', '1234', 'emagrecimento', 'iniciante');

INSERT INTO assinatura (aluno_id, plano_id, dataInicio, dataFim)
SELECT a.id, p.id, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY)
FROM aluno a JOIN plano p
WHERE a.email = 'aluno@teste.com' AND p.nome = 'Plano Premium';

INSERT INTO assinatura (aluno_id, plano_id, dataInicio, dataFim)
SELECT a.id, p.id, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY)
FROM aluno a JOIN plano p
WHERE a.email = 'basico@teste.com' AND p.nome = 'Plano Básico';