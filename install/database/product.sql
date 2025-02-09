DROP TABLE IF EXISTS product;

CREATE TABLE product(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(100),
    description TEXT,
    price DECIMAL NOT NULL,
    cond ENUM('new', 'good', 'fair', 'notworking') NOT NULL DEFAULT 'new',
    brand_id INT,
    quantity INT NOT NULL DEFAULT 0,
    is_published BOOLEAN
);

-- Seed
INSERT INTO product (id, name, code, description, price, cond, brand_id, quantity, is_published) 
VALUES (1, 'Test Product', 'TestCode', 'Test product description', 10.00, 'new', 3, 100, TRUE);
INSERT INTO product (name, code, description, price, cond, brand_id, quantity, is_published) 
VALUES ('Test 2', 'TCode', 'Test product description', 10.00, 'new', 3, 100, TRUE);
INSERT INTO product (name, code, description, price, cond, brand_id, quantity, is_published) 
VALUES ('Test 3', 'TC', 'Test product description', 10.00, 'new', 3, 100, TRUE);