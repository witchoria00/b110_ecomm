<?php
require_once "dbconnect.php";
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['eid'])) {
    $productID = $_GET['eid'];
} elseif (isset($_GET['did'])) {
    try {
        $productID = $_GET['did'];
        $sql = "DELETE FROM product WHERE ProductID = ?";
        $stmt = $conn->prepare($sql); // prevent SQL injection
        $status = $stmt->execute([$productID]); // use correct variable

        if ($status) {
            $_SESSION['deleteSuccess'] = "Product ID $productID has been deleted ✅";
            header("Location: viewProduct.php");
            exit();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>
