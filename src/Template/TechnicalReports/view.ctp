<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TechnicalReport $technicalReport
 */



?>
<style>
.modal-header-primary {
    color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #428bca;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;
}

.btn-default {
  border-color: #FFFFFF;
}
label{
    text-align:left;
    margin-right: 10px;
    display:inline-flex;    
}
.btn-primary {
    color: #FFF;
    background-color: #0099FF;
    border-color: #0099FF;
    float: right;
    margin-left:10px;
}
.sameLine{
    display: flex; 
    justify-content: space-between; 
    border-color: transparent;

}
</style>



<div class="technicalReports view large-9 medium-8 columns content">
    <legend>Informe técnico</legend>
            <br>
            <div class="form-control sameLine" >
                <div class="row">
                <label>Reporte Nº:</label>
                <?php echo '<input type="text" class="form-control" readonly="readonly" name="id" value="' . htmlspecialchars($internalID) . '" style="width: 160px;" >'; ?>     
                </div>

                <div class="row">
                <label >Fecha:</label>
                <?php echo '<input type="text" class="form-control" readonly="readonly" name="fecha" value="' . htmlspecialchars($technicalReport->date).'" style="width: 100px;">'; ?>

                </div>

            </div>
            <br>

            <div>
                <label>Placa del activo:</label>
                <?php echo $this->Html->link($technicalReport->asset->plaque, '#list', array(
                                      'data-toggle' => 'modal',
                                        'class' => 'btn btn-default'
                                    )); ?>
            </div><br>



            <!--  Modal activado por el boton con el número de placa -->
            <!--  Este contiene datos básicos del activo -->
            <div class="modal fade" id="list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

             <div class="modal-dialog" style="width: 100%">
                  <div class="modal-content">
                         <div class="modal-header modal-header-primary">
                            <h1>Datos del activo</h1>
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        </div>
                        <div class="modal-body">

                            <div class="form-control sameLine">

                                <div class="row">
                                    <label>Nº de placa:</label>
                                
                                    <?php echo '<input type="text" class="form-control" readonly="readonly" name="plaque" value="' . htmlspecialchars($assets->plaque) . '" style="width: 100px;">'; ?>
                                </div>     

                                <div class="row">
                                    <label>Nº de serie:</label>
                                    <?php echo '<input type="text" class="form-control " readonly="readonly" name="series" value="' . htmlspecialchars($assets->series) . '" style="width: 100px;">'; ?> 
                                </div>    
                            </div>
                     

                            <div class="form-control sameLine">

                                <div class="row">
                                    <label style="width: 91px;" >Marca:</label>
                                    <?php echo '<input type="text" class="form-control" readonly="readonly" name="brand" value="' . htmlspecialchars($assets->brand) . '" style="width: 100px;" >'; ?>     
                                </div>

                                <div class="row">
                                    <label>Modelo:</label>
                                    <?php echo '<input type="text" class="form-control" readonly="readonly" name="model" value="' . htmlspecialchars($assets->model) . '" style="width: 100px;" >'; ?> 
                                </div>          
                            </div><br>

                            <div>
                            <label>Evaluación:</label><br>
                                <textarea class="form-control" readonly="readonly" rows="3" cols="20"><?= h($technicalReport->asset->description);?></textarea>     
                            </div><br>
            
                         </div>
                    </div>
                </div>
            </div>



            
            <div>
                <label>Evaluación:</label><br>
                <textarea class="form-control" readonly="readonly" rows="3" cols="20"><?= h($technicalReport->evaluation);?></textarea>     
            </div>
            <br>

            <div class="row col-md-8" >
                <label>Recomendación:</label>
                <?php if ("C"==$technicalReport->recommendation): ?>
                    Reubicar
                <?php endif; ?>

                <?php if("R"==$technicalReport->recommendation): ?>
                    Reparar
                <?php endif; ?>

                <?php if("D"==$technicalReport->recommendation): ?>
                    Desechar
                <?php endif; ?>

                <?php if("U"==$technicalReport->recommendation): ?>
                    Usar piezas
                <?php endif; ?>

                <?php if("O"==$technicalReport->recommendation): ?>
                    Otros
                <?php endif; ?>
            </div>
            <br>

            <div class="row col-md-8"> 
                 <label>Nombre del Técnico Especializado: </label>
                    <?php
                    echo $this->Form->imput('evaluator_name', ['class'=>'form-control col-md-4',"readonly"=>"readonly", "value"=>$technicalReport->evaluator_name]); 
                    ?>   
            </div>
            <br><br>

</div>

<?= $this->Html->link(__('Cancelar'), ['controller'=> 'TechnicalReports', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>

<?php if($technicalReport->file_name == null) : ?> 

    <?= $this->Html->link(__('Modificar'), ['controller'=> 'TechnicalReports', 'action' => 'edit', $technicalReport->technical_report_id], ['class' => 'btn btn-primary']) ?>

<?php endif; ?> 

<?php if(($technicalReport->descargado == null) && ($technicalReport->file_name == null )) : ?> 

    <?= $this->Form->postLink(__('Eliminar'), ['controller'=> 'TechnicalReports', 'action' => 'delete', $technicalReport->technical_report_id], ['class' => 'btn btn-primary', 'confirm' => __('¿Seguro que desea eliminar la Ubicación #'.$technicalReport->technical_report_id.' ?', $technicalReport->technical_report_id)]) ?>

<?php endif; ?>
</form>

<?php 
    if($technicalReport->file_name != null)
    {
         echo $this->Form->postLink(__('Descargar Informe técnico'), ['action' => 'download2', $technicalReport->technical_report_id], ['class' => 'btn btn-primary','style'=>'float:left;', 'confirm' => __('¿Seguro que desea descargar el archivo?', $technicalReport->technical_report_id)]);
    }
?>
