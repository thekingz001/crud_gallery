<?php
// Start session
session_start();

$postData = $imgData = array();

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// Get posted data from session
if(!empty($sessData['postData'])){
    $postData = $sessData['postData'];
    unset($_SESSION['sessData']['postData']);
}

// Get image data
if(!empty($_GET['id'])){
    // Include and initialize DB class
    require_once 'DB.class.php';
    $db = new DB();
    
    $conditions['where'] = array(
        'id' => $_GET['id'],
    );
    $conditions['return_type'] = 'single';
    $imgData = $db->getRows('images_php', $conditions);
}

// Pre-filled data
$imgData = !empty($postData)?$postData:$imgData;

// Define action
$actionLabel = !empty($_GET['id'])?'Edit':'Add';
?>

<!-- Display status message -->
<?php if(!empty($statusMsg)){ ?>
<div class="col-xs-12">
    <div class="alert alert-<?php echo $statusMsgType; ?>"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>

<div class="row">
    <div class="col-md-6">
        <form method="post" action="postAction.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Image</label>
                <?php if(!empty($imgData['file_name'])){ ?>
                    <img src="uploads/images/<?php echo $imgData['file_name']; ?>">
                <?php } ?>
                <input type="file" name="image" class="form-control" >
            </div>
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter title" value="<?php echo !empty($imgData['title'])?$imgData['title']:''; ?>" >
            </div>
            <a href="manage.php" class="btn btn-secondary">Back</a>
            <input type="hidden" name="id" value="<?php echo !empty($imgData['id'])?$imgData['id']:''; ?>">
            <input type="submit" name="imgSubmit" class="btn btn-success" value="SUBMIT">
        </form>
    </div>
</div>