<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TechnicalReport $technicalReport
 */
   use Cake\Routing\Router;
?> 
    <!-- Sección para el estilo de la pantalla -->
<style>
    .btn-primary {
      color: #FFF;
      background-color: #0099FF;
      border-color: #0099FF;
      float: right;
      margin-left: 10px;
    }

    label {
          text-align:left;
          margin-right: 10px;
          
    }

    label[class=label-t]{
        margin-left: 20px;
        width: 70px;
    }

    label[class=align]{
        margin-left: 14px;
    }

    input[name=date]{
          width:100px;
          margin-left: 10px;
        }

    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    td {

        border: 1px solid #000000;
        border-bottom: 1px solid #000000;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }

    th[class=transfer-h] {
        border-bottom: 1px solid #000000;
        text-align: center;
        color:black;
        padding: 8px;
    }

    .sameLine{
    display: flex; 
    justify-content: space-between; 
    border-color: transparent;
    }

</style> 

<div class="residues form large-9 medium-8 columns content">
    <?= $this->Form->create($residue,['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Insertar acta de desecho') ?></legend>
        <br> 
        <!-- Sección de entrada de datos --> 
        <div class="form-control sameLine" >    
            <div class='row'>
            <?php
            /*<label>Autorización Número: VRA-</label>
                <label><?php echo h($residue->residues_id); ?></label>*/
            echo '<label>Autorización Número: VRA-'.h($residue->residues_id).'</label>';
            ?>
        </div>
        <div class='row'>
            <label>Fecha:</label>
                <?php
                    $tmpDate= $residue->date->format('d-m-y');
                    echo $this->Form->imput('date', ['class'=>'form-control', 'value'=>$tmpDate, 'disabled']); 
                ?>
            </div>
        </div><br>

        <div class='row'>
            <label class='align'>Unidad Custodio:</label>
            <?php 
                echo $this->Form->imput('Unidad', ['class'=>'form-control col-sm-4', 'value'=>$Unidad, 'disabled']);
            ?>
        </div><br>

        <!-- Sección de cuadro para entrada de datos -->
        <label>En presencia de:</label>
        <table>
            <tr>
                <td><br>
                    <div class="row">
                            <label class='label-t'>Nombre:  </label>
                                <?php 
                                    echo $this->Form->imput('name1', ['class'=>'form-control col-sm-6', 'disabled']);
                                ?>
                    </div><br>
                    <div class="row">
                            <label class='label-t'>Cédula:  </label>
                                <?php 
                                    echo $this->Form->imput('identification1', ['class'=>'form-control col-sm-6','disabled']);
                                ?>
                    </div><br>
                </td>
            
                <td><br>
                    <div class="row">
                            <label class='label-t'>Nombre:</label>
                                <?php 
                                    echo $this->Form->imput('name2', ['class'=>'form-control col-sm-6', 'disabled']);
                                ?>
                    </div><br>
                    <div class="row">
                            <label class='label-t'>Cédula:  </label>
                                <?php 
                                    echo $this->Form->imput('identification2', ['class'=>'form-control col-sm-6', 'disabled']);
                                ?> 
                    </div><br>
                </td>
            </tr>
        </table><br>

        <div>
            <p>
                Se procede a levantar el Acta de Desecho de bienes muebles por haber cumplido su periodo de vida útil, de acuerdo con el Informe Técnico adjunto y la respectiva autorización por parte de la Vicerrectoría de Administración, de conformidad con el Reglamento para la Administración y Control de los Bienes Institucionales de la Universidad de Costa Rica.
            </p>
        </div><br>

    </fieldset>
</div>

    <!-- Sección para grid con activos asignado al acta -->
    <div class="related">
        <legend><?= __('Listado de Bienes') ?></legend>

        <table cellpadding="0" cellspacing="0">
            <tr>
                <th class="transfer-h" scope="col"><?= __('Placa') ?></th>
                <th class="transfer-h" scope="col"><?= __('Recomendacion') ?></th>
                <th class="transfer-h" scope="col"><?= __('Informe Técnico') ?></th>
                
            </tr>
    
            <?php

            $size = count($resultRec);
            for ($i=0; $i< $size;$i++){
                echo '<tr>';
                    echo '<td>' .h($result[$i]->plaque). '</td>';

                    switch ($resultRec[$i]->recommendation) {
                        case 'C':
                            echo '<td>' .'Reubicar'. '</td>';
                        break;
                        case 'R':
                            echo '<td>' .'Reparar'. '</td>'; 
                         break;
                        case 'D':
                            echo '<td>' .'Desechar'. '</td>'; 
                        break;
                        case 'U':   
                            echo '<td>' .'Usar Piezas'. '</td>'; 
                        break;
                        case 'O':
                            echo '<td>' .'Otros'. '</td>'; 
                        break;
                    } 
                    echo '<td>' .h($resultRec[$i]->technical_report_id). '</td>';      
                echo '</tr>';
            }
            
            ?>
        </table>
    </div><br>

    <div>
        <p align="center">
            (Art. 26 del Reglamento para la Administración y Control de los Bienes Institucionales de la Universidad de Costa Rica)
        </p>
    </div>
    <br>

    <b>1- <?= $this->Html->link(__('Generar'), ['controller'=> 'Residues', 'action' => 'generate',$residue->residues_id], [ 'confirm' => __('¿Seguro que desea descargar el archivo?')]) ?> el formulario para llenar y luego subirlo al sitema.</b>
    <br><br>
    <div >
    <b><?php echo $this->Form->input('file',['type' => 'file','label' => '2- Subir Formulario de Préstamo una vez lleno para Finalizar', 'class' => 'form-control-file']); ?></b>
     </div>
     <div class=\"col-12 text-right\">


    <br>
    <div>
        <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
        <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary']) ?>
    </div>
<br>

