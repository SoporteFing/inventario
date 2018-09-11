<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<head>
  <style>
        .btn-primary {
          color: #fff;
          background-color: #0099FF;
          border-color: #0099FF;
          float: right;
          margin-left: 10px;
        }

        .btn-default {
          color: #000;
          background-color: #7DC7EF;
          border-top-right-radius: 5px;
          border-bottom-right-radius: 5px;
        }

        .form .error-message{
          color: red;

        }


  </style>

  <script type='text/javascript' src='../js/plugins/jQuery-Mask-Plugin-master/src/jquery.mask.js'></script>

</head>

<body>
<div class="users form large-9 medium-8 columns content">
  <?= $this->Form->create($user) ?>
  <fieldset>
    <legend><?= __('Insertar Usuario') ?></legend>
    <br>

        <div class="row">
          <div class="col-sm-4">
            <label required="required"> <b>Nombre:</b><b style="color:red;">*</b> </label>
            <?php 
                echo $this->Form->control('nombre', 
                    [
                        'templates' => [
                        'inputContainer' => '{{content}}',
                        'inputContainerError' => '{{content}} {{error}}'
                      ], 
                      'label'=>['text'=>''],
                      "required"=>"required",
                      'class'=>'form-control col-sm-6 col-md-10 col-lg-10',
                    ]);
            ?>
          </div>

          <div class="col-sm-4">
            <label required="required"> <b>Primer Apellido:</b><b style="color:red;">*</b> </label>
            <?php 
                echo $this->Form->control('apellido1', 
                    [
                        'templates' => [
                        'inputContainer' => '{{content}}',
                        'inputContainerError' => '{{content}} {{error}}'
                      ], 
                      'label'=>['text'=>''],
                      "required"=>"required",
                      'class'=>'form-control col-sm-6 col-md-10 col-lg-10',
                    ]);
            ?>
          </div>

          <div class="col-sm-4">
            <label required="required"> <b>Segundo Apellido:</b><b style="color:red;">*</b> </label>
            <?php 
                echo $this->Form->control('apellido2', 
                    [
                        'templates' => [
                        'inputContainer' => '{{content}}',
                        'inputContainerError' => '{{content}} {{error}}'
                      ], 
                      'label'=>['text'=>''],
                      "required"=>"required",
                      'class'=>'form-control col-sm-6 col-md-10 col-lg-10',
                    ]);
            ?>
          </div>
        </div>

      <br>
<br>

        <div class="row">

          <div class="col-sm-4">
            <label> <b>Tipo de Identificación:</b><b style="color:red;">*</b> </label>
            <?php echo $this->Form->select('type_id', array('0' => 'Cédula', '1' => 'Otro Documento'), array('empty' => '-- Seleccione Tipo --', 'class' => 'form-control col-md-10', 'id' => 'type_id')); ?>
          </div>

          <div class="col-sm-4">
            <label> <b>Identificación:</b><b style="color:red;">*</b> </label>
            <?php 
                  echo $this->Form->control('id', 
                      [
                          'templates' => [
                          'inputContainer' => '{{content}}',
                          'inputContainerError' => '{{content}} {{error}}'
                        ], 
                        'label'=>['text'=>''],
                        'type'=>'text',
                        'maxlenght' => '15',
                        "required"=>"required",
                        'class'=>'form-control col-sm-6 col-md-10 col-lg-10',
                      ]);
            ?>
          </div>

          <div class="col-sm-4">
            <label> <b>Correo:</b><b style="color:red;">*</b> </label>
            <?php 
                  echo $this->Form->control('correo', 
                      [
                          'templates' => [
                          'inputContainer' => '{{content}}',
                          'inputContainerError' => '{{content}} {{error}}'
                        ], 
                        'label'=>['text'=>''],
                        'placeholder' => 'correo@ejemplo.com',
                        "required"=>"required",
                        'class'=>'form-control col-sm-6 col-md-10 col-lg-11',
                      ]);
            ?>
          </div>

          
        </div>

<br>  
      <br>

 
        <div class="row">
          

          <div class="col-sm-4">
            <label> <b>Usuario:</b><b style="color:red;">*</b> </label>
            <?php 
                  echo $this->Form->control('username', 
                      [
                          'templates' => [
                          'inputContainer' => '{{content}}',
                          'inputContainerError' => '{{content}} {{error}}'
                        ], 
                        'label'=>['text'=>''],
                        "required"=>"required",
                        'class'=>'form-control col-sm-6 col-md-10 col-lg-11',
                      ]);
            ?>
          </div>


          <div class="col-sm-4">
            <label> <b>Rol:</b><b style="color:red;">*</b> </label>
            <?php echo $this->Form->select('id_rol', $roles, array('empty' => '-- Seleccione Rol --', 'class' => 'form-control col-md-10')); ?>
          </div>

          <div class="col-sm-4">
            <label> <b>Estado:</b><b style="color:red;">*</b> </label>
            <?php echo $this->Form->select('account_status', array('1' => 'Activo', '0' => 'Inoperante'), array('empty' => '-- Seleccione Estado --', 'class' => 'form-control col-md-10')); ?>
          </div>

        </div>

      <br>
<br>

        <div class="row">
          <div class="col-sm-6">
            <label> <b>Contraseña:</b><b style="color:red;">*</b> (mínimo 8 caracteres)</label>
            <?php 
                  echo $this->Form->control('password', 
                      [
                          'templates' => [
                          'inputContainer' => '{{content}}',
                          'inputContainerError' => '{{content}} {{error}}'
                        ], 
                        'label'=>['text'=>''],
                        'type'=>'password',
                        "required"=>"required",
                        'class'=>'form-control col-md-11',
                      ]);
            ?>
          </div>

          <div class="col-sm-6">
            <label> <b>Confirmar Contraseña:</b><b style="color:red;">*</b> </label>
            <?php 
                  echo $this->Form->control('password2', 
                      [
                          'templates' => [
                          'inputContainer' => '{{content}}',
                          'inputContainerError' => '{{content}} {{error}}'
                        ], 
                        'label'=>['text'=>''],
                        'type'=>'password',
                        "required"=>"required",
                        'class'=>'form-control col-md-11',
                      ]);
            ?>
          </div>
        </div>

      <br>
  </fieldset>
</div>
<br>

  <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
  <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary', 'id' => 'send']) ?>

<?= $this->Form->end(); ?>



<script>

$(document).ready(function() {
  if($("#type_id").val() == 0){
      $("#id").attr("placeholder", "1-0123-0123");
      $("#id").mask("0-0000-0000");
  }
});

$("#type_id").change(function () {
    if(this.value == 0){
       $("#id").attr("placeholder", "1-0123-0123");
       $("#id").mask("0-0000-0000");
    }else{
       $("#id").attr("placeholder", "");
       $("#id").unmask();
    }
    
  });

$("#send").on('click',function(){
    $("#id").unmask();
});

</script>

</body>
