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
    $stmt = $objUser->runQuery("SELECT * FROM crud_users WHERE id=:id");
    $stmt->execute(array(":id" => $id));
    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $id = null;
    $rowUser = null;
}

// POST0000
if (isset($_POST['btn_save'])) {
    $name = strip_tags($_POST['name']);
    $email = strip_tags($_POST['email']);
    $photo = isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 ? $_FILES['photo'] : null;


    try {
        if ($id != null) {
            if ($objUser->update($name, $email, $id)) {
                $objUser->redirect('index.php?updated');
            } else {
                $objUser->redirect('index.php?error');
            }
        } else {
            if ($objUser->insert($name, $email, $photo,)) {
                $objUser->redirect('index.php?inserted');
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
                <h1 style="margin-top: 10px">DataTable</h1>
                <p>Required fields are in (*).</p>
                <form  method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input type="text" class="form-control" id="id" name="id" readonly
                            value="<?php echo isset($rowUser['id']) ? $rowUser['id'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?php echo isset($rowUser['name']) ? $rowUser['name'] : ''; ?>"
                            placeholder="First and last name" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address *</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo isset($rowUser['email']) ? $rowUser['email'] : ''; ?>"
                            placeholder="name@example.com" required maxlength="100">
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