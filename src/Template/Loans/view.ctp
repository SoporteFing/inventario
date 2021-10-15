<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Loan $loan
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
  


<div>

  <fieldset>
        <legend><?= __('Consultar préstamo') ?></legend>
    
    <br>

  </fieldset>
    <br>
    
<?php 
      $nombre_archivo_cambiado = str_replace(' ', '%20', $loan->file_devolucion);
      echo "<iframe src=./../../webroot/files/Uploads/Loans/$nombre_archivo_cambiado width=1000px height=1250px ></iframe>";
?>

      <label> Observaciones: </label>
      <?php echo '<input type="text" id="observaciones" class="form-control col-sm-4 col-md-4 col-lg-4" readonly="readonly" value="' . htmlspecialchars($loan->observaciones). '">'; ?>
    </div> <br>

<?php
  if($loan->file_devolucion == ''){
      echo "<b>Error 404, archivo no encontrado.</b>";

  }else{

      echo $this->Html->link(__('Descargar formulario.'),'/' . $loan->file_devolucion_dir . $loan->file_devolucion);
      echo "<div class=\"col-12 text-right\">";
  }
 
?>


 <?= $this->Html->link(__('Terminar'), ['controller' => 'Loans', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
<?php
   if($loan->file_solicitud == ''){
    $this->Form->button(__('Subir'), ['class' => 'btn btn-primary']);
   }else{
    if($loan->estado != 'Terminado'){
      
      echo $this->Html->link(__('Finalizar Préstamo'), ['action' => 'terminar',$loan->id], ['class' => 'btn btn-primary']);
    }
   }
?>




</div>

