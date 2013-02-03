<ul class="nav nav-list">

  <li class="nav-header">Account</li>
  <li <?php if($action_base=="dashboard") echo "class=\"active\""; ?>><a href="?action=dashboard">Dashboard</a></li>
  <li <?php if($action_base=="subscription") echo "class=\"active\""; ?>><a href="?action=subscription">Subscription</a></li>
  <li <?php if($action_base=="accountsettings") echo "class=\"active\""; ?>><a href="?action=accountsettings">Account Settings</a></li>
  
    
  <li class="nav-header"><?php echo $_SESSION['client']['client_name']; ?></li>
  <li <?php if($action_base=="socialmedia") echo "class=\"active\""; ?>><a href="?action=socialmedia">Social Media</a></li>
  <li <?php if($action_base=="locations") echo "class=\"active\""; ?>><a href="?action=locations">Locations</a></li>
  <li <?php if($action_base=="menu") echo "class=\"active\""; ?>><a href="?action=menu">Menus</a></li>
  
  <li class="nav-header">Requests</li>
  <li <?php if($action_base=="changerequest") echo "class=\"active\""; ?>><a href="?action=changerequest">Change Request</a></li>
  
  
</ul>