<?php
// Show PHP errors
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);
require_once 'classes/user.php';

$objUser = new User();

// GET
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $objUser->runQuery("SELECT * FROM products WHERE id=:id");
    $stmt->execute(array(":id" => $id));
    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $id = null;
    $rowUser = null;
}

// POST
if (isset($_POST['btn_save'])) {
    $Product = strip_tags($_POST['Product']);
    $Price = strip_tags($_POST['Price']);
    $Description = strip_tags($_POST['Description']);

    // Handle file upload
    $photo = isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 ? $_FILES['photo'] : null;

    try {
        if ($id != null) {  // Update existing product
            if ($objUser->update($Product, $Price, $Description, $photo)) {
                $objUser->redirect('index.php?updated');
            } else {
                $objUser->redirect('index.php?error');
            }
        } else {  // Insert new product
            if ($objUser->insertProduct($Product, $Price, $Description, $photo)) {
                $objUser->redirect('index.php?productinserted');
            } else {
                $objUser->redirect('index.php?error');
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Head metas, css, and title -->
    <?php require_once 'includes/head.php'; ?>
</head>

<body>
    <!-- Header banner -->
    <?php require_once 'includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar menu -->
            <?php require_once 'includes/sidebar.php'; ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h1 style="margin-top: 10px">DataTableProduct</h1>
                <p>Required fields are in (*).</p>
                <form  method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" class="form-control" id="name" name="Product"
                            value="<?php echo isset($rowUser['Product']) ? $rowUser['Product'] : ''; ?>"
                            placeholder="Add-Product" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="email">Price</label>
                        <input type="number" class="form-control" id="price" name="Price"
                            value="<?php echo isset($rowUser['Price']) ? $rowUser['Price'] : ''; ?>" placeholder="Price"
                            required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="Price"> Description</label>
                        <input type="text" class="form-control" id="text" name="Description"
                            value="<?php echo isset($rowUser['Description']) ? $rowUser['Description'] : ''; ?>"
                            placeholder="Description" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="photo">Select a photo:</label>
                        <input type="file" name="photo" class="form-control" id="photo" accept="image/*" required>
                    </div>

                    <input type="submit" name="btn_save" class="btn btn-primary mb-2" value="Save">
                </form>
            </main>
        </div>
    </div>
    <!-- Footer scripts, and functions -->
    <?php require_once 'includes/footer.php'; ?>

</body>

</html>