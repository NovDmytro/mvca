CREATE TABLE mvcadb.mvca_example
(
    id          int auto_increment primary key,
    name        varchar(256)   not null,
    description text           null,
    price       decimal(10, 2) not null,
    sale        int(3)         not null
);

INSERT INTO mvcadb.mvca_example (name, description, price, sale) VALUES ('dolphin', 'He likes fish', 40.00, 5);