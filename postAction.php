<?php
// Start session
session_start();

// Include and initialize DB class
require_once 'DB.class.php';
$db = new DB();

$tblName = 'images_php';

// File upload path
$uploadDir = "uploads/images/";

// Allow file formats
$allowTypes = array('jpg','png','jpeg','gif');

// Set default redirect url
$redirectURL = 'manage.php';

$statusMsg = '';
$sessData = array();
$statusType = 'danger';
if(isset($_POST['imgSubmit'])){
    
     // Set redirect url
    $redirectURL = 'addEdit.php';

    // Get submitted data
    $image    = $_FILES['image'];
    $title    = $_POST['title'];
    $id        = $_POST['id'];

    if( $title == "กรุณาเลือกร้าน"){
        $redirectURL = 'addEdit.php';
    }
    
    // Submitted user data
    $imgData = array(
        'title'  => $title
    );
    
    // Store submitted data into session
    $sessData['postData'] = $imgData;
    $sessData['postData']['id'] = $id;
    
    // ID query string
    $idStr = !empty($id)?'?id='.$id:'';
    
    // If the data is not empty
    if((!empty($image['name']) && !empty($title)) || (!empty($id) && !empty($title))){
        
        if(!empty($image)){
            $fileName = basename($image["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
            
            if(in_array($fileType, $allowTypes)){
                // Upload file to server
                if(move_uploaded_file($image["tmp_name"], $targetFilePath)){
                    $imgData['file_name'] = $fileName;
                }else{
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            }else{
                $statusMsg = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
            }
        }

        if(!empty($id)){
            // Previous file name
            $conditions['where'] = array(
                'id' => $_GET['id'],
            );
            $conditions['return_type'] = 'single';
            $prevData = $db->getRows('images', $conditions);
            
            // Update data
            $condition = array('id' => $id);
            $update = $db->update($tblName, $imgData, $condition);
            
            if($update){
                // Remove old file from server
                if(!empty($imgData['file_name'])){
                    @unlink($uploadDir.$prevData['file_name']);
                }
                
                $statusType = 'success';
                $statusMsg = 'Image data has been updated successfully.';
                $sessData['postData'] = '';
                
                $redirectURL = 'manage.php';
            }else{
                $statusMsg = 'Some problem occurred, please try again.';
                // Set redirect url
                $redirectURL .= $idStr;
            }
        }elseif(!empty($imgData['file_name'])){
            // Insert data
            $insert = $db->insert($tblName, $imgData);
            
            if($insert){
                $statusType = 'success';
                $statusMsg = 'Image has been uploaded successfully.';
                $sessData['postData'] = '';
                
                $redirectURL = 'manage.php';
            }else{
                $statusMsg = 'Some problem occurred, please try again.';
            }
        }
    }else{
        $statusMsg = 'All fields are mandatory, please fill all the fields.';
    }
    
    // Status message
    $sessData['status']['type'] = $statusType;
    $sessData['status']['msg']  = $statusMsg;
}elseif(($_REQUEST['action_type'] == 'block') && !empty($_GET['id'])){
    // Update data
    $imgData = array('status' => 0);
    $condition = array('id' => $_GET['id']);
    $update = $db->update($tblName, $imgData, $condition);
    if($update){
        $statusType = 'success';
        $statusMsg  = 'Image data has been blocked successfully.';
    }else{
        $statusMsg  = 'Some problem occurred, please try again.';
    }
    
    // Status message
    $sessData['status']['type'] = $statusType;
    $sessData['status']['msg']  = $statusMsg;
}elseif(($_REQUEST['action_type'] == 'unblock') && !empty($_GET['id'])){
    // Update data
    $imgData = array('status' => 1);
    $condition = array('id' => $_GET['id']);
    $update = $db->update($tblName, $imgData, $condition);
    if($update){
        $statusType = 'success';
        $statusMsg  = 'Image data has been activated successfully.';
    }else{
        $statusMsg  = 'Some problem occurred, please try again.';
    }
    
    // Status message
    $sessData['status']['type'] = $statusType;
    $sessData['status']['msg']  = $statusMsg;
}elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){
    // Previous file name
    $conditions['where'] = array(
        'id' => $_GET['id'],
    );
    $conditions['return_type'] = 'single';
    $prevData = $db->getRows('images', $conditions);
                
    // Delete data
    $condition = array('id' => $_GET['id']);
    $delete = $db->delete($tblName, $condition);
    if($delete){
        // Remove old file from server
        @unlink($uploadDir.$prevData['file_name']);
        
        $statusType = 'success';
        $statusMsg  = 'Image data has been deleted successfully.';
    }else{
        $statusMsg  = 'Some problem occurred, please try again.';
    }
    
    // Status message
    $sessData['status']['type'] = $statusType;
    $sessData['status']['msg']  = $statusMsg;
}

// Store status into the session
$_SESSION['sessData'] = $sessData;
    
// Redirect the user
header("Location: ".$redirectURL);
exit();
?>