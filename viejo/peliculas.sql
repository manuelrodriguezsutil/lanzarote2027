-- \. ./var/www/html/lanzarote.lan/tablas/peliculas.sql

DROP TABLE peliculas;

CREATE TABLE IF NOT EXISTS peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    director VARCHAR(255) NOT NULL,
    year DATE NOT NULL,
    genero VARCHAR(255) NOT NULL,
    duracion FLOAT NOT NULL
);

INSERT INTO peliculas (titulo, director, year, genero, duracion) VALUES
('El padrino', 'Francis Ford Coppola', '1972-03-24', 'Drama', 175),
('Pulp Fiction', 'Quentin Tarantino', '1994-10-14', 'Crimen', 154),
('El caballero oscuro', 'Christopher Nolan', '2008-07-18', 'Acción', 152),
('Forrest Gump', 'Robert Zemeckis', '1994-07-06', 'Drama', 142),
('Origen', 'Christopher Nolan', '2010-07-16', 'Ciencia ficción', 148),
('Matrix', 'Lana y Lilly Wachowski', '1999-03-31', 'Ciencia ficción', 136),
('Titanic', 'James Cameron', '1997-12-19', 'Romance', 195),
('Gladiator', 'Ridley Scott', '2000-05-05', 'Acción', 155),
('Interstellar', 'Christopher Nolan', '2014-11-07', 'Ciencia ficción', 169),
('Avatar', 'James Cameron', '2009-12-18', 'Ciencia ficción', 162),
('Parásitos', 'Bong Joon-ho', '2019-05-30', 'Suspense', 132),
('Joker', 'Todd Phillips', '2019-10-04', 'Drama', 122),
('La lista de Schindler', 'Steven Spielberg', '1993-12-15', 'Drama', 195),
('El señor de los anillos: La comunidad del anillo', 'Peter Jackson', '2001-12-19', 'Fantasía', 178),
('El señor de los anillos: Las dos torres', 'Peter Jackson', '2002-12-18', 'Fantasía', 179),
('El señor de los anillos: El retorno del rey', 'Peter Jackson', '2003-12-17', 'Fantasía', 201),
('Django desencadenado', 'Quentin Tarantino', '2012-12-25', 'Western', 165),
('Malditos bastardos', 'Quentin Tarantino', '2009-08-21', 'Bélico', 153),
('El club de la lucha', 'David Fincher', '1999-10-15', 'Drama', 139),
('Seven', 'David Fincher', '1995-09-22', 'Suspense', 127);