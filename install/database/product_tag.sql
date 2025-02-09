DROP TABLE IF EXISTS product_tag;

CREATE TABLE product_tag(
    prod_id INT NOT NULL,
    tag_id INT NOT NULL
);

-- Seed
INSERT INTO product_tag (prod_id, tag_id) VALUES (1, 5);
INSERT INTO product_tag (prod_id, tag_id) VALUES (1, 2);
INSERT INTO product_tag (prod_id, tag_id) VALUES (1, 10);