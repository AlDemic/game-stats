CREATE TABLE games(
	id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    title varchar(128) NOT NULL,
    realesed_year int NOT NULL,
    pic varchar(48),
    url varchar(128) NOT NULL UNIQUE
);

CREATE TABLE g_online(
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_game int NOT NULL,
    FOREIGN KEY (id_game) REFERENCES games(id) ON UPDATE CASCADE,
    date DATE,
    online float NOT NULL,
    source varchar(128) NOT NULL,
    CONSTRAINT unique_online_stat UNIQUE (id_game, date, source)
);

CREATE TABLE g_income(
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_game int NOT NULL,
    FOREIGN KEY (id_game) REFERENCES games(id) ON UPDATE CASCADE,
    date DATE,
    income float NOT NULL,
    source varchar(128) NOT NULL,
    CONSTRAINT unique_online_stat UNIQUE (id_game, date, source)
);
