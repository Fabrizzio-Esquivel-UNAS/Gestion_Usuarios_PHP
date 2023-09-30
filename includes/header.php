<div class="brand clearfix">
	<h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px"><i class="fa fa-user"></i>&nbsp; USUARIO CON DNI <?php echo htmlentities($_SESSION['dni']);?></h4>
	<span class="menu-btn"><i class="fa fa-bars"></i></span>
	<ul class="ts-profile-nav">
		<li class="ts-account">
			<a href="#"><img src="images/<?php 
				if (isset($_SESSION['privileges']) && $_SESSION['privileges']==1){
					echo htmlentities("admin.png");
				}else{
					echo htmlentities("user.png");
				}
			?>" class="ts-avatar hidden-side" alt=""> <?php 
				if (isset($_SESSION['privileges']) && $_SESSION['privileges']==1){
					echo "Admin";
				}else{
					echo "Usuario";
				}
			?> <i class="fa fa-angle-down hidden-side"></i></a>
			<ul>
			<li><a href="user.php?edit=<?php echo htmlentities($_SESSION['dni']);?>">Editar perfil</a></li>
			<li><a href="logout.php">Cerrar sesión</a></li>
			</ul>
		</li>
	</ul>
</div>