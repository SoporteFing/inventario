<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Model $model
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
   
	<script type="text/javascript">
		function mostrarReferencia(){
		if (document.agregarModelo.nuevaMarca.checked == true) {
			document.getElementById('new_Brand').value='';
			document.getElementById('newBrandField').style.display='block';
			document.getElementById('id_brand').disabled=true;
			document.getElementById('id_brand').value='';
		} else {
			document.getElementById('newBrandField').style.display='none';
			document.getElementById('id_brand').disabled=false;
			document.getElementById('new_Brand').value='';
		}
	}
	-->
	</script>
</head>

<div class="locations form large-9 medium-8 columns content">
    <?= $this->Form->create($model, ['type' => 'file', 'name' => 'agregarModelo']) ?>

	<fieldset>
    <legend><?= __('Insertar modelo') ?></legend>
    <br>

		<div class='row'>
			<div class="col-md-4 col-xs-12 col-lg-4 col-sm-1 ">
				<?php echo $this->Form->control('name', array('label'=>'Nombre', 'class' => 'form-control')); ?>
			</div>
			<div class="col-md-4 col-xs-12 col-lg-3 col-sm-12 ">   </div>
			<div class="col-md-4 col-xs-12 col-lg-4 col-sm-12">
			<?php echo $this->Form->control('id_type', array('options' => $types,'label'=>'Tipo', 'empty' => '-- Seleccione tipo --', 'class' => 'form-control')); ?>
			</div>
		</div>

		<br>

		<div class='row'>
			<div class="col-md-4 col-xs-12 col-lg-4 col-sm-12">
				<?php echo $this->Form->control('id_brand', array('options' => $brands,'label'=>'Marca', 'id' => 'id_brand', 'empty' => '-- Seleccione marca --', 'class' => 'form-control')); ?>
			</div>
			
			<div class="col-md-4 col-xs-12 col-lg-3 col-sm-12">
				<br> <br>
				<input type="checkbox" name="nuevaMarca" id="newBrand" value="nueva_marca" onclick="mostrarReferencia();" /> Agregar nueva marca
			</div>
			
			<div id="newBrandField" style="display:none;" class="col-md-4 col-xs-12 col-lg-4 col-sm-1 ">
				<?php echo $this->Form->control('new_Brand', array('label'=>'Nueva marca', 'id' => 'new_Brand', 'class' => 'form-control')); ?>
			</div>
			<br>
		</div>

		<br>

	</fieldset>

</div>

<div class="col-12 text-right">

	<?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
	<?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary']) ?>

</div>