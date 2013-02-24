<?php
$errors = array();

function isMyMenu($menu)
{
	global $db;
	$data = array($_SESSION['client']['client_id'], $menu, 1);
	$dbh = $db->prepare("SELECT count(*) FROM menus WHERE menu_client=? AND menu_id=? AND menu_active=?");
	$dbh->execute($data);
	$menu_items = $dbh->fetchColumn();
	if($menu_items > 0) return true;
	else return false;
}

if(isset($_POST['do']))
{
	if($_POST['do'] == 'addmenuitem')
	{
		if(!isMyMenu($_POST['menu_id'])) array_push($errors, "Invalid Menu ID.");
		if(!$_POST['item_title']) array_push($errors, "You must enter an item title.");
		if(!$_POST['item_price']) array_push($errors, "You must enter an item price.");
		
		if(empty($errors))
		{
			try
			{
				if(!$_POST['item_description']) $_POST['item_description'] == null;
				if(!$_POST['item_tag']) $_POST['item_tag'] == null;

				$data = array(
					$_POST['item_title'],
					$_POST['item_description'],
					$_POST['item_price'],
					$_POST['item_tag'],
					$_POST['menu_id'],
					$_SESSION['client']['client_id'],
				);
				$dbh = $db->prepare("INSERT INTO menuitems (mitem_title, mitem_description, mitem_price, mitem_tags, mitem_menu, mitem_client) VALUES (?, ?, ?, ?, ?, ?)");
				$dbh->execute($data);
				header('location: ?action=menu');
			}
			catch (PDOException $e)
			{
				array_push($errors, "There was an error updating the menu database. ".$e->getMessage());
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
		<div class="page-header"><h1>Add Menu Item</h1></div>
		<?php
		if(!empty($errors))
		{
			foreach($errors as $err)
			{
				echo "<div class='alert alert-error'>{$err}<div style='clear: both;'></div></div>";
			}
		}		
		?>
		
		<form action="?action=menu/additem" method="post">
			<input type="hidden" name="do" value="addmenuitem" />
			<input type="hidden" name="menu_id" value="<?php echo $_REQUEST['menu_id']; ?>" />
			
			<div class="row-fluid">
				<div class="span6">
					<label>Item Title</label>
					<input type="text" name="item_title" class="input-large span11" placeholder="Ceaser Salad" />
				</div>
				<div class="span6">
					<label>Item Description</label>
					<input type="text" name="item_description" class="input-large span11" placeholder="A bed of romaine tossed in a sweet.." />
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<label>Item Price</label>
					<div class="input-prepend">
						<span class="add-on">$</span>
						<input type="text" id="prependedInput" name="item_price" class="input-large span4" placeholder="7.99" />
					</div>
				</div>
				<div class="span6">
					<label>Item Tag</label>
					<input type="text" name="item_tag" class="input-large span11" placeholder="Healty" />
					<span class="help-block">An item tag shows up next to an item in the menu</span>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<button type="submit" class="btn btn-large btn-primary">Add Menu Item</button>
				</div>
			</div>
			
			
		</form>
		
	</div>
</div>