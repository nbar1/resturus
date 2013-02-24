<?php
$errors = array();

$data = array($_SESSION['client']['client_id'], 1);
$dbh = $db->prepare("SELECT * FROM menus WHERE menu_client=? AND menu_active=? ORDER BY menu_position ASC");
$dbh->execute($data);
$dbh->setFetchMode(PDO::FETCH_ASSOC);
$menus = $dbh->fetchAll();

if($menus === false)
{
	array_push($errors, "You have no menus, would you like to add one? <a href='?action=menu/addmenu' class='btn btn-info'>Add Menu</a>");
}
?>
<div id="content" class="row-fluid">
	<div class="span3 well" style="padding: 8px 0;">
		<?php include ('actions/includes/menu.php'); ?>
	</div>
	<div class="span9 well page_menu">
		<div class="page-header"><h1>Menus</h1></div>
		<?php
		if(!empty($errors))
		{
			foreach($errors as $err)
			{
				echo "<div class='alert alert-info alert-option'>{$err}<div style='clear: both;'></div></div>";
			}
		}
		?>
		<div class='alert alert-warning'>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>Note</h4>
			To have a menu repositioned, added or removed from your menu page on <?php echo $_SESSION['client']['client_portal']; ?>, you must submit a <a href='?action=changerequest'>change request</a>. Menu details and menu items are updated immediately.
		</div>
		<?php
		if(is_array($menus))
		{
			foreach($menus as $menu)
			{
				$data = array($_SESSION['client']['client_id'], $menu['menu_id'], 1);
				$dbh = $db->prepare("SELECT count(*) FROM menuitems WHERE mitem_client=? AND mitem_menu=? AND mitem_active=?");
				$dbh->execute($data);
				$menu_items = $dbh->fetchColumn();
				
				echo "<div class='span5 well menu_selector'>";
				echo "<div class='menu_menutitle'><h4>".$menu['menu_title']."</h4></div>";
				//echo "<div class='menu_menusubtitle'>".$menu['menu_subtitle']."</div>";
				echo "<div class='menu_menuitems'>".$menu_items." menu items</div>";
				
				echo "<div class='row-fluid'>";
				echo "<a href='?action=menu/editmenu&menu_id=".$menu['menu_id']."' class='span6 btn btn-primary'>Edit Menu</a>";
				echo "<a href='?action=menu/additem&menu_id=".$menu['menu_id']."' class='span6 btn'>Add Item</a>";
				echo "</div>";
				
				echo "</div>";
				
			}
		}
		
		?>
		
	</div>
</div>