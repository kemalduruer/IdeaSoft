SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
START TRANSACTION;
SET time_zone = "+03:00";

CREATE DATABASE IF NOT EXISTS laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laravel;

CREATE TABLE IF NOT EXISTS customers (
                                         `id` int NOT NULL AUTO_INCREMENT,
                                         `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
    `since` datetime DEFAULT CURRENT_TIMESTAMP,
    `revenue` decimal(9,2) DEFAULT '0.00',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS products (
                                        `id` int NOT NULL AUTO_INCREMENT,
                                        `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
    `category` int NOT NULL,
    `price` decimal(9,2) DEFAULT '0.00',
    `stock` int DEFAULT '0',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS orders (
                                      `id` int NOT NULL AUTO_INCREMENT,
                                      `customerId` int NOT NULL,
                                      `total` decimal(9,2) DEFAULT '0.00',
    PRIMARY KEY (`id`),
    KEY `orders_customerId` (`customerId`),
    CONSTRAINT `orders_customerId` FOREIGN KEY (`customerId`) REFERENCES `customers` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

CREATE TABLE IF NOT EXISTS order_items (
                                           `id` int NOT NULL AUTO_INCREMENT,
                                           `orderId` int NOT NULL,
                                           `productId` int NOT NULL,
                                           `quantity` int NOT NULL,
                                           `unitPrice` decimal(9,2) DEFAULT '0.00',
    `total` decimal(9,2) DEFAULT '0.00',
    PRIMARY KEY (`id`),
    KEY `order_items_orderId` (`orderId`),
    KEY `order_items_productId` (`productId`),
    CONSTRAINT `order_items_orderId` FOREIGN KEY (`orderId`) REFERENCES `orders` (`id`),
    CONSTRAINT `order_items_productId` FOREIGN KEY (`productId`) REFERENCES `products` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

INSERT INTO customers (id, name, since, revenue) VALUES (1, 'Türker Jöntürk', '2014-06-28 00:00:00', 492.12);
INSERT INTO customers (id, name, since, revenue) VALUES (2, 'Kaptan Devopuz', '2015-01-15 00:00:00', 1505.95);
INSERT INTO customers (id, name, since, revenue) VALUES (3, 'İsa Sonuyumaz', '2016-02-11 00:00:00', 0.00);

INSERT INTO products (id, name, category, price, stock) VALUES
                                                            (100, 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti', 1, 120.75, 100),
                                                            (101, 'Reko Mini Tamir Hassas Tornavida Seti 32''li', 1, 49.50, 100),
                                                            (102, 'Viko Karre Anahtar - Beyaz', 2, 11.28, 100),
                                                            (103, 'Legrand Salbei Anahtar, Alüminyum', 2, 22.80, 100),
                                                            (104, 'Schneider Asfora Beyaz Komütatör', 2, 12.95, 100),
                                                            (105, 'Lorem Ipsum Sit Amet.', 3, 20.95, 100),
                                                            (106, 'Kahverengi Masa', 1, 100.95, 100);

INSERT INTO orders (id, customerId, total) VALUES (1, 1, 112.80), (2, 2, 219.75), (3, 3, 1275.18);

INSERT INTO order_items (id, orderId, productId, quantity, unitPrice, total) VALUES
                                                                                 (1, 1, 102, 10, 11.28, 112.80),
                                                                                 (2, 2, 101, 2, 49.50, 99.00),
                                                                                 (3, 2, 100, 1, 120.75, 120.75),
                                                                                 (4, 3, 102, 6, 11.28, 67.68),
                                                                                 (5, 3, 100, 10, 120.75, 1207.50);

COMMIT;
