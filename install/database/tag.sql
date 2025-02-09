DROP TABLE IF EXISTS tag;

CREATE TABLE tag(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    is_published BOOLEAN NOT NULL DEFAULT 0
);

-- Seed
INSERT INTO tag (id, name, slug, is_published) VALUES (1, 'All', 'all', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (2, 'Clothing', 'clothing', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (3, 'Cars', 'cars', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (4, 'Audio', 'Audio', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (5, 'Computers', 'computers', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (6, 'Computing', 'Computing', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (7, 'Life Style', 'life-style', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (8, 'Towing', 'towing', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (9, 'Sound System', 'sound-system', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (10, 'Bluetooth', 'bluetooth', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (11, 'Cloud Computing', 'cloud-computing', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (12, 'Food', 'food', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (13, 'Healthy Food', 'Healthy Food', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (14, 'Accessories', 'Accessories', 1);
INSERT INTO tag (id, name, slug, is_published) VALUES (15, 'Car Accessories', 'Car Accessories', 1);