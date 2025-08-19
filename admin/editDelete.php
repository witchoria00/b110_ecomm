<?php
require_once "dbconnect.php";
if (!isset($_SESSION)) {
    session_start();
}

try {
    $sql = "SELECT * FROM category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    echo $e->getMessage();
}

// Edit
if (isset($_GET['eid'])) {
    $productID = $_GET['eid'];
    try {
        $sql = "SELECT p.productId, p.productName, c.catName, p.category, p.price, p.description, p.qty, p.imgPath 
                FROM product p 
                JOIN category c ON p.category = c.catId
                WHERE p.productId = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$productID]);
        $product = $statement->fetch();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} 
// Delete
elseif (isset($_GET['did'])) {
    $productID = $_GET['did'];
    try {
        $sql = "DELETE FROM product WHERE productId = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$productID]);
        if ($status) {
            $_SESSION['deleteSuccess'] = "Product ID $productID has been deleted ✅";
            header("Location: viewProduct.php");
            exit();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} 
// Update
elseif (isset($_POST['updateBtn'])) {
    $productName = $_POST['pname'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $qty = $_POST['qty'];
    $pid = $_POST['pid'];

    // Handle optional image upload
    if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
        $fileImg = $_FILES['file'];
        $filePath = "productImage/" . $fileImg['name'];
        $status = move_uploaded_file($fileImg['tmp_name'], $filePath);
        if (!$status && isset($product['imgPath'])) {
            $filePath = $product['imgPath']; // fallback to old image if upload fails
        }
    } else {
        $filePath = $product['imgPath'] ?? '';
    }

    try { $pid = $_POST['pid']; //DML data Manipulate language, updae, delete, insert
        $sql = "UPDATE product 
                SET productName=?, category=?, price=?, description=?, qty=?, imgPath=? 
                WHERE productId=?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$productName, $category, $price, $description, $qty, $filePath, $pid]);
        if ($status) {
            $_SESSION['updateMessage'] = "Product with Product id $pid is updated✅";
            header("Location: viewProduct.php");
            exit();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php require_once "navbarcopy.php"; ?>
    </div>
    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-primary">Add New</button>
        </div>
        <div class="col-md-10 p-3">
            <form action="editDelete.php" method="post" enctype="multipart/form-data" class="form card p-4">
                <input type="hidden" name="pid" value="<?php echo $product['productId'] ?? ''; ?>">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="pname" class="form-control" 
                                   value="<?php echo $product['productName'] ?? ''; ?>">
                        </div>
                        <div class="mb-2">
                            <p class="alert alert-info">
                                Previously selected category: <?php echo $product['catName'] ?? ''; ?>
                            </p>
                            <select name="category" class="form-select">
                                <option value="0">Choose Category</option>
                                <?php
                                if (!empty($categories)) {
                                    foreach ($categories as $cat) {
                                        $selected = ($product['category'] ?? 0) == $cat['catId'] ? 'selected' : '';
                                        echo "<option value='{$cat['catId']}' $selected>{$cat['catName']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" class="form-control" 
                                   value="<?php echo $product['price'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" placeholder="Write Description Here..."><?php echo $product['description'] ?? ''; ?></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="qty" class="form-control" 
                                   value="<?php echo $product['qty'] ?? ''; ?>">
                        </div>
                        <div class="mb-2">
                            <?php if (!empty($product['imgPath'])): ?>
                                <img src="<?php echo $product['imgPath']; ?>" style="width:100px; height:100px;">
                            <?php endif; ?>
                            <label class="form-label">Product Image</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="mb-2">
                            <button type="submit" name="updateBtn" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
