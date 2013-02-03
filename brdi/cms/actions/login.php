<?php
$errors = array();

if(isset($_POST['do']))
{
	switch($_POST['do'])
	{
		case 'login':
			
			if(!$_POST['clientid']) array_push($errors, "You must enter your Client ID");
			if(!$_POST['clientpassword']) array_push($errors, "You must enter your password");
			
			if(empty($errors))
			{
				$clientid = $_POST['clientid'];
				$clientpassword = md5($_POST['clientpassword']);
	
				$data = array($clientid, $clientpassword, 1);
				$dbh = $db->prepare("SELECT * FROM clients WHERE client_token=? AND client_password=? AND client_active=? LIMIT 1");
				$dbh->execute($data);
				$dbh->setFetchMode(PDO::FETCH_ASSOC);
				$client = $dbh->fetch();
				
				if($client === false)
				{
					array_push($errors, "Invalid Client ID / Password");
				}
				else {
					$_SESSION['client'] = $client;
					header('location: ?action=dashboard');
				}
			}
		break;
	}
}
?>
<div id="content" class="row-fluid">
	<div class="span4 offset4">
		<form action="/brdi/cms/?action=login" method="POST">
			<input type="hidden" name="do" value="login">
			<div class="login_wrapper well">
				<legend>Login</legend>
				<?php
				if(!empty($errors))
				{
					foreach($errors as $err)
					{
						echo "<div class='alert alert-error'>{$err}</div>";
					}
				}
				?>
				<label>Client ID</label>
				<input type="text" name="clientid" />
				<label>Password</label>
				<input type="password" name="clientpassword" />
				<button type="submit" class="btn btn-large">Login</button>
			</div>
		</form>
	</div>
</div>