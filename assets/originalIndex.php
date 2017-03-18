
<?php	if($_GET['password'] != "" && $_GET['file'] != "") {
		$command = "/home/readFile ".$_GET['password']." insurances/".$_GET['file'];
	}
?>
<html>
	<head>
		<style>
			body {margin: 0;}
			.customerAccess {animation-name: customerAnimation; animation-duration: 2s; position: relative; width: 50%; height: 50%; left: 25%; top: 0px;}
			.home {animation-name: homeanimation; animation-duration: 2s; position: relative; left:45%; top:0px; width: 200px; height: 100px;}
			input[type=text], select, input[type=password] {width: 100%; padding: 12px, 20px; margin: 8px 0; display: inline-block; border: 1px; solid #ccc; border-radius: 4px; box-sizing: border-box;}
			@keyframes customerAnimation {0% {left:-50%; top: 0px;} 100% {left:25%; top:0px;}}
			@keyframes homeanimation {0% {left:0px;top:0px;} 100% {left:45%;top:0px;}}
			input[type=text]:focus, input[type=password]:focus {background-color: #DDDEEE;}
			@keyframes example {0% {background-color: white;} 100% {background-color:#CCCCCC}}
			table {border-collapse: collapse;}
			td, th {border: 0px solid #dddddd;}
			.button:hover {background-color: #888888;}
			.button {background-color: #aaaaaa; border: none; color: white; padding: 5px 32px; text-align: center; text-decoration: none; display: inline-block; margin: 4px 2px; cursor: pointer; border-radius: 5px; width: 100%;}
			a:link {color: blue;}
			a:visited {color: white;}
			a:hover {color:red;}
			.overlay {height: 100%; width: 0; position: fixed; z-index: 1; top: 0; left: 0; background-color: rgb(0,0,0); background-color: rgba(0,0,0,0.9); overflow-x: hidden; transition: 0.5s;}
			.overlay-content {position: relative; top: 25%; width: 100%; text-align: center; margin-top: 30px;}
			.overlay a {padding: 8px; text-decoration: none; font-size: 36px; color: #818181; display: block; transition: 0.3s;}
			.overlay a:hover, overlay a:focus {color: #f1f1f1;}
			.overlay .closebtn {position: absolute; top: 20px; right: 45px;}
			@media screen and (max-height: 450px) {.overlay a {font-size: 20px} .overlay .closebtn {font-size: 40px; top: 15px; right: 35px;}
		</style>
		<title>Mountain Security</title>
		<script>
			function change(i) {
				var newContent = "";
				if(i == 1) {
					document.getElementById("co").style="animation-name: example; animation-duration: 2s; background-color: #CCCCCC";
					newContent = "<div class=\"home\"><h2>Welcome!</h2>Enjoy your visit!</div>";
				}
				if(i == 2) {
					newContent = "<div class=\"customerAccess\"><h2>Only the best!</h2><ul><li>Trust!</li><li>Performance!</li><li>Service!</li><li>Reliability!</li></ul>We are the #1 climbing insurance seller!</div>";
				}
				if(i == 3) {
					newContent = "<div class=\"customerAccess\"><h2>Just the policy you need!</h1>We provide policies that fit everyones needs.<br />Just give us a call, so we can discuss your needs.</div>";
				}
				if(i == 4) {
					newContent = "<div class=\"customerAccess\"><table align=center border=1 cellpadding=10px><tr><td>Please provide your cutsomer ID and password to access your insurance policy.</td></tr><tr><td><form action=\"originalIndex.php\" method=\"get\"><table width=100%><tr><td width=20%>Password</td><td><input type=\"password\" name=\"password\" maxlength=20></td></tr><tr><td>Customer ID</td><td><input type=\"text\" name=\"file\" maxlength=5></td></tr></table><p><input class=\"button\" type=\"button\" value=\"submit\" onclick=\"document.forms[0].submit();\"></form></td></tr></table></div>";
				}
				document.getElementById("content").innerHTML = newContent;
			}
		</script>
	</head>

	<body style="background-color: #BBBBBB">
			<div id="myNav" class="overlay">
			<div class="overlay-content" style="color: white;"><?php systehpm($command); ?></div>
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			</div>

			<script>
			function openNav() {
				document.getElementById("myNav").style.width="100%";
			}
			function closeNav() {
				document.getElementById("myNav").style.width = "0%";
			}
			<?php
				if($_GET['password'] != "" && $_GET['file'] != "") {
					echo "openNav();";

				}
			?>
			</script>

			<table align=center border=0 width=100% height=100% style="background-color: #FFFFFF;">
			<tr height=15%><td colspan=3 align=center style="background-color: #95C7EA;"><h1>MountSec - Your Climbing Insurance Seller</h1></td></tr>
			<tr height=5%><td style="background-color: #CCCCCC">
			<table width=100%><tr>
			<td align=center><input class="button" type="button" onclick="change(1)" value="Home"></td>
			<td align=center><input class="button" type="button" onclick="change(2)" value="About Us"></td>
			<td align=center><input class="button" type="button" onclick="change(3)" value="Services"></td>
			<td align=center><input class="button" type="button" onclick="change(4)" value="Customer Access"></td>
			</tr></table>
			</td></tr>
			<tr><td id="co" style="background-color: #CCCCCC"><div id="content" style="color:white;"><div class="home"><h2>Welcome!</h2>Enjoy your visit!</div></div></td></tr>
			<tr height=5%><td colspan=3 style="background-color: #95C7EA"><div align=center style="font-size: 12px; color: gray;">MountSec Cmp 2016 - <a href=#>Legal</a></div></td></tr>
			</table>
	</body>

</html>

php
