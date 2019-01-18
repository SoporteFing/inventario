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

        label {
          text-align:left;
          margin-right: 10px;

        }

        .sameLine{
          display: flex;
          justify-content: space-between;
          border-color: transparent;
        }

  </style>
</head>

<body>
<div class="users form large-9 medium-8 columns content">
  <?= $this->Form->create($user) ?>
  <fieldset>
    <legend><?= __('Modificar Usuario') ?></legend>
    <br>
      <div class="form-control sameLine" >
        <div class="row">
          <label> <b>Nombre:</b><b style="color:red;"></b> </label>
          <?php echo $this->Form->imput('nombre', ['class'=>'form-control col-md-6', 'disabled']); ?>
        </div>

        <div class="row">
          <label> <b>Primer Apellido:</b><b style="color:red;"></b> </label>
          <?php echo $this->Form->imput('apellido1', ['class'=>'form-control col-md-6', 'disabled']); ?>
        </div>

        <div class="row">
          <label> <b>Segundo Apellido:</b><b style="color:red;"></b> </label>
          <?php echo $this->Form->imput('apellido2', ['class'=>'form-control col-md-6', 'disabled']); ?>
        </div>
      </div>
      <br>

      <label> <b>Correo:</b><b style="color:red;"></b> </label>
      <?php echo $this->Form->imput('correo', ['class'=>'form-control col-md-6', 'disabled']); ?>
      <br>

      <div class="form-control sameLine" >
        <div class="row">
          <label> <b>Usuario:</b><b style="color:red;"></b> </label>
          <?php echo $this->Form->imput('username', ['class'=>'form-control col-md-8', 'disabled']); ?>
        </div>

        <div class="row">
          <label> <b>Contraseña:</b><b style="color:red;"></b> </label>
          <?php echo $this->Form->imput('password', ['class'=>'form-control col-md-6', 'type' => 'password', 'value' => '']); ?>
        </div>

        <div class="row">
          <label> <b>Confirmar Contraseña:</b><b style="color:red;"></b> </label>
          <?php echo $this->Form->imput('password2', ['class'=>'form-control col-md-6', 'type' => 'password', 'value' => '']); ?>
        </div>
      </div>
      <br>
  </fieldset>
</div>
<br>

  <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
  <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary', 'action' => 'profile']) ?>

<?= $this->Form->end(); ?>
