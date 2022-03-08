<?php
// Start session
session_start();

// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// Get image data
if(!empty($_GET['id'])){
    // Include and initialize DB class
    require_once 'DB.class.php';

    $sql = "
        SELECT 
        images_php.id,
        images_php.file_name,
        images_php.title,
        images_php.created,
        store.name as s_name
        FROM 
            images_php
        INNER JOIN 
            store
        ON
        images_php.title = store.id
        WHERE images_php.id = ".$_GET['id']."
    ";
    $images = mysqli_query($con, $sql);

    foreach ($images as $test) {
        echo json_encode($test) ;
    }
    // $db = new DB();
    // $conditions['where'] = array(
    //     'id' => $_GET['id'],
    // );
    // $conditions['return_type'] = 'single';
    // $imgData = $db->getRows('images_php', $conditions);
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<!-- Display status message -->
<?php if(!empty($statusMsg)){ ?>
<div class="col-xs-12">
    <div class="alert alert-<?php echo $statusMsgType; ?>"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>

<div class="row">
    <div class="col-md-5">
        <h4 class="mb-3"><label>Image</label></h4>
        <form method="post" action="postAction.php" enctype="multipart/form-data">
            <div class="form-group">
                
			<?php
			foreach ($images as $data)
			{
                //echo "<script type='text/javascript'>alert('".$test["id"]."');</script>";
			?>
                <img src="uploads/images/<?php echo $data['file_name']; ?>" width="500" height="500">
                </div>
                <div class="form-group">
                    <label>ร้านค้า</label>
                    <input type="text" name="title" class="form-control mb-3" placeholder="Enter title" value="<?php echo $data['s_name']?>" disabled>
                </div>
                <a href="manage.php" class="btn btn-secondary">Back</a>
                <input type="hidden" name="id" value="<?php echo !empty($data['id'])?$data['id']:''; ?>">
            <?php } ?>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>