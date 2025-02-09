DROP TABLE IF EXISTS category;

CREATE TABLE category(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    parent_id INT,
    is_published BOOLEAN DEFAULT 0
);


-- Seed
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (1, 'All', 'all', NULL, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (2, 'Audio', 'audio', 1, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (3, 'Headunits', 'headunits', 2, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (4, 'Speakers', 'speakers', 2, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (5, 'Subwoofers', 'subwoofers', 2, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (6, 'Active Subwoofers', 'active-subwoofers', 2, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (7, 'Towbars', 'towbars', 1, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (8, 'Under Bumpers', 'under-bumpers', 7, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (9, 'Double Tube Steps', 'double-tube-steps', 7, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (10, 'Laptops', 'laptops', 1, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (11, 'Netbooks', 'netbooks', 10, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (12, '14.1inch', '14.1inch', 10, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (13, '15.6inch', '15.6inch', 10, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (14, 'Servers', 'server', 1, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (15, 'Shared Servers', 'shared-servers', 14, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (16, 'VPS', 'vps', 14, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (17, 'Dedicated Servers', 'dedicated-servers', 14, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (18, 'Clothing', 'clothing', 1, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (19, 'Men', 'men', 18, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (20, 'Women', 'women', 18, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (21, 'Boys', 'Girls', 18, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (22, 'Accessories', 'accessories', 18, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (23, 'Food', 'Food', 1, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (24, 'Fruit', 'fruit', 23, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (25, 'Vegetables', 'vegetables', 23, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (26, 'Meat', 'meat', 23, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (27, 'Drinks', 'drinks', 23, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (28, 'AI', 'ai', 1, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (29, 'ChatGPT 3.5', 'chatgpt3.5', 28, 1);
INSERT INTO category (id, name, slug, parent_id, is_published) VALUES (30, 'ChatGPT 4.0', 'chatgpt4.0', 28, 1);