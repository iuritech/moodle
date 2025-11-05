<?php
// Página Index

session_start(); 

if (isset($_SESSION["sessao"])) {
    header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
		<link rel="shortcut icon" type="image/jpg" href="images/fav_icon.png"/>
        
        <title>ApoioUTC - Login</title>

        <!-- Bootstrap -->
        <link href="css/styles.css" rel="stylesheet" />

    </head>
<body style="margin: 0; padding: 0; background: url(images/fundo_login.jpg); background-size: cover; background-position: center;">
	<div id="cover-spin"></div> 
	<div class="loginbox">
	<img src="images/logo_login.png" class="avatar">
		<h1 style="margin:0; padding: 0 0 20px; text-align: center; font-size: 22px;">Login</h1>
		<form id="formLogin" action="processamento/login.php" method="post">
			<p>Utilizador</p>
			<input type="text" name="login" id="loginForm" placeholder="Utilizador...">
			<p>Password</p>
			<input type="password" name="password" id="passwordForm" placeholder="Password...">
			<input type="submit" name="" value="Login" form="formLogin" onclick="mostrarLoading()">
		</form>
    </div>
</body>
<script language="javascript">
function mostrarLoading(){
$('#cover-spin').show();
setTimeout(function(){;
			$('#cover-spin').hide();
			}
			,500);
}
</script>
</html>

