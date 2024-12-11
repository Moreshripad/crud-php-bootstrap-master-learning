<?php
require_once 'database.php';

class User {
    private $conn;


    // Constructor
    public function __construct() {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    // Execute queries SQL
    public function runQuery($sql) {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    // Insert
    public function insert($name, $email, $image) {
        try {
            // Check if an image is uploaded
            if (isset($image) && $image['error'] == 0) {
                // Define upload directory and generate a unique filename
                $uploadDir = 'uploads/';
                $imageName = time() . '_' . basename($image['name']);
                $targetFilePath = $uploadDir . $imageName;

              
    
                // Create the uploads directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
    
                // Move the uploaded file to the uploads directory
                if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
                    // Prepare the SQL statement
                    $stmt = $this->conn->prepare(
                        "INSERT INTO crud_users (name, email, photo) VALUES (:name, :email, :photo)"
                    );
                    $stmt->bindparam(":name", $name);
                    $stmt->bindparam(":email", $email);
                    $stmt->bindparam(":photo", $targetFilePath);
                    $stmt->execute();
    
                    return $stmt;
                } else {
                    throw new Exception("Failed to upload the image.");
                }
            } else {
                throw new Exception("No valid image uploaded.");
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    

    // Update
    public function update($name, $email, $id) {
        try {
            $stmt = $this->conn->prepare("UPDATE crud_users SET name=:name, email=:email WHERE id=:id");
            $stmt->bindparam(":name", $name);
            $stmt->bindparam(":email", $email);
            $stmt->bindparam(":id", $id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Delete
    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM crud_users WHERE id=:id");
            $stmt->bindparam(":id", $id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Redirect URL method
    public function redirect($url) {
        header("Location: $url");
    }

    // products
    public function insertProduct($Product, $Price, $Description, $photo) {
        try {
            // If an image is uploaded
            if ($photo && $photo['error'] == 0) {
                // Define the upload directory and create a unique filename
                $uploadDir = 'uploads/';
                $imageName = time() . '_' . basename($photo['name']);
                $targetFilePath = $uploadDir . $imageName;
    
                // Check if the upload directory exists; if not, create it
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
    
                // Move the uploaded file to the desired location
                if (move_uploaded_file($photo['tmp_name'], $targetFilePath)) {
                    // Prepare and execute the INSERT query
                    $stmt = $this->conn->prepare(
                        "INSERT INTO products (product_name, price, description, photo) VALUES (:product_name, :price, :description, :photo)"
                    );
                    $stmt->bindParam(":product_name", $Product);
                    $stmt->bindParam(":price", $Price);
                    $stmt->bindParam(":description", $Description);
                    $stmt->bindParam(":photo", $targetFilePath);
                    $stmt->execute();
                    
                    return true; // Success
                } else {
                    throw new Exception("Failed to upload the image.");
                }
            } else {
                throw new Exception("No valid image uploaded.");
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    // public function insertproduct($Product, $Price, $Description) {
    //     try {
    //         $stmt = $this->conn->prepare("INSERT INTO products (product_name, price, description) VALUES (:product_name, :price, :description)");
    //         $stmt->bindparam(":product_name", $Product);
    //         $stmt->bindparam(":price", $Price);
    //         $stmt->bindparam(":description", $Description);
    //         $stmt->execute();
    //         return $stmt;
    //     } catch (PDOException $e) {
    //         echo $e->getMessage();
    //     }
    // }
 


    public function productdelete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id=:id");
            $stmt->bindparam(":id", $id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
 
   

}
require_once 'User.php';


?>