USE bookshop_inventory;

DROP TRIGGER IF EXISTS trg_after_stock_transaction_insert;

DELIMITER $$

CREATE TRIGGER trg_after_stock_transaction_insert
AFTER INSERT ON stock_transactions
FOR EACH ROW
BEGIN
    IF NEW.transaction_type = 'stock_in' THEN
        UPDATE books
        SET stock_quantity = stock_quantity + NEW.quantity,
            is_low_stock = IF((stock_quantity + NEW.quantity) <= minimum_stock, 1, 0)
        WHERE book_id = NEW.book_id;
    ELSEIF NEW.transaction_type = 'stock_out' THEN
        UPDATE books
        SET stock_quantity = stock_quantity - NEW.quantity,
            is_low_stock = IF((stock_quantity - NEW.quantity) <= minimum_stock, 1, 0)
        WHERE book_id = NEW.book_id;
    END IF;
END$$

DELIMITER ;