<?php
$errors = array();

// get categories




if(isset($_REQUEST['do']))
{
	if($_REQUEST['do'] == 'addimage')
	{
		if(!$_POST['image_title'])
		{
			$errors[] = "You must enter an image title.";
		}
		elseif(!$_POST['image_category'])
		{
			$errors[] = "You must enter an image category.";
		}
		elseif (($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg" || $_FILES["image"]["type"] == "image/gif" || $_FILES["image"]["type"] == "image/x-png" || $_FILES["image"]["type"] == "image/png") && ($_FILES["image"]["size"] < 4000000))
		{
			$current_img=$_FILES['image']['name'];
			$extension = substr(strrchr($current_img, '.'), 1);
			$time = date("YmdHis");
			$new_image = uniqid() . $time;
			
			echo is_dir("../../brdi/cmsimages/");
			$originalImage  = "../../brdi/cmsimages/".$new_image.".".$extension;
			$destination   = "../../brdi/cmsimages/thumbs/".$new_image.".".$extension;
			$image_filename = $new_image.".".$extension;
			
			$action = move_uploaded_file($_FILES['image']['tmp_name'], $originalImage);
			
			$max_upload_width = 999;
			$max_upload_height = 100;
			if($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg"){
			    $image_source = imagecreatefromjpeg($originalImage) ;
			} 
			if($_FILES["image"]["type"] == "image/gif"){    
			    $image_source = imagecreatefromgif($originalImage);
			}
			if($_FILES["image"]["type"] == "image/bmp"){    
			    $image_source = imagecreatefromwbmp($originalImage);
			}
			if($_FILES["image"]["type"] == "image/x-png" || $_FILES["image"]["type"] == "image/png"){
			    $image_source = imagecreatefrompng($originalImage);
			}
			
			imagejpeg($image_source,$destination,100);
			chmod($destination,0644);
			
			list($image_width, $image_height) = getimagesize($destination);
			
			if($image_width>$max_upload_width || $image_height >$max_upload_height){
			    $proportions = 1;
			
			    /*
			    if($image_width>$image_height){
			        $new_width  = $max_upload_width;
			        $new_height = round($max_upload_width/$proportions);
			    }       
			    else{*/
			        $new_height = $max_upload_height;
			        $height_percent = $max_upload_height / $image_height;
			        $new_width = $image_width * $height_percent;
			        //$new_width  = round($max_upload_height*$proportions);
			    //}       
			
			
			    $new_image = imagecreatetruecolor($new_width , $new_height);
			    $image_source = imagecreatefromjpeg($destination);
			
			    imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			    imagejpeg($new_image, $destination, 100); // save
			    imagedestroy($new_image);
			}
			$data = array($_SESSION['client']['client_id'], $image_filename, $_POST['image_title'], $_POST['image_description'], $_POST['image_category'], date("Y-m-d H:i:s"));
			$dbh = $db->prepare("INSERT INTO client_images (client_id, image_filename, image_title, image_description, image_category, image_uploaded) VALUES (?, ?, ?, ?, ?, ?)");
			$dbh->execute($data);
		}
		else
		{
			$errors[] = "Error uploading image";
		}
	}
	elseif($_REQUEST['do'] == 'deleteimage' && isset($_GET['image_id']))
	{	
		// check if it's their image
		$data = array($_SESSION['client']['client_id'], $_GET['image_id'], 1);
		$dbh = $db->prepare("SELECT id FROM client_images WHERE client_id=? and id=? and image_active=? LIMIT 1");
		$dbh->execute($data);
		$dbh->setFetchMode(PDO::FETCH_ASSOC);
		$count = sizeof($dbh->fetchAll());
		if($count > 0)
		{
			$data = array(0, $_GET['image_id'], $_SESSION['client']['client_id']);
			$dbh = $db->prepare("UPDATE client_images SET image_active=? WHERE id=? AND client_id=?");
			$dbh->execute($data);
			$errors[] = "Image deleted";
		}
		else
		{
			$errors[] = "Error deleting image: invalid id";
		}
	}
}
$data = array($_SESSION['client']['client_id']);
$dbh = $db->prepare("SELECT DISTINCT(image_category) FROM client_images WHERE client_id=?");
$dbh->execute($data);
$dbh->setFetchMode(PDO::FETCH_ASSOC);
$image_categories = $dbh->fetchAll();
$image_categories_datasource = "";
foreach($image_categories as $cat)
{
	$image_categories_datasource .= "\"".trim($cat['image_category'])."\"";
	if($image_categories[sizeof($image_categories)-1]['image_category'] !== $cat['image_category']) $image_categories_datasource .= ",";
}
?>
<div id="content" class="row-fluid">
	<div class="span3 well" style="padding: 8px 0;">
		<?php include ('actions/includes/menu.php'); ?>
	</div>
	<div class="span9 well page_menu">
		<div class="page-header"><h1>Image Manager</h1></div>
		<?php
		if(!empty($errors))
		{
			foreach($errors as $err)
			{
				echo "<div class='alert alert-error alert-option'>{$err}<div style='clear: both;'></div></div>";
			}
		}
		?>
		<form method="post" action="?action=imagemanager" enctype="multipart/form-data">
			
			<input type="hidden" name="do" value="addimage" />
			
			<div class="row-fluid">
				<div class="span6">
					<label>Image Title</label>
					<input type="text" name="image_title" class="input-large span11" value="<?php if(sizeof($errors) > 0 && isset($_POST['image_title'])) echo $_POST['image_title']; ?>"/>
				</div>
				<div class="span6">
					<label>Image Category</label>
					<input id="image-category" type="text" name="image_category" class="input-large span11" autocomplete="off" data-provide="typeahead" data-items="4" data-source='[<?php echo $image_categories_datasource; ?>]' value="<?php if(sizeof($errors) > 0 && isset($_POST['image_category'])) echo $_POST['image_category']; ?>"/>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<label>Image Description</label>
					<textarea name="image_description" class="input-large span11" rows="3"><?php
						if(sizeof($errors) > 0 && isset($_POST['image_description'])) echo $_POST['image_description'];
					?></textarea>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span10">
					<a class="btn image-file-button">Choose Image</a>
					<input class="image-file" style="display:none;" type="file" name="image"/>
					<span class="image-name"></span>
				</div>
				<div class="span2">
					<input class="btn" type="submit" name="image" value="Upload" />
				</div>
			</div>
			<br />
		</form>
		<br><br><br>
		<table width="700" class="table table-hover">
			<thead>
				<tr>
					<th align="left" width="350">Title</th>
					<th align="left">Category</th>
					<th align="left">Date Added</th>
				</tr>
			</thead>
			<tbody>
		<?php
			$data = array($_SESSION['client']['client_id'], 1);
			$dbh = $db->prepare("SELECT * FROM client_images WHERE client_id=? AND image_active=? ORDER BY image_uploaded");
			$dbh->execute($data);
			$image = $dbh->fetchAll();
			
			foreach($image as $k=>$v)
			{
				echo "<tr><td><a target='_blank' href='/brdi/cmsimages/{$v['image_filename']}'>{$v['image_title']}</a></td><td>{$v['image_category']}</td><td>".date("m-d-Y", strtotime($v['image_uploaded']))."</td></tr>";
			}
			
		?>
			</tbody>
		</table>
		
	</div>
</div>
<script>
$('.image-file-button').each(function() {
      $(this).off('click').on('click', function() {
           $(this).siblings('.image-file').trigger('click');
      });
});
$('.image-file').each(function() {
      $(this).change(function () {
           $(this).siblings('.image-name').html(this.files[0].name);
      });
});
</script>