<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Residue $residue
 */
    use Cake\Routing\Router;
    $mysqli = new mysqli('decanatura.mysql.database.azure.com','ecci@decanatura','Gaby1234','decanatura');
    use Cake\I18n\Date;
?>
<!-- Sección de estilo para la pantalla -->
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

    label[class=align]{
        margin-left: 14px;
    }

    label[class=label-t]{
        margin-left: 20px;
        width: 70px;
    }

    input[name=date]{
          width:120px;
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
    .cuadro
    {
        display: flex; 
    border-color: transparent;
    }
</style>
<!-- Sección de scripts utilizados por la pantalla -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <?php echo $this->Html->css('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');?>
  <link rel="stylesheet" href="/resources/demos/style.css">

  <script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>

  <script src="http://code.jquery.com/ui/1.9.1/jquery-ui.min.js" type="text/javascript"></script>




 <div class="residues form large-9 medium-8 columns content">
    <?= $this->Form->create($residue,['novalidate','onsubmit'=>'return validateCheck()','type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Modificar acta de desecho') ?></legend>
        <br> 
        <!-- Sección de entrada de datos -->   
         <div class="form-control sameLine" >    
        <div class='row'>

            <label>Autorización Número: VRA-</label>
            <?php echo '<input type="text" id="residues_id" class="form-control col-sm-4" disabled value="'.h($residue->residues_id).'">'; ?>
        </div>
        <div class='row'>
            <label>Fecha:</label>
                <?php
                    //debug($residue->date);
                    $tmpDate= $residue->date->format('d-m-Y');
                    /*echo $this->Form->imput('date', ['class'=>'form-control', 'value'=>$tmpDate, 'disabled']); */
                    echo "<input type='text' class='form-control' name='date' id='date' value=".$tmpDate." disabled>"
                ?>
        </div>
        </div><br>

        <div class='row'>
            <label class='align'>Unidad Custodio:</label>
            <?php 
                echo $this->Form->imput('Unidad', ['class'=>'form-control col-sm-3', 'value'=>$Unidad, 'disabled','id'=>'unit']);
            ?>
        </div><br>

        <!-- Sección de cuadro para entrada de datos -->
        <label>En presencia de:</label>
        <table>
            <tr>
                <td><br>
                    
                    <!-- Se modificó la clase del div (a travez de la plantilla) y la del label
                                  Este mismo proceso se aplica en las demás geberaciones -->
                    <div class="form-control cuadro">
                    <label style =  "text-align:left; margin-right: 10px;"> <b> Nombre: </b> <font color="red"> * </font> </label><br>          
                    <?php 
                        echo $this->Form->control('name1', 
                            [
                            'templates' => [
                                'inputContainer' => '<div class="row">{{content}}</div>',
                                'inputContainerError' => '<div class="row {{type}} error"> {{content}} {{error}}</div>'
                                ],

                            'label'=>['text'=>''],
                            //"required"=>"required",
                            'id'=>'name1',
                            'class'=>'form-control',
                            'style'=>'width: 180px;'
                            ]);
                    ?>
                </div>
                    <br>

                    <div class="form-control cuadro">
                    <label style =  "text-align:left; margin-right: 10px;"> <b> Cédula: </b> <font color="red"> * </font> </label><br>  

                    <?php 
                        echo $this->Form->control('identification1', 
                            [
                            'templates' => [
                                'inputContainer' => '<div class="row">{{content}}</div>',
                                'inputContainerError' => '<div class="row {{type}} error"> {{content}} {{error}}</div>'
                                ],
                                "required"=>"required",
                            'label'=>['text' => '' ,'style'=>'margin-left:7px;'],
                            'id' => 'identification1',
                            'class'=>'form-control ',
                            'style'=>'width: 180px;'
                            ]);
                    ?>
                </div>
                    <br>
                </td>
            
                <td><br>

                    <div class="form-control cuadro">
                    <label style =  "text-align:left; margin-right: 10px;"> <b> Nombre: </b> <font color="red"> * </font> </label><br>  

                    <?php 
                        echo $this->Form->control('name2', 
                            [
                            'templates' => [
                                'inputContainer' => '<div class="row">{{content}}</div>',
                                'inputContainerError' => '<div class="row {{type}} error"> {{content}} {{error}}</div>'
                                ],

                            'label'=>['text'=>''],
                            'style'=>'width: 180px;',
                            'id' => 'name2',
                            'class'=>'form-control '
                            ]);
                    ?>
                </div>
                    <br>

                    <div class="form-control cuadro">
                    <label style =  "text-align:left; margin-right: 10px;"> <b> Cédula: </b> <font color="red"> * </font> </label><br>  

                    <?php 
                        echo $this->Form->control('identification2', 
                            [
                            'templates' => [
                                'inputContainer' => '<div class="row">{{content}}</div>',
                                'inputContainerError' => '<div class="row {{type}} error"> {{content}} {{error}}</div>'
                                ],
                            'label'=>['text' => '' ,'style'=>'margin-left:7px;'],
                            'style'=>'width: 180px;',
                            'id' =>'identification2',
                            'class'=>'form-control '
                            ]);
                    ?>
                    </div>
                    <br>
                </td>
            </tr>
        </table><br>

        <div>
            <p>
                Se procede a levantar el Acta de Desecho de bienes muebles por haber cumplido su periodo de vida útil, de acuerdo con el Informe Técnico adjunto y la respectiva autorización por parte de la Vicerrectoría de Administración, de conformidad con el Reglamento para la Administración y Control de los Bienes Institucionales de la Universidad de Costa Rica.
            </p>
        </div><br>

    </fieldset>
</div><br>





 <!-- Sección de grid para agregar o eliminar activos al acta de desecho -->
        <div class="form-control" style="border-color: transparent;">
            <fieldset>
            <legend style="margin-left: 13px;"><?= __('Activos a desechar') ?></legend>
            </fieldset>
            <!--  Sirve para mostrar el mensaje que se debe seleccionar un activo -->
            <p id="errorMsg" style="color: red;"></p>
            <!-- tabla que contiene  datos básicos de activos-->
            <table id='assets-residues-grid' cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="transfer-h"><?= __('Placa') ?></th>
                        <th class="transfer-h"><?= __('Marca') ?></th>
                        <th class="transfer-h"><?= __('Modelo') ?></th>
                        <th class="transfer-h"><?= __('Serie') ?></th>
                        <th class="transfer-h"><?= __('Estado') ?></th>
                        <th class="transfer-h"><?= __('Seleccionados') ?></th>
                    </tr>
                <thead>
                <tbody>
                    <?php
                      foreach ($result as $a): ?>
                      <tr>
                          <td><?= h($a->plaque) ?></td>
                          <td><?= h($a->brand) ?></td>
                          <td><?= h($a->model) ?></td>  
                          <td><?= h($a->series) ?></td>
                          <td><?= h($a->state) ?></td>
                          <?php
                              // If que verifica si el checkbox debe ir activado o no
                              $isIn= in_array($a->plaque, array_column($result2, 'plaque') );
                              if($isIn)
                                  {
                                      echo '<td data-order="1">';
                                      echo $this->Form->checkbox('assets_id',
                                      ['value'=>htmlspecialchars($a->plaque),'checked', "class"=>"chk" ]
                                      );
                                      echo '</td>';
                                  }
                              else
                                  {
                                      echo '<td data-order="0">';
                                      echo $this->Form->checkbox('assets_id',
                                      ['value'=>htmlspecialchars($a->plaque),"class"=>"chk"]
                                      );
                                      echo '</td>';
                                  }
                              
                              
                          
                          ?>
                      </tr>
                    <?php endforeach; ?>
                    
                </tbody>
            </table>

        </div>

    <!-- input donde coloco la lista de placas checkeadas -->
    <input type="hidden" name="checkList" id="checkList">


    <div>
        <p align="center">
            (Art. 26 del Reglamento para la Administración y Control de los Bienes Institucionales de la Universidad de Costa Rica)
        </p>
    </div>
    <br>
    <div class='form-control' style="border-color: transparent;">
        
        <?php
          if($residue->file_name == ''){
              echo "<b>1- Descargar el acta para llenar y luego subirlo al sitema.</b>";
              echo "<br><br><br>";
              echo "<div >";
              echo "<b>";
              echo $this->Form->input('file_name',['type' => 'file','label' => '2- Subir Acta de Desecho una vez llena para Finalizar', 'class' => 'form-control-file']);
              echo "</b>";
              echo "</div>";
              echo "<div class=\"col-12 text-right\">";

          }
         
        ?>



    </div>
    <br>
    
    <div>

    
    <!-- Sección de botones -->
        <div>
        <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
        <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary', 'id' => 'acept']) ?>
        </div>
</form>
        <!-- Creo un nuevo from para hacer el post hacia el método de descarga
            Además hay 2 inputs hidden para poder colocar ahí los datos actualues de la vista.
        -->
        
        <?= $this->Form->create(null,['type'=>'post',
                                        'url'=>'/residues/download'
                                    ]) ?>
        <!-- input donde coloco todo los datos de los demás imput exepto el input checkList -->
        <input type="hidden" name="pdf" id="pdf">
        <!-- input donde coloco todo los datos de los demás imput exepto el input checkList -->
        <input type="hidden" name="plaques" id="plaques">


        <?= $this->Form->button(__('Descargar PDF'), ['class' => 'btn btn-primary', 'id'=>'generate','style'=>'float:left;']) ?>
        </form>


    </div>

    
<script type="text/javascript">
/** método extraido de https://stackoverflow.com/questions/46590217/jquery-datatable-order-table-based-on-checkbox
para poder ordenar los checksbox
**/

$(document).ready(function() 
{
    var equipmentTable = $('#assets-residues-grid').DataTable( {
         dom: 'Bfrtip',
                buttons: [
                ],
                "iDisplayLength": 10,
                "paging": true,
                "pageLength": 10,
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "decimal": ",",
                    "thousands": ".",
                    "sSelect": "1 fila seleccionada",
                    "select": {
                        rows: {
                            _: "Ha seleccionado %d filas",
                            0: "Dele click a una fila para seleccionarla",
                            1: "1 fila seleccionada"
                        }
                    },
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }

                },
                "order": [[ 5, "desc" ]]
    } );
     // Listen to change event from checkbox to trigger re-sorting
    $('#assets-residues-grid input[type="checkbox"]').on('change', function() {
    // Update data-sort on closest <td>
    $(this).closest('td').attr('data-order', this.checked ? 1 : 0);
    
    // Store row reference so we can reset its data
    var $tr = $(this).closest('tr');
    
    // Force resorting
    equipmentTable
    .row($tr)
    .invalidate()
    .order([ 5, 'desc' ])
    .draw();
    } );
        
} );

function validateCheck() {
    var checks, error;

    // Get the value of the input field with id="numb"
    checks = getValueUsingClass();


    // If x is Not a Number or less than one or greater than 10
    if ( checks.length == 0 ) {
        error = "Seleccione al menos un activo";
        document.getElementById("errorMsg").innerHTML = error;
        return false;
    } else {
        return true;
    }
    
}

// funcion para colocar los valores de las placas de los activos seleccionados
//dentor de un input
    $("document").ready(
    function() {
      $('#acept').click( function()
      {
        var check = getValueUsingClass();
        $('#checkList').val(check);

        });
        }
    );

    //  Funcion para meter todos los datos en el input pdf para posteriormente 
    //usar los datos en el método download del controlador
    $("document").ready(
    function() {
      $('#generate').click( function()
      {
        var check = getValueUsingClass();
        //concateno todos los valores
        var res = document.getElementById('residues_id').value;
        res=res+","+document.getElementById('date').value;
        res=res+","+document.getElementById('name1').value;
        res=res+","+document.getElementById('identification1').value;
        res=res+","+document.getElementById('name2').value;
        res=res+","+document.getElementById('identification2').value;
        $('#pdf').val(res);
        $('#plaques').val(check);
        });
        }
    );

    /*$("document").ready(
    function() {
      $('#acept').click( function()
      {
        var check = getValueUsingClass();
        $('#checkList').val(check);
        });
        }
    );*/
/** función optenida de http://bytutorial.com/blogs/jquery/jquery-get-selected-checkboxes */
    function getValueUsingClass(){
    /* declare an checkbox array */
    var chkArray = [];
    
    /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
    $(".chk:checked").each(function() {
        chkArray.push($(this).val());
    });
    
    /* we join the array separated by the comma */
    var selected;
    selected = chkArray.join(',') ;
    return selected;
}
</script>
