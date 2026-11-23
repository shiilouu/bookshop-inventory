CREATE DATABASE IF NOT EXISTS bookshop_inventory;

USE bookshop_inventory;

DROP TABLE IF EXISTS stock_transactions;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE categories (
    category_id   INT          AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    user_id       INT                 AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(150)        NOT NULL,
    username      VARCHAR(50)         NOT NULL UNIQUE,
    password_hash VARCHAR(255)        NOT NULL,
    role          ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    created_at    TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE books (
    book_id        INT           AUTO_INCREMENT PRIMARY KEY,
    category_id    INT           NOT NULL,
    isbn           VARCHAR(20)   NULL UNIQUE,
    title          VARCHAR(200)  NOT NULL,
    author         VARCHAR(150)  NULL,
    price          DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    stock_quantity INT           NOT NULL DEFAULT 0,
    minimum_stock  INT           NOT NULL DEFAULT 5,
    is_low_stock   TINYINT(1)    NOT NULL DEFAULT 0,
    created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_books_category
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE stock_transactions (
    transaction_id   INT                        AUTO_INCREMENT PRIMARY KEY,
    book_id          INT                        NOT NULL,
    user_id          INT                        NOT NULL,
    transaction_type ENUM('stock_in','stock_out') NOT NULL,
    quantity         INT                        NOT NULL,
    unit_price       DECIMAL(10,2)              NOT NULL,
    remarks          VARCHAR(255)               NULL,
    created_at       TIMESTAMP                  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tx_book
        FOREIGN KEY (book_id) REFERENCES books(book_id),
    CONSTRAINT fk_tx_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
);

DESC categories;
DESC books;
DESC users;
DESC stock_transactions;