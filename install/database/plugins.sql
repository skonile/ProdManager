DROP TABLE IF EXISTS plugins;

CREATE TABLE plugins(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    plugin_name VARCHAR(255) NOT NULL,
    plugin_sys_name VARCHAR(255) NOT NULL
);

-- Seed
INSERT INTO plugins (id, plugin_name, plugin_sys_name) VALUES (1, 'Example Plugin', 'ExamplePlugin');
INSERT INTO plugins (id, plugin_name, plugin_sys_name) VALUES (2, 'Woocommerce', 'WocommercePlugin');