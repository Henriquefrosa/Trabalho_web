CREATE DATABASE marketplace;
USE marketplace;

CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE Produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    foto VARCHAR(255) NOT NULL, -- Caminho para a imagem no servidor
    id_vendedor INT NOT NULL,
    FOREIGN KEY (id_vendedor) REFERENCES Usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Avaliacao (
    id_avaliacao INT AUTO_INCREMENT PRIMARY KEY,
    nota INT NOT NULL,
    avaliacao TEXT,
    id_avaliador INT NOT NULL,
    id_avaliado INT NOT NULL,
    FOREIGN KEY (id_avaliador) REFERENCES Usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_avaliado) REFERENCES Usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);

