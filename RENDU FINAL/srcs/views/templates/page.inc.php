<!DOCTYPE html>
<html lang="en">
  <head>
		<meta charset="utf-8">
		<title>Sondages</title>
		<link rel="stylesheet" type="text/css" href="bootstrap.min.css" />
</head>

<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
            <center><font color="#CECECE"> Projet PHP r&eacute;alis&eacute; par PAPASSIAN - BETRANCOURT - LAJEUNESSE - LOPEZ - DALBERTO </font></center>
				<?php $this->displaySearchForm(); ?>
				<?php
					if ($this->login===null) $this->displayLoginForm();
					else $this->displayLogoutForm();
				?>
			</div>
		</div>
	</div>

<?php
	$this->displayBody();
?>

</body>
</html>
