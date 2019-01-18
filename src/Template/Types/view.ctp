<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Type $type
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
	</style>
</head>

<div class="locations form large-9 medium-8 columns content">
  <?= $this->Form->create($type) ?>
  <fieldset>
    <legend><?= __('Consultar tipo de activo') ?></legend>
    <br>
	
    <div>
        <label> Nombre: </label>
        <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" value="' . htmlspecialchars($type->name) . '">'; ?> 
    </div>
      
    <br>
	
	<div>
      <label> Descripción: </label>
      <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" value="' . htmlspecialchars($type->description) . '">'; ?> 
    </div> <br>

  </fieldset>

</div>

<?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>

<?= $this->Form->postLink(__('Eliminar'), ['action' => 'delete', $type->type_id], ['class' => 'btn btn-primary', 'confirm' => __('Seguro que desea eliminar el tipo de activo # {0}?', $type->type_id)]) ?>

<?= $this->Html->link(__('Editar'), ['action' => 'edit', $type->type_id], ['class' => 'btn btn-primary']) ?>
    
