<?php 

if(!isset($_SESSION)){
    session_start();
}

header("Content-Type: text/plain");

require_once "../db/connection.php";

if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true){
    echo "Unauthorized access!";
    exit;
}

$userRole = isset($_SESSION["active_account_type"]) ? $_SESSION["active_account_type"] : "";
if(strtolower($userRole) != "seller"){
    echo "Only sellers can register products!";
    exit;
}

$userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "";

// Get POST data
$productTitle = isset($_POST["productTitle"]) ? trim($_POST["productTitle"]) : "";
$description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
$categoryId = isset($_POST["category"]) ? intval($_POST["category"]) : 0;
$price = isset($_POST["price"]) ? floatval($_POST["price"]) : 0;
$level = isset($_POST["level"]) ? trim($_POST["level"]) : "";
$status = isset($_POST["status"]) ? trim($_POST["status"]) : "";

// validation
if(empty($productTitle)){
   echo "Product name is required!";
   exit;
}else if(strlen($productTitle) > 150){
    echo "Product title must be less than 150 characters!";
    exit;
}else if(empty($description)){
    echo "Product description is required!";
    exit;
}else if(strlen($description) > 1000){
    echo "Product description must be less than 1000 characters";
    exit;
}else if(empty($categoryId)){
    echo "Please select a valid category!";
    exit;
}else {

    // verify category exists
    $categoryCheck = Database::search("SELECT `id` FROM `category` WHERE `id`=?","i",[$categoryId]);
    $validLevels = ["Beginner","Intermediate","Advanced"];
    $validStatus = ["active","inactive"];

    if(!$categoryCheck || $categoryCheck->num_rows == 0){
        echo "Invalid category selected";
        exit;
    }else if($price <= 0){
        echo "Price must be greater than 0";
        exit;
    }else if(empty($level)){
        echo "Please select a level";
        exit;
    }else if(!in_array($level, $validLevels)){   // FIXED: must be !in_array
        echo "Invalid level selected";
        exit;
    }else if(empty($status)){
        echo "Please select a status";
        exit;
    }else if(!in_array($status, $validStatus)){
        echo "Invalid status selected";
        exit;
    }else{

        // handle file upload
        if(!isset($_FILES["productImage"]) || $_FILES["productImage"]["error"] != UPLOAD_ERR_OK){
            echo "Please upload a product image.";
            exit;
        }

        $image = $_FILES["productImage"];

        // validate the image
        $allowedMimes = ["image/jpeg","image/png","image/webp","image/gif"];
        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fInfo, $image["tmp_name"]);   // FIXED variable name
        finfo_close($fInfo);

        if(!in_array($mimeType, $allowedMimes)){   // FIXED: must be !in_array
            echo "Invalid file type. Only JPG, PNG, GIF and WebP are allowed";
            exit;
        }

        // check file size (5MB max)
        $maxSize = 5 * 1024 * 1024;   // FIXED variable name
        if($image["size"] > $maxSize){
            echo "Image size must be less than 5MB";
            exit;
        }

        // create upload directory if not exists
        $uploadDir = __DIR__ ."/../uploads/products/";
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0755, true);
        }

        // generate unique file name
        $fileExtension = pathinfo($image["name"], PATHINFO_EXTENSION);
        $fileName = "product_". $userId . "_" .bin2hex(random_bytes(4)). "." . $fileExtension;
        $filePath = $uploadDir . $fileName;
        $fileUrl = "uploads/products/" . $fileName;

        // Move uploaded file
        if(!move_uploaded_file($image["tmp_name"], $filePath)){
            echo "Failed to upload image. Please try again.";
            exit;
        }

        // insert product to the database
        try{
            $result = Database::iud(
                "INSERT INTO `product` (`seller_id`,`category_id`,`title`,`description`,`price`,`level`,`status`,`image_url`) 
                 VALUES (?,?,?,?,?,?,?,?)",
                "iissdsss",
                [$userId, $categoryId, $productTitle, $description, $price, $level, $status, $fileUrl]
            );

            if($result){
                echo "success";
            }else{
                // delete uploaded file on error
                unlink($filePath);
                echo "Failed to register the product. Please try again.";
            }

        }catch(Exception $e){
            // delete uploaded file on error
            if(file_exists($filePath)){
                unlink($filePath);
            }
            echo "An error occurred: " . $e->getMessage();
        }
    }
}

?>
