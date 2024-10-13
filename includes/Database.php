<?php

require_once ('macros.php');
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Product.php");


class Database
{
    private $db;
    private static $instance;

    public function __construct()
    {
        if (!file_exists(DATABASE_DIR)) {
            throw new Exception("Database file not found: " . DATABASE_DIR);
        }

        $this->db = new PDO("sqlite:" . DATABASE_DIR);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->db;
    }

    private function emailTaken($email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }


    private function storeUserInSession($userid, $useremail)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_set_cookie_params(0, "/", "", true, true);
            session_start();
        }

        $_SESSION["userid"] = $userid;
        $_SESSION["email"] = $useremail;
        if(!isset($_SESSION["csrf"])){
            $_SESSION["csrf"] = generate_random_token();
        }
        return isset($_COOKIE["PHPSESSID"]);
    }

    public function registerUser($username, $email, $password): ?string
    {
        try {
            if ($this->emailTaken($email)) {
                return json_encode(array("status" => "error", "message" => "Email is already registered!"));
            }

            $options = ['cost' => 12];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT, $options);
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");

            if (!$stmt->execute(array($username, $email, $hashed_password))) {
                return json_encode(array("status" => "error", "message" => "Failed to register user."));
            }

            $userId = $this->db->lastInsertId();
            if (!$this->storeUserInSession($userId, $email)) {
                throw new Exception("Couldn't store user in session");
            }

            return json_encode(array("status" => "success", "message" => "Registered successfully!"));

        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        } catch (Exception $e) {
            return json_encode(array("status" => "error", "message" => "An unexpected error occurred: " . $e->getMessage()));
        }
    }


    public function loginUser($email, $password): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if (!$user) {
                return json_encode(array("status" => "error", "message" => "Email is not registered!"));
            } else if (password_verify($password, $user['password'])) {
                $this->storeUserInSession($user["userid"], $email);
                return json_encode(array("status" => "success", "message" => "Login succesfull!"));
            }
            return json_encode(array("status" => "error", "message" => "Password is incorrect!"));

        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        } catch (Exception $e) {
            return json_encode(array("status" => "error", "message" => "An unexpected error occurred: " . $e->getMessage()));
        }
    }

    public function addProduct($userId, $categoryId, $conditionId, $gender, $name, $description, $basePrice, $discount, $size, $visible = true): ?string
    {
        try {

            $stmt = $this->db->prepare("INSERT INTO products (sellerId
            ,categoryId
            ,conditionId
            ,size
            ,gender
            ,name
            ,description
            ,basePrice
            ,discount
            ,visible) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt->execute([$userId, $categoryId, $conditionId, $size, $gender, $name, $description, $basePrice, $discount, $visible])) {
                return json_encode(array("status" => "error", "message" => "Couldn't register new product!"));
            }
            return json_encode(array("status" => "success", "message" => "Registered product succesfully!", "productId" => $this->db->lastInsertId()));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "An unexpected error occurred: " . $e->getMessage()));
        }
    }

    public function changeUsersname($id, $username): ?string
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET username = ? WHERE userid = ?");
            $stmt->execute([$username, $id]);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                return json_encode(array("status" => "success", "message" => "Username changed!"));
            } else {
                return json_encode(array("status" => "error", "message" => "Couldn't change username!"));
            }
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }


    public function changeEmail($id, $email): ?string
    {
        try {
            if ($this->emailTaken($email)) {
                return json_encode(array("status" => "error", "message" => "Email is already registered!"));
            }

            $stmt = $this->db->prepare("UPDATE users SET email = ? WHERE userid = ?");
            $stmt->execute([$email, $id]);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                return json_encode(array("status" => "success", "message" => "email changed!"));
            } else {
                return json_encode(array("status" => "error", "message" => "Couldn't change email!"));
            }
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }

    public function getAllCategories(): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($categories) {
                return json_encode(array("status" => "success", "categories" => $categories));
            } else {
                return json_encode(array("status" => "error", "message" => "No categories found."));
            }
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }

    public function changeAddress($id, $address): ?string
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET address = ? WHERE userid = ?");
            $stmt->execute([$address, $id]);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                return json_encode(array("status" => "success", "message" => "Address changed!"));
            } else {
                return json_encode(array("status" => "error", "message" => "Couldn't change address!"));
            }
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }
    public function changeZIP($id, $zip): ?string
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET postalCode = ? WHERE userid = ?");
            $stmt->execute([$zip, $id]);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                return json_encode(array("status" => "success", "message" => "Postal code changed!"));
            } else {
                return json_encode(array("status" => "error", "message" => "Couldn't change postal code!"));
            }
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }
    public function changePhone($id, $phone): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM USERS WHERE phoneNumber = ?");
            $stmt->execute([$phone]);
            if ($stmt->fetchColumn() > 0) {
                return json_encode(array("status" => "error", "message" => "Phone number is already taken!"));
            }

            $stmt = $this->db->prepare("UPDATE users SET phoneNumber = ? WHERE userid = ?");
            $stmt->execute([$phone, $id]);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                return json_encode(array("status" => "success", "message" => "Phone number changed!"));
            } else {
                return json_encode(array("status" => "error", "message" => "Couldn't change phone number!"));
            }
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }


    public function getUser($id): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT userid, username, email, address, postalCode, phoneNumber  FROM USERS WHERE userid = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }


    public function changePassword($userId, $currentPassword, $newPassword): ?string
    {
        try {
            // Fetch the user data from the database
            $stmt = $this->db->prepare("SELECT password FROM users WHERE userid = ?");
            $stmt->execute([$userId]);
            $userData = $stmt->fetch();

            // Check if user exists
            if (!$userData) {
                return json_encode(array("status" => "error", "message" => "User not found."));
            }

            // Verify if the current password provided matches the one stored in the database
            if (!password_verify($currentPassword, $userData['password'])) {
                return json_encode(array("status" => "error", "message" => "Current password is incorrect!"));
            }

            // Hash the new password
            $options = ['cost' => 12];
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT, $options);

            // Update the password in the database
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE userid = ?");
            $stmt->execute([$hashedNewPassword, $userId]);

            // Check if the password was successfully updated
            if ($stmt->rowCount() > 0) {
                return json_encode(array("status" => "success", "message" => "Password changed successfully!"));
            } else {
                return json_encode(array("status" => "error", "message" => "Failed to change password."));
            }
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        } catch (Exception $e) {
            return json_encode(array("status" => "error", "message" => "An unexpected error occurred: " . $e->getMessage()));
        }
    }

    public function getProductPhotos($productId): ?array
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . "/" . "database/uploads/product/" . $productId . "/*";
        $files = glob($path);
        $filenames = array_map('basename', $files);
        return $filenames;
    }


    public function getUserProducts($userid): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM products WHERE sellerId = ? AND sold = FALSE");
            $stmt->execute([$userid]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $message = array("status" => "success", "message" => "User's products retrieval succesfull", "products" => $products);

            for ($i = 0; $i < count($products); $i++) {
                $photos = $this->getProductPhotos($products[$i]["productId"]);
                $message["products"][$i]["photos"] = $photos;
            }

            return json_encode($message);
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }
    public function getAllProducts(): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT (productId) FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
    public function getAllConditions(): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT conditionId, name, description FROM conditions");
            $stmt->execute();
            $conditions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode(array("status" => "success", "message" => "Retrieved successfully conditions", "conditions" => $conditions));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Couldn't retrieve conditions"));
        }
    }


    public function getProduct($productId): ?array
    {
        try {
            if(session_status() == PHP_SESSION_NONE) {session_start();}
            $stmt = $this->db->prepare(
                "SELECT * FROM products 
                WHERE productId = ? AND (visible = TRUE and sold = FALSE OR sellerId = ?)"
            );
            $stmt->execute([$productId, $_SESSION["userid"]]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            return array("productInfo" => $product, "productPhotos" => $this->getProductPhotos($productId));

        } catch (PDOException $e) {
            return array("productInfo" => null, "productPhotos" => null);
        }
    }

    public function toggleProductVisible($productId): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT (visible) FROM products WHERE productId = ?");
            $stmt->execute([$productId]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $visible = $products[0]["visible"];
            $stmt = $this->db->prepare("UPDATE products SET visible = ? WHERE productId = ?");
            $stmt->execute([!$visible, $productId]);
            return json_encode(array("status" => "success", "message" => "Toggled product visibility succesfully!"));

        } catch (PDOException $e) {
            return json_encode(array("status" => "success", "message" => "Database error: " . $e->getMessage()));
        }
    }

    public function getProducts($category, $size, $minPrice, $maxPrice, $condition, $page, $userId): ?string
    {
        try {
            $minPrice = floatval($minPrice);
            $maxPrice = floatval($maxPrice);
            $stmt = $this->db->prepare(
                "SELECT *, 
                (basePrice * (1 - (discount/100))) AS discountedPrice 
                FROM products
                 WHERE 
                     (? IS NULL OR categoryId = ?) 
                     AND (? IS NULL OR size = ? )
                     AND discountedPrice - ? >= 0
                     AND discountedPrice - ? <= 0
                     AND (? IS NULL OR conditionId = ?)
                     AND visible = TRUE AND sold = FALSE 
                     AND (sellerId != ? OR ? IS NULL)
                LIMIT ?, 50"
            );

            $stmt->execute([$category, $category, $size, $size, $minPrice, $maxPrice, $condition, $condition, $userId, $userId, ($page) * 50]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $photos = [];
            for ($idx = 0; $idx < count($products); $idx++) {
                $productPhotos = $this->getProductPhotos($products[$idx]["productId"]);
                $photos[$products[$idx]["productId"]] = $productPhotos;
            }

            return json_encode(array("status" => "success", "message" => "Products were retrieved succesfully", "products" => $products, "photos" => $photos));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "" . $e->getMessage()));
        }
    }

    public function getCategoryName($categoryId): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT categoryName FROM categories WHERE categoryId = ?");
            $stmt->execute([$categoryId]);
            return $stmt->fetchColumn(0);
        } catch (PDOException $e) {
            return "Error in database: " . $e->getMessage();
        }
    }
    public function getConditionName($conditionId): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT name FROM conditions WHERE conditionId = ?");
            $stmt->execute([$conditionId]);
            return $stmt->fetchColumn(0);
        } catch (PDOException $e) {
            return "Error in database: " . $e->getMessage();
        }
    }
    public function getUserMessages($userId): ?string
    {
        try {

            $stmt = $this->db->prepare(
                "SELECT m.*
            FROM messages m
            JOIN (
                SELECT productId, MAX(date || ' ' || time) AS latest_datetime
                FROM messages
                WHERE senderId = ? OR receiverId = ?
                GROUP BY productId
            ) latest ON m.productId = latest.productId AND (m.date || ' ' || m.time) = latest.latest_datetime
            WHERE m.senderId = ? OR m.receiverId = ?
            ORDER BY m.date DESC, m.time DESC"
            );
            $stmt->execute([$userId, $userId, $userId, $userId]);
            return json_encode(array("status" => "success", "message" => "retrieved succesfully user's messages!", "messages" => $stmt->fetchAll(PDO::FETCH_ASSOC)));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Couldn't retrieve user's messages: " . $e->getMessage()));
        }
    }

    public function getMessagesWithId($clientId, $productId): ?string
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * 
                FROM messages
                WHERE (senderId = ? OR receiverId = ?) AND productId = ?
                ORDER BY date || ' ' || time DESC
                LIMIT 50
                "
            );
            $stmt->execute([$clientId, $clientId, $productId]);
            return json_encode(array("status" => "success", "message" => "retrieved succesfully user's messages!", "messages" => $stmt->fetchAll(PDO::FETCH_ASSOC)));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Couldn't retrieve user's messages: " . $e->getMessage()));
        }
    }

    public function sendMessage($productId, $senderId, $receiverid, $message, $date, $time): ?string
    {
        try {

            $stmt = $this->db->prepare(
                "INSERT INTO messages (senderId, receiverId, productId, date, time, content) VALUES (?,?,?,?,?,?)
                "
            );
            $stmt->execute([$senderId, $receiverid, $productId,$date, $time, $message]);
            $message = $this->db->lastInsertId();
            $stmt = $this->db->prepare("SELECT * FROM messages WHERE messageId = ?");
            $stmt->execute([$message]);


            return json_encode(array("status" => "success", "message" => "Sent message succesfully!", "data" => $stmt->fetch(PDO::FETCH_ASSOC)));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Couldn't send message: " . $e->getMessage()));
        }
    }
    public function isUserAdmin($userid): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT is_admin FROM users WHERE userid = ?");
            $stmt->execute([$userid]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (bool) $row["is_admin"];
        } catch (PDOException $e) {
            return false;
        }

    }
    public function elevateUserToAdmin($userEmail): ?string
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET is_admin = TRUE WHERE email = ?");
            $stmt->execute([$userEmail]);
            $affectedRows = $stmt->rowCount();
            if ($affectedRows === 0) {
                throw new InvalidArgumentException("Email is not registered!");
            }
            return json_encode(array("status" => "success", "message" => "Elevated user succesfully!"));

        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        } catch (InvalidArgumentException $e) {
            return json_encode(array("status" => "error", "message" => $e));
        }
    }
    public function addCondition($name, $description): ?string
    {

        try {
            $stmt = $this->db->prepare("INSERT INTO conditions (name, description) VALUES (?,?)");
            $stmt->execute([$name, $description]);
            $affectedRows = $stmt->rowCount();
            if ($affectedRows === 0) {
                throw new InvalidArgumentException("Couldn't register condition!");
            }
            return json_encode(array("status" => "success", "message" => "Added condition succesfully!"));

        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        } catch (InvalidArgumentException $e) {
            return json_encode(array("status" => "error", "message" => $e));
        }
    }

    public function addCategory($name): ?string
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO categories (categoryName) VALUES (?)");
            $stmt->execute([$name]);
            $affectedRows = $stmt->rowCount();
            if ($affectedRows === 0) {
                throw new InvalidArgumentException("Couldn't register category!");
            }
            return json_encode(array("status" => "success", "message" => "Added category succesfully!"));

        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        } catch (InvalidArgumentException $e) {
            return json_encode(array("status" => "error", "message" => $e));
        }
    }

    public function addSize($categoryId, $size): ?string
{
    try {
        // Prepare and execute the query to fetch sizes
        $stmt = $this->db->prepare("SELECT sizes FROM categories WHERE categoryId = ?");
        $stmt->execute([$categoryId]);
        $sizes = $stmt->fetch(PDO::FETCH_ASSOC)["sizes"];

        if ($sizes) {
            $sizesArray = explode(",", $sizes);
            if (in_array($size, $sizesArray)) {
                throw new InvalidArgumentException("Size is already registered!");
            }
            $sizesArray[] = $size;
            $sizes = implode(",", $sizesArray);
        } else {
            $sizes = $size;
        }

        $stmt = $this->db->prepare("UPDATE categories SET sizes = ? WHERE categoryId = ?");
        $stmt->execute([$sizes, $categoryId]);

        $affectedRows = $stmt->rowCount();
        if ($affectedRows === 0) {
            throw new InvalidArgumentException("Couldn't register size!");
        }
        
        // Return success message
        return json_encode(array("status" => "success", "message" => "Added size successfully!"));
    } catch (PDOException $e) {
        // Return database error message
        return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
    } catch (InvalidArgumentException $e) {
        // Return invalid argument error message
        return json_encode(array("status" => "error", "message" => $e->getMessage()));
    }
}


    public function getProductSearch($query, $userId): ?string
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * 
                FROM products 
                WHERE (name LIKE ? OR 
                name LIKE '% ' || ? OR
                name LIKE ? || ' %' OR
                name LIKE '% ' || ? || ' %')
                AND (sellerId != ? OR ? IS NULL) 
                AND visible = TRUE AND sold = FALSE
                "
            );

            $stmt->execute([$query, $query, $query, $query, $userId, $userId]);
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode(array("status" => "success", "message" => "Search query results retrieved!", "products" => $row));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }

    public function getSimilarProducts($productId): ?string
    {
        try {
            $categoryId = $this->db->prepare(
                "SELECT categoryId
                FROM products
                WHERE productId = ?
                "
            );
            $categoryId->execute([$productId]);
            $categoryId = $categoryId->fetch(PDO::FETCH_ASSOC)["categoryId"];
            if ($categoryId == null)
                throw new PDOException("No such product!");

            $stmt = $this->db->prepare(
                "SELECT * 
            FROM products
            WHERE categoryId = ? AND productId != ? AND sold = FALSE and visible = TRUE
            "
            );
            $stmt->execute([$categoryId, $productId]);
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode(array("status" => "success", "message" => "Retrieved similar products succesfully!", "products" => $row));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }

    public function removeProduct($productId): ?string
    {
        try {
            $stmt = $this->db->prepare(
                "DELETE FROM products
                WHERE productId = ?
            "
            );
            $stmt->execute([$productId]);
            $row = $stmt->rowCount();
            if ($row == 0)
                throw new PDOException("Product was not found in database!");
            $stmt = $this->db->prepare("DELETE FROM messages WHERE productId = ?");
            $stmt->execute([$productId]);
            return json_encode(array("status" => "success", "message" => "Product was succesfully removed!"));
        } catch (PDOException $e) {
            return json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
        }
    }

    public function isProductSold($productId): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT sold FROM products WHERE productId = ?");
            $stmt->execute([$productId]);
            $sold = $stmt->fetch(PDO::FETCH_ASSOC)["sold"];
            return (bool) $sold;
        } catch (PDOException $e) {
            return true;
        }

    }
    public function userOwnsProduct($productId, $userId): bool
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * 
                FROM products 
                WHERE productId = ? AND sellerId = ?"
            );

            $stmt->execute([$productId, $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false;
        } catch (PDOException $e) {
            return true;
        }
    }

    public function setProductSold($productId, $sold): bool
    {
        try {

            $stmt = $this->db->prepare(
                "UPDATE products
                SET sold = ?
                WHERE productId = ?
                "
            );
            $stmt->execute([$sold, $productId]);
            $stmt = $this->db->prepare(
                "DELETE FROM messages WHERE productId = ?"
            );
            $stmt->execute([$productId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function registerSale($userid, $productId, $name, $email, $address, $city, $zip, $cardNumber, $cardName): bool
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO sales
                (customerId, productId, customer_name, email, delivery_address, city, zip_code, card_number, cardholder_name) VALUES (?,?,?,?,?,?,?,?,?)
                "
            );
            $stmt->execute([$userid, $productId, $name, $email, $address, $city, $zip, $cardNumber, $cardName]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function deleteSale($productId): bool
    {
        try {
            $stmt = $this->db->prepare(
                "DELETE FROM sales
                WHERE productId = ?
                "
            );
            $stmt->execute([$productId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function checkout($cart, $userid, $name, $email, $address, $city, $zip, $cardNumber, $cardName): ?string
    {
        $changedProducts = [];
        try {
            // to checkout we need to make items not available and register the sale
            foreach ($cart as $key => $product) {
                $productId = $product->productId;

                if (!$this->setProductSold($productId, true))
                    throw new PDOException("");
                $changedProducts[] = $productId;
                if (!$this->registerSale($userid, $productId, $name, $email, $address, $city, $zip, $cardNumber, $cardName))
                    throw new PDOException("");

            }
            return json_encode(array("status" => "success", "message" => "Thank you for shopping with us!"));
        } catch (PDOException $e) {
            // error so we need to revert
            foreach ($changedProducts as $productId) {
                $this->setProductSold($productId, false);
                $this->deleteSale($productId);
            }
            return json_encode(array("status" => "error", "message" => "Something went wrong!"));
        }
    }

    public function getSoldProducts($userid): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT productId
                FROM products
                WHERE sellerId = ? AND SOLD = TRUE
            "
            );
            $stmt->execute([$userid]);
            $userProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $userProducts;
        } catch (PDOException $e) {
            return array();
        }
    }
    public function getSoldProductById($productId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT *
                FROM sales as s
                JOIN products as p ON p.productId = s.productId
                JOIN categories as c ON c.categoryId = p.categoryId
                WHERE s.productId = ?
            "
            );
            $stmt->execute([$productId]);
            $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $product;
        } catch (PDOException $e) {
            return array();
        }
    }
    public function getProductReceipt($productId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM sales as s
                JOIN products as p ON p.productId = s.productId
                WHERE s.productId = ?;"
            );
            $stmt->execute([$productId]);
            $userReceipt = $stmt->fetch(PDO::FETCH_ASSOC);
            return $userReceipt;

        } catch (PDOException $e) {
            return array();
        }
    }


}

