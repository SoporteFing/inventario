<?php
/**
 * @var \App\View\AppView $this
 * @var \app\model\entity\loan $loan
 * @var \App\Model\Table\LoansTable $tablaprestamos
 * @var \App\Controller\LoansController $controller
 */
?>

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
		
		.date{
          width:100px;
          margin-left: 10px;
        }
</style> 
 
<div class="form large-9 medium-8 columns content">
<?= $this->Form->create($loan, ['type' => 'file']) ?>
	<fieldset>
        <?php
            if($loan->estado == "En proceso"){
                echo "<legend>Insertar préstamo</legend>";
            }
        ?>
        
				 <?php
					$servername = "163.178.109.13";
					$username = "activos";
					$password = "activos.fing";

					// Create connection
					$base = mysqli_connect($servername, $username, $password);

					$solicitud = 'SELECT users.id, users.nombre, users.apellido1,  users.apellido2, loans.id_responsables FROM users users, loans loans';
					echo $base->$solicitud;
					?> 

		<br>

		<div class="form-control sameLine">
			<div class="row col-lg-5">
				<label> <b>Responsable:</b> </label>
				<?php
					
//					$nombre = $this->Users->get($loan->id_respondables, ['contain' => ['Users']]);
					$tablaprestamos->id2Name('123'); //$users->id;//$loan->id_responsables; ?>
			</div>

			<div class="row">
				<label> <b>Fecha inicio:</b> <p>(D/M/A)</p> </label>
				<?php 
					$fecha = explode('/', $loan->fecha_inicio);
					$fecha = mktime(0, 0, 0, $fecha[0], $fecha[1], $fecha[2]);
					echo date("d-m-Y", $fecha); ?>
			</div>
			
			<div class="row">
				<label> <b>Fecha de devoluci�n:</b> <p>(D/M/A)</p>  </label>
                <?php echo date('d-m-Y'); ?>
			</div>
			
		</div>
	
	</fieldset>
    <br> <br>

    <div class="related">
        <legend><?= __('Activos prestados') ?></legend>

        <!-- tabla que contiene  datos básicos de activos-->
        <table id='assets-borrowed-grid' cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="transfer-h"><?= __('Placa') ?></th>
                    <th class="transfer-h"><?= __('Modelo') ?></th>
                    <th class="transfer-h"><?= __('Serie') ?></th>
                </tr>
            <thead>
            <tbody>
                <?php 
                    foreach ($result as $a): ?>
                    <tr>
                        <td><?= h($a->plaque) ?></td>
                        <td><?= h($a->models_id) ?></td>  
                        <td><?= h($a->series) ?></td>
                    </tr>
                <?php endforeach; ?>
                
            </tbody>
        </table>

    </div>

    <div>
        <label> Observaciones: </label>
        <?php echo '<input type="text" id="observaciones" class="form-control col-sm-4 col-md-4 col-lg-4" readonly="readonly" value="' . htmlspecialchars($loan->observaciones). '">'; ?>
    </div> 
<br>
	 <b>1- <?= $this->Html->link(__('Descargar'), ['controller'=> 'Loans', 'action' => 'download',$loan->id], [ 'confirm' => __('Seguro que desea descargar el archivo?')]) ?> el formulario para llenar y luego subirlo al sitema.</b>
    <br><br>
    <div >
    <b><?php 
	    echo $this->Form->input('file_devolucion',['type' => 'file','label' => '2- Subir Formulario de Préstamo una vez lleno para Finalizar', 'class' => 'form-control-file']);
    ?></b>
     </div>
     <div class=\"col-12 text-right\">
	
	
    <br>


</div>

<div class="col-12 text-right">

    <?= $this->Html->link(__('Cancelar'), ['controller' => 'Loans', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary']) ?>
<br><br><br>
</div>

<?= $this->Form->end(); ?>
