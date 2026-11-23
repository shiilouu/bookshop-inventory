USE bookshop_inventory;
DESCRIBE stock_transactions;

-- dashboard totals view
CREATE OR REPLACE VIEW v_dashboard_totals AS
SELECT
    (SELECT COUNT(*) FROM books) AS total_books,
    (SELECT COALESCE(SUM(stock_quantity), 0) FROM books) AS total_stock_quantity,
    (SELECT COUNT(*) FROM books WHERE is_low_stock = 1) AS low_stock_books,
    (SELECT COALESCE(SUM(quantity), 0)
     FROM stock_transactions
     WHERE transaction_type = 'stock_out') AS total_items_sold;

-- low stock view
CREATE OR REPLACE VIEW v_low_stock AS
SELECT
    book_id,
    isbn,
    title,
    author,
    stock_quantity,
    minimum_stock,
    is_low_stock
FROM books
WHERE is_low_stock = 1
   OR stock_quantity <= minimum_stock
ORDER BY stock_quantity ASC, title ASC;

-- best seller view
CREATE OR REPLACE VIEW v_best_sellers AS
SELECT
    b.book_id,
    b.isbn,
    b.title,
    b.author,
    COALESCE(SUM(st.quantity), 0) AS total_sold,
    COALESCE(SUM(st.quantity * COALESCE(st.unit_price, b.price)), 0.00) AS total_sales_amount
FROM books b
JOIN stock_transactions st
    ON b.book_id = st.book_id
WHERE st.transaction_type = 'stock_out'
GROUP BY b.book_id, b.isbn, b.title, b.author
ORDER BY total_sold DESC, total_sales_amount DESC;

-- sales report view
CREATE OR REPLACE DEFINER = 'root'@'localhost' VIEW v_sales_report AS
SELECT
    st.transaction_id,
    st.created_at AS transaction_date,
    b.book_id,
    b.isbn,
    b.title,
    u.user_id,
    u.username AS processed_by,
    st.quantity,
    COALESCE(st.unit_price, b.price) AS unit_price,
    (st.quantity * COALESCE(st.unit_price, b.price)) AS line_total,
    st.remarks AS note
FROM stock_transactions st
JOIN books b
    ON st.book_id = b.book_id
JOIN users u
    ON st.user_id = u.user_id
WHERE st.transaction_type = 'stock_out'
ORDER BY st.created_at DESC, st.transaction_id DESC;