<?php
// Start session
session_start();

// Include and initialize DB class
//require_once 'DB.class.php';
include('DB.class.php'); 
$db = new DB();
// ดึงข้อมูล จากตาราง images_php
if(!empty($_GET['id_store'])){
    # code...
    $id_store = $_GET['id_store'];

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
        WHERE
            images_php.title = ". (string)$_GET['id_store'] .";";

        // echo "<script>console.log(" . $_GET['id_store'] . ");</script>";
        // echo "<script type='text/javascript'>alert('".$sql."');</script>";
}else {
    # code...
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
            images_php.title = store.id ;";
}

$select = "";
$images = mysqli_query($con, $sql);
// foreach ($images as $test) {
    // echo json_encode($test) ;
    // echo "<script type='text/javascript'>alert('".$test."');</script>";
// }
//$images = $db->getRows('images_php');

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Get status message from session จัดการข้อความ Alert
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
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
    <div class="col-md-12 head">
        <h5></h5>
        <!-- Add link -->
        <div class="float-right">
            <select id="lmName1" name="lmName1" class="btn btn-secondary dropdown-toggle w-25 p-2 mb-3" onchange="myFunction()">
			<option value="">เลือกร้าน</option>
			<?php
			$strSQL = "SELECT * FROM store ORDER BY id ASC";
			$objQuery = mysqli_query($con, $strSQL);
            $i = 1;
			foreach ($objQuery as $test)
			{
                // echo "<script>console.log(" . $i . ");</script>";
                if ($i == $_GET['id_store']) {
                    # code...
                    echo "<script>console.log(" . $i . ");</script>";
                    $count = 'selected';
                    
                }else {
                    # code...
                    $count = '';
                }
                $i = $i+1;

                
                //echo "<script type='text/javascript'>alert('".$test["id"]."');</script>";
			?>
			<option value="<?php echo $test["id"];?>" <?php echo $count;?>><?php echo $test["name"];?></option>
			<?php
			}
			?>
		</select>
        <a href="addEdit.php" class="btn btn-success p-2 mb-3">สร้างรูปใหม่</a>
        </div>

    </div>
    
    <!-- List the images -->
    <table class="table table-striped table-bordered text-center">
        <thead class="thead-dark">
            <tr>
                <th width="5%">รหัส</th>
                <th width="12%">รูป</th>
                <th width="45%">ร้านค้า</th>
                <th width="17%">สร้างเมื่อ</th>
                <th width="13%">เมนู</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(!empty($images)){
                foreach($images as $row){
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><a href="show.php?id=<?php echo $row['id']; ?>"  ><img src="<?php echo 'uploads/images/'.$row['file_name']; ?>" alt=""  width="150" height="60"/></a></td>
                <td><?php echo $row['s_name']; ?></td>
                <td><?php echo $row['created']; ?></td>
                <td>
                    <a href="addEdit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">edit</a>
                    <a href="postAction.php?action_type=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete data?')?true:false;">delete</a>
                </td>
            </tr>
            <?php } }else{ ?>
            <tr><td colspan="6">No image(s) found...</td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>

    function leaveChange(control) {
        if (document.getElementById("leave").value != "1"){
            alert('1');
        }     
        else{
            alert('err');
        }        
    }

    function myFunction() {
        let test = document.getElementById("lmName1").value;
        window.location.replace('http://localhost/kan_it_report_p.cab/crud_gallery/manage.php?id_store='+test);
        // alert(test);
    }

</script>