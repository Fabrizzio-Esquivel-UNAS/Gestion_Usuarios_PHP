<?php
session_start();
error_reporting(0);
include('includes/config.php');

$titulo = "Añadir Usuario";
$dni = $_GET['edit'];

if(isset($_POST['submit'])){
	if($dni===NULL){
		$sql= "INSERT INTO usuarios VALUES (:v1, :v2, :v0,:v3, :v4, :v5, DEFAULT)";
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v0', $_POST['dni'], PDO::PARAM_STR);
	}else{
		$sql= "UPDATE usuarios SET nombres=(:v1), apellidos=(:v2), correo=(:v3), telefono=(:v4), clave=(:v5) WHERE dni=(:v0)";		
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v0', $dni, PDO::PARAM_STR);
	}
	$query-> bindParam(':v1', $_POST['nombres'], PDO::PARAM_STR);
	$query-> bindParam(':v2', $_POST['apellidos'], PDO::PARAM_STR);
	$query-> bindParam(':v3', $_POST['correo'], PDO::PARAM_STR);
	$query-> bindParam(':v4', $_POST['telefono'], PDO::PARAM_STR);
	$query-> bindParam(':v5', $_POST['clave'], PDO::PARAM_STR);
	try {
		$query-> execute();
		$msg = ($dni===NULL? "Usuario Añadido con Éxito" : "Usuario Editado con Éxito");
		header("Location: user-list.php?msg=".urlencode($msg));
		exit;
	} catch (PDOException $e) {
		$error = $e->getMessage();
	}
}
if(isset($dni)){
	$sql = "SELECT * FROM usuarios WHERE dni=:v1";
	$query = $dbh -> prepare($sql);
	$query->bindParam(':v1',$dni,PDO::PARAM_INT);
	$query->execute();
	$result=$query->fetch(PDO::FETCH_OBJ);	
	$titulo="Editar Usuario";
}	
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	<title><?php echo $titulo;?></title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">

	<script type= "text/javascript" src="../vendor/countries.js"></script>
	<style>
		.errorWrap {
			padding: 10px;
			margin: 0 0 20px 0;
			background: #dd3d36;
			color:#fff;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
			box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
		}
		.succWrap{
			padding: 10px;
			margin: 0 0 20px 0;
			background: #5cb85c;
			color:#fff;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
			box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
		}
	</style>
</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h3 class="page-title"><?php echo $titulo; if (isset($result)) echo ": ".htmlentities(($result->nombres))." ".htmlentities(($result->apellidos));?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Información del usuario</div>
									<?php if($error){?>
										<div class="errorWrap"><strong>ERROR: </strong><?php echo htmlentities($error); ?> </div>
									<?php } else if($msg){?>
										<div class="succWrap" id="msgshow"> <?php echo htmlentities($msg);?> </div>
									<?php }?>
									<div class="panel-body"><form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform" id="imgform">
										<div class="form-group">
											<label class="col-sm-2 control-label">Nombre(s)<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="text" name="nombres" class="form-control" required value="<?php echo htmlentities($result->nombres);?>">
											</div>
											<label class="col-sm-2 control-label">DNI<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="tel" name="dni" class="form-control" <?php if (isset($dni)) echo htmlentities('disabled'); ?> value="<?php echo htmlentities($result->dni);?>">
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-2 control-label">Apellidos(s)<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="text" name="apellidos" class="form-control" required value="<?php echo htmlentities($result->apellidos);?>">
											</div>
											<label class="col-sm-2 control-label">Correo<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="text" name="correo" class="form-control" required value="<?php echo htmlentities($result->correo);?>">
											</div>
										</div>

										<div class="form-group">
										<label class="col-sm-2 control-label">Clave<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="password" name="clave" class="form-control" required value="<?php echo htmlentities($result->clave);?>">
											</div>
											<label class="col-sm-2 control-label">Telefono<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="tel" name="telefono" class="form-control" required value="<?php echo htmlentities($result->telefono);?>">
											</div>
										</div>

										<div class="form-group">
											<div class="col-sm-8 col-sm-offset-2">
												<button class="btn btn-primary" name="submit" type="submit" value="<?php echo htmlentities($dni);?>">Aceptar</button>
											</div>
										</div>
									</form></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {          
		setTimeout(function() {
			$('.succWrap').slideUp("slow");
		}, 3000);
		});
	</script>
</body>
</html>