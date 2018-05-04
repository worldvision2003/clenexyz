DROP DATABASE IF EXISTS `clene`;
CREATE DATABASE `clene`;

USE `clene`;

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  ID        INTEGER PRIMARY KEY AUTO_INCREMENT,
  login     VARCHAR(60) NOT NULL,
  nome      VARCHAR(60) NOT NULL,
  email     VARCHAR(60) NOT NULL,
  senha     VARCHAR(32) NOT NULL,
  token     VARCHAR(32) NOT NULL,
  criado    DATETIME    NOT NULL,
  logado    DATETIME    NOT NULL,
  propic    LONGBLOB    DEFAULT NULL,
  priv      INTEGER     NOT NULL
);

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  ID        INTEGER PRIMARY KEY AUTO_INCREMENT,
  nome      VARCHAR(20) NOT NULL,
  target    VARCHAR(60) NOT NULL,
  priv      INTEGER     NOT NULL,
  ativo     BOOLEAN     NOT NULL
);

DROP TABLE IF EXISTS `clene`;
CREATE TABLE `clene` (
  ID        INTEGER PRIMARY KEY AUTO_INCREMENT,
  userid    INTEGER     NOT NULL,
  data      DATETIME    NOT NULL,
  nome      VARCHAR(20) DEFAULT NULL,
  descr     VARCHAR(60) DEFAULT NULL,
  imagem    LONGBLOB    NOT NULL,
  tipo      VARCHAR(20) NOT NULL,
  ativo     BOOLEAN     NOT NULL
);

INSERT INTO `usuario` (login,nome,email,senha,token,criado,logado,propic,priv)
VALUES ('teste','Teste da Silva','teste@teste.com','4daaed10d00f88929b0516a1028b8cb3','',NOW(),NOW(),'',1);

INSERT INTO `menu` (nome,target,priv,ativo) VALUES
('Home', '/', 0, 1), ('Perfil', '/?op=perfil', 1, 1), ('Ranking', '/?op=ranking', 1, 1), ('Login', '/?op=login', -1, 1), ('Registrar', '/?op=registrar', -1, 1),
('Logout', '/?op=logout', 1, 1);
