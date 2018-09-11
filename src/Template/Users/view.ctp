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
    <legend><?= __('Consultar Usuario') ?></legend>
    <br>
      <div class="form-control sameLine" >
        <div class="row">
          <label> <b>Nombre:</b><b style="color:red;"></b> </label>
          <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" value="' . htmlspecialchars($user->nombre) . '">'; ?>
        </div>

        <div class="row">
          <label> <b>Primer Apellido:</b><b style="color:red;"></b> </label>
          <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" value="' . htmlspecialchars($user->apellido1) . '">'; ?>
        </div>

        <div class="row">
          <label> <b>Segundo Apellido:</b><b style="color:red;"></b> </label>
          <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" value="' . htmlspecialchars($user->apellido2) . '">'; ?>
        </div>
      </div>
      <br>

      <div class="form-control sameLine" >
        <div class="row">
          <label> <b>Cédula:</b><b style="color:red;"></b> </label>
          <?php echo '<input type="text" class="form-control col-sm-7" readonly="readonly" value="' . htmlspecialchars($user->id) . '">'; ?>
        </div>

        <div class="row">
          <label> <b>Rol:</b><b style="color:red;"></b> </label>
          <?php echo '<input type="text" class="form-control col-sm-7" readonly="readonly" value="' . htmlspecialchars($user->id_rol) . '">'; ?>
        </div>

        <div class="row">
          <label> <b>Estado:</b><b style="color:red;"></b> </label>
          <?php echo '<input type="text" class="form-control col-sm-7" readonly="readonly" value="' . htmlspecialchars($user->account_status) . '">'; ?>
        </div>

      </div>
      <br>

      <label> <b>Correo:</b><b style="color:red;"></b> </label>
      <?php echo '<input type="text" class="form-control col-sm-7" readonly="readonly" value="' . htmlspecialchars($user->correo) . '">'; ?>
      <br>

      <div class="form-control sameLine" >
        <div class="row">
          <label> <b>Usuario:</b><b style="color:red;"></b> </label>
          <?php echo '<input type="text" class="form-control col-sm-7" readonly="readonly" value="' . htmlspecialchars($user->username) . '">'; ?>
        </div>

      </div>
      <br>
  </fieldset>
</div>
<br>

  <?= $this->Html->link(__('Cancelar'), ['controller' => 'Users', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
  <?= $this->Html->link(__('Modificar'), ['controller' => 'Users', 'action' => 'edit', $user->id], ['class' => 'btn btn-primary']) ?>
  <?= $this->Form->postLink('Eliminar', ['action' => 'delete', $user->id],  ['escape'=> false, 'class' => 'btn btn-primary' ,'confirm' => __('¿Está seguro que desea eliminar este usuario? # {0}?', $user->id)]) ?>

<?= $this->Form->end(); ?>

</body>
