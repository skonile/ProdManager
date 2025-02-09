-- Active: 1715269500265@@127.0.0.1@3306@prodmanager

DROP TABLE IF EXISTS `brand`;

CREATE TABLE `brand`(
    `brand_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `brand_name` VARCHAR(255) NOT NULL
);

-- Seed
INSERT INTO brand (brand_id, brand_name) VALUES (1, 'Artav');
INSERT INTO brand (brand_id, brand_name) VALUES (2, 'Rhinobar');
INSERT INTO brand (brand_id, brand_name) VALUES (3, 'AndyCab');
INSERT INTO brand (brand_id, brand_name) VALUES (4, 'AudoMax');
INSERT INTO brand (brand_id, brand_name) VALUES (5, 'Auto Accessories');
INSERT INTO brand (brand_id, brand_name) VALUES (6, 'MD Enterprises');
INSERT INTO brand (brand_id, brand_name) VALUES (7, 'Wilsons Buchery');
INSERT INTO brand (brand_id, brand_name) VALUES (8, 'Takealot');
INSERT INTO brand (brand_id, brand_name) VALUES (9, 'Amazon');
INSERT INTO brand (brand_id, brand_name) VALUES (10, 'AliExpress');
INSERT INTO brand (brand_id, brand_name) VALUES (11, 'PEP');
INSERT INTO brand (brand_id, brand_name) VALUES (12, 'Mr Price');
INSERT INTO brand (brand_id, brand_name) VALUES (13, 'Boxer');
INSERT INTO brand (brand_id, brand_name) VALUES (14, 'Shoprite');
INSERT INTO brand (brand_id, brand_name) VALUES (15, 'Jet');
INSERT INTO brand (brand_id, brand_name) VALUES (16, 'Exact');
INSERT INTO brand (brand_id, brand_name) VALUES (17, 'Studio 88');
INSERT INTO brand (brand_id, brand_name) VALUES (18, 'AutoBoys');
INSERT INTO brand (brand_id, brand_name) VALUES (19, 'HP');
INSERT INTO brand (brand_id, brand_name) VALUES (20, 'Lenovo');
INSERT INTO brand (brand_id, brand_name) VALUES (21, 'OpenAI');
INSERT INTO brand (brand_id, brand_name) VALUES (22, 'Microsoft');
INSERT INTO brand (brand_id, brand_name) VALUES (23, 'Google');
INSERT INTO brand (brand_id, brand_name) VALUES (24, 'Meta');
INSERT INTO brand (brand_id, brand_name) VALUES (25, 'Twitter');
INSERT INTO brand (brand_id, brand_name) VALUES (26, 'Game');
INSERT INTO brand (brand_id, brand_name) VALUES (27, 'Asus');
INSERT INTO brand (brand_id, brand_name) VALUES (28, 'IBM');
INSERT INTO brand (brand_id, brand_name) VALUES (29, 'Linode');
INSERT INTO brand (brand_id, brand_name) VALUES (30, 'HostAfrca');