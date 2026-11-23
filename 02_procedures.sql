USE bookshop_inventory;

DROP PROCEDURE IF EXISTS sp_stock_in;

DELIMITER $$

CREATE PROCEDURE sp_stock_in(
    IN p_book_id INT,
    IN p_user_id INT,
    IN p_quantity INT,
    IN p_note VARCHAR(255)
)
BEGIN
    DECLARE v_book_count INT DEFAULT 0;
    DECLARE v_user_count INT DEFAULT 0;
    DECLARE v_unit_price DECIMAL(10,2) DEFAULT 0.00;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    IF p_quantity <= 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Invalid quantity',
                MYSQL_ERRNO = 1001;
    END IF;

    SELECT COUNT(*) INTO v_book_count
    FROM books
    WHERE book_id = p_book_id;

    IF v_book_count = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Book not found',
                MYSQL_ERRNO = 1002;
    END IF;

    SELECT COUNT(*) INTO v_user_count
    FROM users
    WHERE user_id = p_user_id;

    IF v_user_count = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'User not found',
                MYSQL_ERRNO = 1003;
    END IF;

    SELECT price INTO v_unit_price
    FROM books
    WHERE book_id = p_book_id;

    START TRANSACTION;

        INSERT INTO stock_transactions (book_id, user_id, transaction_type, quantity, unit_price, remarks)
        VALUES (p_book_id, p_user_id, 'stock_in', p_quantity, v_unit_price, p_note);

    COMMIT;
END$$

DELIMITER ;


USE bookshop_inventory;

DROP PROCEDURE IF EXISTS sp_stock_out;

DELIMITER $$

CREATE PROCEDURE sp_stock_out(
    IN p_book_id INT,
    IN p_user_id INT,
    IN p_quantity INT,
    IN p_note VARCHAR(255)
)
BEGIN
    DECLARE v_book_count INT DEFAULT 0;
    DECLARE v_user_count INT DEFAULT 0;
    DECLARE v_current_stock INT DEFAULT 0;
    DECLARE v_unit_price DECIMAL(10,2) DEFAULT 0.00;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    -- check 1: dapat 1 pataas ang quantity
    IF p_quantity <= 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Invalid quantity',
                MYSQL_ERRNO = 1001;
    END IF;

    -- check 2: checker kung existing ang book_id
    SELECT COUNT(*) INTO v_book_count
    FROM books
    WHERE book_id = p_book_id;

    IF v_book_count = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Book not found',
                MYSQL_ERRNO = 1002;
    END IF;

    -- check 3: checker kung existing ang user_id
    SELECT COUNT(*) INTO v_user_count
    FROM users
    WHERE user_id = p_user_id;

    IF v_user_count = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'User not found',
                MYSQL_ERRNO = 1003;
    END IF;

    START TRANSACTION;

        SELECT stock_quantity, price
        INTO v_current_stock, v_unit_price
        FROM books
        WHERE book_id = p_book_id
        FOR UPDATE;

        -- check 4: dapat sapat ang stock bago mag-stock out
        IF v_current_stock < p_quantity THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Insufficient stock',
                    MYSQL_ERRNO = 1004;
        END IF;

        INSERT INTO stock_transactions (book_id, user_id, transaction_type, quantity, unit_price, remarks)
        VALUES (p_book_id, p_user_id, 'stock_out', p_quantity, v_unit_price, p_note);

    COMMIT;
END$$

DELIMITER ;