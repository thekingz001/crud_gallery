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
    //require_once 'DB.class.php';
    include('DB.class.php'); 

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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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
                <label>รูป</label>
                <?php if(!empty($imgData['file_name'])){ ?>
                    <img src="uploads/images/<?php echo $imgData['file_name']; ?>">
                <?php } ?>
                <input type="file" name="image" class="form-control" >
            </div>
            <div class="form-group">
                <label>ร้านค้า</label>
                <input type="text" name="title" class="form-control" placeholder="กรอกฃื่อร้านค้า" value="<?php echo !empty($imgData['title'])?$imgData['title']:''; ?>" >
            </div> 
            <input type="submit" name="imgSubmit" class="btn btn-success form-control mt-3" value="ยืนยัน" >
            <a href="manage.php" class="btn btn-secondary form-control mt-1">กลับ</a>
            <input type="hidden" name="id" value="<?php echo !empty($imgData['id'])?$imgData['id']:''; ?>">
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>