
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Ingreso al sistema</title>
  <!-- Bootstrap core CSS-->

 <?= $this->Html->meta('icon') ?>
  <?= $this->Html->css('cake.css') ?>


 <?= $this->Html->css([ 'plugins/bootstrap/css/bootstrap.min.css', 'plugins/font-awesome/css/font-awesome.min.css','sb-admin.css']) ?>

 
</head>

<?= $this->Flash->render() ?>
<div class="clearfix"></div>
<body class="bg-dark">
	
 <div class="container">

  <div class="card card-login mx-auto mt-5">
    <div class="card-header">Ingreso al Sistema de Activos</div>
    <div class="card-body">

     
     <div align="center">
       <?= $this->Html->image('acronimo.png', array('style' => 'max-width:100px; margin-top: -7px;'),['alt' => 'Facultad de Ingenieria']);?>
     </div>
   
     
     <!--<form>-->
       <?= $this->Form->create() ?>


       <div class="form-group">
        <!--<label for="usr">Usuario</label>-->
        <?= $this->Form->input('Usuario', array('id' => 'username', 'name' => 'username', 'class' => 'form-control', 'type' => 'username', 
        'aria-describedby' => 'emailHelp', 'placeholder' => 'Digite su usuario')); ?>
        <!--<input class="form-control" id="username" type="username" aria-describedby="emailHelp" placeholder="Digite su usuario">-->
      </div>
      <div class="form-group">

        <!--<label for="psw">Contraseña</label>-->
        <?= $this->Form->input('Contraseña', array('id' => 'password', 'name' => 'password', 'class' => 'form-control', 'type' => 'password',  'placeholder' => 'Contraseña')) ?>
        <!--<input class="form-control" id="password" type="password" placeholder="Contraseña">-->
      </div>
      <div class="form-group">
        <div class="form-check">
          <label class="form-check-label">
            <!--<input class="form-check-input" type="checkbox"> Recordar mi contraseña</label>-->
          </div>
        </div>
        <?= $this->Form->button('Ingresar', array('class' => 'btn btn-primary btn-block')) ?>
        <!--<a class="btn btn-primary btn-block" href="index">Ingresar</a>-->

        <!--</form>-->
        <?= $this->Form->end() ?>
        <div class="text-center">
          <!--<a class="d-block small mt-3" href="register.html">Register an Account</a>-->
          <!--<a class="d-block small" href="forgot-password.html">Olvidé mi contraseña</a>-->
        </div>
      </div>
    </div>
  </div>

</body>

</html>
