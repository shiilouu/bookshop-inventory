<?php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../auth/check_auth.php";

class TransactionHistory
{
    // Central filter: every value comes in as a string
    public function filter(
        string $transactionType = "",
        string $dateFrom = "",
        string $dateTo = ""
    ): array {
        /*
            Validation Phase
        */
        global $conn;

        $transactionType = trim($transactionType);
        $dateFrom        = trim($dateFrom);
        $dateTo          = trim($dateTo);

        if ($transactionType !== "" && !preg_match("/^(stock_in|stock_out)$/", $transactionType)) {
            throw new InvalidArgumentException("Invalid transaction type.");
        }

        $dateFrom = $this->validateDate($dateFrom, "Date from");
        $dateTo   = $this->validateDate($dateTo, "Date to");

        if ($dateFrom !== null && $dateTo !== null && $dateFrom > $dateTo) {
            throw new InvalidArgumentException("Date from must not be later than date to.");
        }

        // empty filter becomes NULL so the query skips it
        $transactionType = ($transactionType === "") ? null : $transactionType;

        /*
            Query Phase
        */
        $stmt = $conn->prepare(
            "SELECT st.transaction_id, b.title, u.full_name,
                    st.transaction_type, st.quantity, st.unit_price,
                    st.remarks, st.created_at
             FROM stock_transactions st
             JOIN books b ON st.book_id = b.book_id
             JOIN users u ON st.user_id = u.user_id
             WHERE (? IS NULL OR st.transaction_type = ?)
               AND (? IS NULL OR st.created_at >= ?)
               AND (? IS NULL OR st.created_at < DATE_ADD(?, INTERVAL 1 DAY))
             ORDER BY st.created_at DESC, st.transaction_id DESC"
        );

        $stmt->bind_param(
            "ssssss",
            $transactionType,
            $transactionType,
            $dateFrom,
            $dateFrom,
            $dateTo,
            $dateTo
        );

        $stmt->execute();
        $result = $stmt->get_result();

        $transactionList = [];
        while ($row = $result->fetch_assoc()) {
            // escape every value before it reaches the page
            $transactionList[] = array_map(
                fn($value) => htmlspecialchars(
                    (string) $value,
                    ENT_QUOTES,
                    "UTF-8"
                ),
                $row
            );
        }

        $stmt->close();

        return $transactionList;
    }

    // Returns the date as YYYY-MM-DD
    private function validateDate(string $value, string $label): ?string
    {
        if ($value === "") {
            return null;
        }

        if (!preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $value, $parts)
            || !checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1])) {
            throw new InvalidArgumentException($label . " must be a valid date (YYYY-MM-DD).");
        }

        return $value;
    }
}