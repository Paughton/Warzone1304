<?php
$page = "uploadalliance";
include("./template/header.php");

$target_dir = "flag/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        output("File is not an image!");
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    output("Sorry, file already exists!");
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    output("Sorry, your file is too large!");
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    output("Sorry, your file was not uploaded!");
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		$update_flag = mysqli_query($mysqli, "UPDATE alliance SET flag='".basename( $_FILES["fileToUpload"]["name"])."' WHERE id='".$alliance['id']."'") or die(mysqli_error($mysqli));
    } else {
        output("Sorry, there was an error uploading your file!");
    }
}
include("./template/header.php");

?>