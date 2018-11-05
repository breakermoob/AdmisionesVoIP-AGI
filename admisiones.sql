CREATE TABLE credencial (
	id INT NOT NULL AUTO_INCREMENT,
	sede VARCHAR(30),
	bloque VARCHAR(30),
	aula VARCHAR(30),
	hora VARCHAR(30),
	fecha VARCHAR(30),
	estado VARCHAR(30),
	KEY (id)
);

CREATE TABLE usuario (
	cedula INT NOT NULL,
	nombre varchar(50),
	KEY (cedula)
);
INSERT INTO usuario VALUES(1,'leon arango');
INSERT INTO usuario VALUES(2,'leonidas arango');
INSERT INTO usuario VALUES(3,'lion arango');
INSERT INTO usuario VALUES(4,'leo arango');

CREATE TABLE credencial_usuario (
	id INT NOT NULL,
	cedula NOT NULL,
	KEY (id)
);

INSERT INTO credencial VALUES(1, 'Medellin Ciudadela', '20','211', '8:30', '20 de octubre','sin presentar');
INSERT INTO credencial VALUES(2, 'Medellin Robledo', '5','101', '8:30', '10 de marzo','rechazado');
INSERT INTO credencial VALUES(3, 'Yarumal', '1','11', '8:30', '10 de marzo','aprovado');
INSERT INTO credencial VALUES(4, 'Carmen', '3','1', '8:30', '10 de marzo','aprovado');


INSERT INTO credencial_usuario VALUES(1,1);
INSERT INTO credencial_usuario VALUES(2,2);
INSERT INTO credencial_usuario VALUES(3,3);
INSERT INTO credencial_usuario VALUES(4,4);
