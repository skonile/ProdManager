DROP TABLE IF EXISTS shipping;

CREATE TABLE shipping(
    prod_id INT NOT NULL,
    local FLOAT,
    nationwide FLOAT,
    international FLOAT
);


-- Seed
INSERT INTO shipping (prod_id, local, nationwide, international) VALUES (1, 0, 0, 0);
INSERT INTO shipping (prod_id, local, nationwide, international) VALUES (2, 0, 0, 0);
INSERT INTO shipping (prod_id, local, nationwide, international) VALUES (3, NULL, NULL, NULL);