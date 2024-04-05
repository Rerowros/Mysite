CREATE TABLE `users`
(
    `id`       int(11) NOT NULL,
    `username` varchar(50)  DEFAULT NULL,
    `email`    varchar(100) DEFAULT NULL,
    `password` varchar(255) DEFAULT NULL,
    `role`     varchar(50)

);

CREATE TABLE `cart`
(
    `id`                 int(11)      NOT NULL, -- уникальный идентификатор для каждого элемента в корзине
    `product_name`       varchar(100) NOT NULL, -- название товара в корзине
    `product_price`      varchar(50)  NOT NULL, -- цена товара в корзине
    `product_image_link` varchar(255) NOT NULL, -- URL изображения товара в корзине
    `quantity`           int(10)      NOT NULL, -- количество товара в корзине
    `total_price`        varchar(100) NOT NULL, -- общая стоимость товара в корзине
    `product_code`       varchar(10)  NOT NULL  -- код товара в корзине
);



CREATE TABLE `orders`
(
    `id`          int(11) NOT NULL,          -- уникальный идентификатор заказа
    `name`        varchar(100) DEFAULT NULL, -- имя клиента, разместившего заказ
    `email`       varchar(100) DEFAULT NULL, -- электронная почта клиента
    `phone`       varchar(20)  DEFAULT NULL, -- номер телефона клиента
    `address`     varchar(255) DEFAULT NULL, -- адрес доставки заказа
    `paym`        varchar(50)  DEFAULT NULL, -- способ оплаты заказа
    `products`    varchar(255) DEFAULT NULL, -- список товаров в заказе
    `amount_paid` varchar(100) DEFAULT NULL  -- сумма, оплаченная за заказ
);


CREATE TABLE `product`
(
    `id`                 int(11)      NOT NULL, -- уникальный идентификатор продукта
    `product_name`       varchar(255) NOT NULL, -- название продукта
    `product_price`      varchar(100) NOT NULL, -- цена
    `product_qty`        int(11)      NOT NULL DEFAULT 1,
    `product_image_link` varchar(255) NOT NULL, -- изображение продукта
    `product_code`       varchar(50)  NOT NULL  -- код продукта
);

-- Добавление в качестве примера --
INSERT INTO `users` (id, username, email, password, role)
VALUES (1, 'bzr', '123@gmail.com', '123', 'admin'),
       (2, 'rr', 'rr@gmail.com', '123', 'user');


INSERT INTO `product` (`id`, `product_name`, `product_price`, `product_image_link`, `product_code`)
VALUES (1, 'Apple iPhone X', '90000', 'image/iphone_x.jpg', 'p1000'),
       (9, 'Nike air jordan max', '20000', 'image/1.jpg', 'p2000');



-- ADD PRIMARY KEY, ограничение первичного ключа  Это ограничение гарантирует, что каждое значение в столбце "id" уникально и не является нулевым. --
-- ADD UNIQUE KEY, Это ограничение гарантирует, что каждое значение в столбце "код_продукта" будет уникальным --
-- ADD KEY, Этот ключ не является уникальным, но он позволяет ускорить поиск и индексирование столбца  --

ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
    ALTER COLUMN role SET DEFAULT 'user';

ALTER TABLE `cart`
    ADD PRIMARY KEY (`id`);


ALTER TABLE `orders`
    ADD PRIMARY KEY (`id`);


ALTER TABLE `product`
    ADD PRIMARY KEY (`id`),
    ADD KEY `product_code` (`product_code`);

ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;

ALTER TABLE `cart`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;

ALTER TABLE `orders`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;

ALTER TABLE `product`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;
COMMIT;
