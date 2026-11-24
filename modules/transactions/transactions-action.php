<?php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../auth/check_auth.php";

class TransactionAction
{
    // Central method: validates everything, then creates the transaction
    public function record(string $bookId, string $userId, string $transactionType, string $quantity, string $remarks) {   
        /* 
            Validation Phase
        */
        global $conn;
        $bookId = $this->validateInt($bookId, "Book ID");
        $userId = $this->validateInt($userId, "User ID");
        $quantity = $this->validateInt($quantity, "Quantity");

        $remarks = trim((string) $remarks);
        if (mb_strlen($remarks) > 255) {
            throw new InvalidArgumentException("Remarks must not exceed 255 characters.");
        }
        $remarks = ($remarks === "") ? null : $remarks;

        $transactionType = trim((string) $transactionType);
        if (!preg_match("/^(stock_in|stock_out)$/", $transactionType)) {
            throw new InvalidArgumentException("Invalid transaction type.");
        }

        /* 
            Transaction Phase
        */


                
        $procedure = ($transactionType === "stock_in") ? "sp_stock_in" : "sp_stock_out";

        try {
            $stmt = $conn->prepare("CALL $procedure(?, ?, ?, ?)");
            $stmt->bind_param("iiis", $bookId, $userId, $quantity, $remarks);
            $stmt->execute();
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() >= 1001 && $e->getCode() <= 1004) {
                throw new RuntimeException($e->getMessage());
            }
            throw $e;
        }
    }

    private function validateInt($value, $label)
    {
        if (!is_string($value) && !is_int($value)) {
            throw new InvalidArgumentException(
                $label . " must be a whole number greater than 0."
            );
        }

        $value = is_string($value) ? trim($value) : $value;

        $int = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if ($int === false) {
            throw new InvalidArgumentException(
                $label . " must be a whole number greater than 0."
            );
        }

        return $int;
    }
}