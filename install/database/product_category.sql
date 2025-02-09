DROP TABLE IF EXISTS product_category;

CREATE TABLE product_category(
    prod_id INT NOT NULL,
    cat_id INT NOT NULL
);

-- Seed
INSERT INTO product_category (prod_id, cat_id) VALUES (1, 1);
INSERT INTO product_category (prod_id, cat_id) VALUES (1, 14);