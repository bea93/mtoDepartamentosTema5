CREATE DATABASE IF NOT EXISTS DAW213DBProyectoTema5;

USE DAW213DBProyectoTema5;

CREATE USER 'usuarioDAW213DBProyectoTema5'@'%' IDENTIFIED BY 'P@ssw0rd';

GRANT ALL PRIVILEGES ON `DAW213DBProyectoTema5`.* TO 'usuarioDAW213DBProyectoTema5'@'%';

CREATE TABLE T02_Departamento (
        T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
	T02_DescDepartamento VARCHAR(255) NOT NULL,
	T02_FechaBajaDepartamento INT DEFAULT NULL, -- Valor por defecto null, ya que cuando lo creas no puede estar de baja logica
	T02_FechaCreacionDepartamento INT NOT NULL,
	T02_VolumenNegocio FLOAT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=LATIN1;

CREATE TABLE T01_Usuario (
	T01_CodUsuario VARCHAR(15) PRIMARY KEY,
	T01_DescUsuario VARCHAR(250) NOT NULL,
	T01_Password VARCHAR(64) NOT NULL,
	T01_Perfil ENUM('administrador', 'usuario') DEFAULT 'usuario',
	T01_FechaHoraUltimaConexion INT,
	T01_NumConexiones INT DEFAULT 0,
	T01_ImagenUsuario MEDIUMBLOB 
    /*Los tipos BLOB se utilizan para almacenar datos binarios como pueden ser ficheros.
    MediumBlob es un texto con un máximo de 16.777.215 caracteres.
    */
)ENGINE=INNODB DEFAULT CHARSET=LATIN1;