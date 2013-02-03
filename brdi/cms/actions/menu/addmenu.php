<?php
$errors = array();

if(isset($_POST['do']))
{
	if($_POST['do'] == 'addmenu')
	{
		if(!$_POST['menu_title']) array_push($errors, "You must enter a menu title.");
		
		if(empty($errors))
		{
			try
			{
				if(!$_POST['menu_subtitle']) $_POST['menu_subtitle'] == null;
				$data = array($_POST['menu_title'], $_POST['menu_subtitle'], $_SESSION['client']['client_id']);
				$dbh = $db->prepare("INSERT INTO menus (menu_title, menu_subtitle, menu_client) VALUES (?, ?, ?)");
				$dbh->execute($data);
				header('location: ?action=menu');
			}
			catch (PDOException $e)
			{
				array_push($errors, "There was an error updated the menu database. ".$e->getMessage());
			}
		}
	}
}
?>
<div id="content" class="row-fluid">
	<div class="span3 well" style="padding: 8px 0;">
		<?php include ('actions/includes/menu.php'); ?>
	</div>
	<div class="span9 well">
		<div class="page-header"><h1>Add Menu</h1></div>
		<?php
		if(!empty($errors))
		{
			foreach($errors as $err)
			{
				echo "<div class='alert alert-error'>{$err}<div style='clear: both;'></div></div>";
			}
		}		
		?>
		
		<form action="?action=menu/addmenu" method="post">
			<input type="hidden" name="do" value="addmenu" />
			
			<div class="row-fluid">
				<div class="span6">
					<label>Menu Title</label>
					<input type="text" name="menu_title" class="input-large span11" placeholder="Breakfast" />
				</div>
				<div class="span6">
					<label>Menu Subtitle</label>
					<input type="text" name="menu_subtitle" class="input-large span11" placeholder="Served 7am - 1pm" />
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<button type="submit" class="btn btn-large btn-primary">Add Menu</button>
				</div>
			</div>
			
			
		</form>
		
	</div>
</div>