<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rol $rol
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
  <?= $this->Form->create($rol) ?>
  <fieldset>
    <legend><?= __('Insertar Rol') ?></legend>
    <br>
    <div class="row">
      <label> <b>Nombre:</b><b style="color:red;">*</b> </label>
      <?php echo $this->Form->imput('nombre', ['class'=>'form-control col-md-6']); ?>
    </div>
  <br>
</fieldset>
</div>
<br>

<?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
<?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary']) ?>

<?= $this->Form->end(); ?>
