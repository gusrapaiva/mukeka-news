create database beluganews;
use beluganews;

create table usuario (	id int auto_increment primary key,
						nome varchar(35) not null,
						tipo char(1) not null,
                        email varchar(35) unique not null,
                        senha char(8) not null,
                        ativo boolean
);
 
 create table imagem ( 	id int auto_increment primary key,
						nome varchar(40) unique,
						tipo varchar(10),
						tamanho numeric(15)
);

create table noticia (	num_noticia int auto_increment primary key,
						categoria char(1) not null,
                        titulo varchar(50) unique not null,
                        corpo varchar (1000) not null,
                        visivel boolean,
                        imagem_capa int,
                        imagem_corpo int,
                        id_autor int,
                        id_editor int,
                        foreign key (imagem_capa) references imagem (id),
                        foreign key (imagem_corpo) references imagem (id),
                        foreign key (id_autor) references usuario (id),
                        foreign key (id_editor) references usuario (id)
);

insert into usuario (nome, tipo, email, senha, ativo) values ('Admin', 0, 'admin@gmail.com', 1234, 1);
select * from usuario;
select * from noticia;
select * from imagem;

select id from usuario;