--CREATE DATABASE [usuarios-db];

BEGIN TRANSACTION
USE [usuarios-db];

CREATE TABLE usuarios (
  id int NOT NULL IDENTITY(1,1) PRIMARY KEY,
  nombres nvarchar(100)   DEFAULT NULL,
  apellidos nvarchar(100) DEFAULT NULL,
  dni char(8) UNIQUE,
  correo nvarchar(100)   DEFAULT NULL,
  telefono char(9)   DEFAULT NULL,
  clave nvarchar(100) DEFAULT NULL,
  privilegios bit NOT NULL DEFAULT 0
) ;

CREATE TABLE sincronizaciones (
	tabla varchar(100) PRIMARY KEY,
    tiempo datetime NOT NULL,
);

INSERT INTO usuarios VALUES('Fabrizzio Fabiano','Esquivel Mori','12345678','correo@hotmail.com','987654321','123',1)
INSERT INTO usuarios VALUES('Frank Heli','Carrion Molina','87654321','correo@outlook.com','123456789','abc',0)
INSERT INTO usuarios VALUES('Ricardo Jeanzel','Vásquez Yalle','00000000','correo@gmail.com','000000000','ABC',0)

INSERT INTO sincronizaciones VALUES('usuarios', '1900-01-01 00:00:00.000')

-- Create a SQL Server login
CREATE LOGIN fabrizzio WITH PASSWORD = '*8e#0@32V*';
EXEC sp_addsrvrolemember 'fabrizzio', 'sysadmin';
EXEC sp_addsrvrolemember 'fabrizzio', 'serveradmin';
EXEC sp_addsrvrolemember 'fabrizzio', 'securityadmin';
EXEC sp_addsrvrolemember 'fabrizzio', 'processadmin';
EXEC sp_addsrvrolemember 'fabrizzio', 'setupadmin';
EXEC sp_addsrvrolemember 'fabrizzio', 'bulkadmin';
USE [usuarios-db];
ALTER LOGIN fabrizzio ENABLE;
CREATE USER fabrizzio FOR LOGIN fabrizzio;
ALTER ROLE db_owner ADD MEMBER fabrizzio;

-- Create a Login
CREATE LOGIN invitado WITH PASSWORD = '@v67F4#75';
USE [usuarios-db];
CREATE USER invitado FOR LOGIN invitado;
GRANT SELECT (dni,clave,privilegios) ON usuarios TO invitado;

-- Create a SQL Server login
CREATE LOGIN usuario WITH PASSWORD = '28@c3*N54';
USE [usuarios-db];
CREATE USER usuario FOR LOGIN usuario;
GRANT SELECT ON usuarios TO usuario;

COMMIT;