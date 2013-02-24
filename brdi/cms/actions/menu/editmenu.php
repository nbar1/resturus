<?php
$errors = array();
$success = array();

if(!isset($_REQUEST['menu_id'])) header('location: ?action=menu');

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

if(!isMyMenu($_REQUEST['menu_id']))
{
	header('location: ?action=menu');
}

if(isset($_POST['do']))
{
	if($_POST['do'] == 'editmenu')
	{
		if(!isMyMenu($_POST['menu_id'])) array_push($errors, "Invalid Menu ID.");
		if(!$_POST['menu_title']) array_push($errors, "You must enter a menu title.");
		
		if(empty($errors))
		{
			try
			{
				if(!$_POST['menu_subtitle']) $_POST['menu_subtitle'] == null;

				$data = array(
					$_POST['menu_title'],
					$_POST['menu_subtitle'],
					$_REQUEST['menu_id'],
					$_SESSION['client']['client_id'],
				);
				$dbh = $db->prepare("UPDATE menus SET menu_title=?, menu_subtitle=? WHERE menu_id=? AND menu_client=?");
				$dbh->execute($data);
				
				// update menu items
				if(is_array($_GET['menuitem']))
				{
					foreach($_GET['menuitem'] as $pos=>$m_id)
					{
						$pos++;
						$data = array($pos, $m_id, $_REQUEST['menu_id'], $_SESSION['client']['client_id']);
						$dbh = $db->prepare("UPDATE menuitems SET mitem_position=? WHERE mitem_id=? AND mitem_menu=? AND mitem_client=?");
						$dbh->execute($data);
					}
				}
				array_push($success, "Menu successfully updated");
			}
			catch (PDOException $e)
			{
				array_push($errors, "There was an error updating the menu database. ".$e->getMessage());
			}
		}
	}
}
$data = array($_SESSION['client']['client_id'], $_REQUEST['menu_id'], 1);
$dbh = $db->prepare("SELECT * FROM menus WHERE menu_client=? AND menu_id=? AND menu_active=? ORDER BY menu_position ASC");
$dbh->execute($data);
$dbh->setFetchMode(PDO::FETCH_ASSOC);
$menu_info = $dbh->fetch();
?>
<div id="content" class="row-fluid">
	<div class="span3 well nav-fixed" style="padding: 8px 0;">
		<?php include ('actions/includes/menu.php'); ?>
	</div>
	<div class="span9 well">
		<div class="page-header"><h1>Edit Menu</h1></div>
		<?php
		if(!empty($success))
		{
			foreach($success as $suc)
			{
				echo "<div class='alert alert-success'>{$suc}<div style='clear: both;'></div></div>";
			}
		}		
		?>
		<?php
		if(!empty($errors))
		{
			foreach($errors as $err)
			{
				echo "<div class='alert alert-error'>{$err}<div style='clear: both;'></div></div>";
			}
		}		
		?>
		<div class='alert alert-warning hidden-phone'>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>Drag & Drop Menu Items</h4>
			You can drag and drop your menu items to reorganize them. These changes take effect immediately. Please note, that when in mobile view, the items will collapse in a left-to-right fashion. 
		</div>
		<form id="form_editmenu" method="post">
			<input type="hidden" name="do" value="editmenu" />
			<input type="hidden" name="menu_id" value="<?php echo $_REQUEST['menu_id']; ?>" />
			<div class="row-fluid">
				<div class="span6">
					<label>Menu Title</label>
					<input type="text" name="menu_title" class="input-large span11" value="<?php echo $menu_info['menu_title']; ?>" />
				</div>
				<div class="span6">
					<label>Menu Subtitle</label>
					<input type="text" name="menu_subtitle" class="input-large span11" value="<?php echo $menu_info['menu_subtitle']; ?>" />
				</div>
			</div>
			<ul id="sortable_menuitems">
			<?php
			if(isMyMenu($_REQUEST['menu_id']))
			{
				$data = array($_SESSION['client']['client_id'], $_REQUEST['menu_id'], 1);
				$dbh = $db->prepare("SELECT * FROM menuitems WHERE mitem_client=? AND mitem_menu=? AND mitem_active=? ORDER BY mitem_position");
				$dbh->execute($data);
				$menu_items = $dbh->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($menu_items as $menu_item)
				{
					?>
					<li class="well span6 mitem" id="menuitem_<?php echo $menu_item['mitem_id']; ?>">
						<div class="mitem_title"><?php echo $menu_item['mitem_title']; ?></div>
						<div class="mitem_description"><?php echo $menu_item['mitem_description']; ?></div>
						<div class="mitem_price">$<?php echo $menu_item['mitem_price']; ?></div>
						<div class="mitem_tags"><span class="label label-info"><?php echo $menu_item['mitem_tags']; ?></span></div>
						<div class="btn-group opt_edit">
							<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
								Options
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="#">Edit</a></li>
								<li><a href="#">Delete</a></li>
							</ul>
						</div>
					</li>
					<?php
				}
				
			}
			?>
			</ul>
			<div class="row-fluid" style='clear: both;'>
				<div class="span12">
					<button type="submit" class="btn btn-large btn-primary">Update Menu</button>&nbsp;&nbsp;
					<a class="btn btn-large" href='?action=menu/additem&menu_id=<?php echo $_REQUEST['menu_id']; ?>'>Add Menu Item</a>
				</div>
			</div>
		</form>		
	</div>
</div>
<script type="text/javascript"> 
// When the document is ready set up our sortable with it's inherant function(s) 
$(document).ready(function() {
	$("#sortable_menuitems").sortable({
      	distance: 20
      });
	
	$("#form_editmenu").submit(function(event){
		var order = $('#sortable_menuitems').sortable('serialize');
		$(this).attr('action', '?action=menu/editmenu&'+order);
		return true;
	})
}); 
</script>