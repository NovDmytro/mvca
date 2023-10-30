CREATE SCHEMA mvca;
CREATE TABLE mvca.mvca_example
(
    id          serial PRIMARY KEY,
    name        character varying(256) NOT NULL,
    description text,
    price       numeric(10,2) NOT NULL,
    sale        integer NOT NULL
);

INSERT INTO mvca.mvca_example (name, description, price, sale) VALUES ('elphant', 'He likes banana', 100.00, 20);