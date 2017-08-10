CREATE database controle_atividades;

USE controle_atividades; 

CREATE TABLE atividades
	(
	    idatividade int not null PRIMARY KEY AUTO_INCREMENT,
	    nome varchar(255) not null,
	    descricao varchar(600) not null,
	    datainicio DATE not null,
	    datafinal DATE,
	    idstatus int,
	    situacao char(1)
	); 

CREATE TABLE status
	(
	    idstatus int not null PRIMARY KEY AUTO_INCREMENT,
	    descricao varchar(45)
	); 


INSERT INTO `status`(`idstatus`, `descricao`) VALUES (NULL,'Pendente'), (NULL, 'Em desenvolvimento'), (NULL, 'Em teste'), (NULL, 'Concluído')