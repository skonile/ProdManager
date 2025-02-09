DROP TABLE IF EXISTS product_image;

CREATE TABLE product_image(
    prod_id INT NOT NULL,
    prod_image VARCHAR(500) NOT NULL
);

-- Seed
INSERT INTO product_image (prod_id, prod_image) VALUES (1, 'product-1-1869955212912-538409.jpg');