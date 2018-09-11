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
		
		.sameLine{
          display: flex; 
          justify-content: space-between; 
          border-color: transparent;
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
		
	</style>

</head>

<div class="locations form large-9 medium-8 columns content">
  <?= $this->Form->create($type) ?>
  <fieldset>
    <legend><?= __('Insertar tipo de activo') ?></legend>
    <br>

    <div class="form-control sameLine" >
	
      <div class="row">
          <label> <b>Nombre:</b><b style="color:red;">*</b> </label>
		  <?php echo $this->Form->imput('name', ['class'=>'form-control col-lg-8']); ?> 
      </div>
        
    </div> <br>
	
	<div>
      <label> Descripción: </label>
      <?php echo $this->Form->textarea('description', ['class'=>'form-control col-md-8']); ?>
    </div> <br>

  </fieldset>

</div> 


<?= $this->Html->link(__('Cancelar'), ['controller' => 'Types', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>

<?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary']) ?>
    
