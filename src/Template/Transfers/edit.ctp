<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Transfer $transfer
 */
use Cake\Routing\Router;
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>

<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.min.js" type="text/javascript"></script>

<style>
    
   .btn-primary{
      color: #FFF;
      background-color: #0099FF;
      border-color: #0099FF;
      float: right;
      margin-left:10px;
      text-transform: capitalize;
    }
    .btn-primary:hover{
        color: #fff;
        background-color: #0099FF;
    }
    .btn[type="submit"]:not{
        text-transform: capitalize;
    }
    .btn[type="submit"]:hover{
        text-transform: capitalize;
        color: #fff;
        background-color: #0099FF;
    }
    table {
    border-collapse: collapse;
    width: 100%;
    }
    td{
        border: 1px solid #000000;
        border-bottom: 1px solid #000000;
        padding: 8px;
    }
    th[class=transfer-h]{
        border-bottom: 1px solid #000000;
        text-align: center;
        color:black;
        padding: 8px;
    }
    label[class=label-t]{
        margin-left: 20px;
        width: 160px;
    }
    label[class=label-h]{
        margin-right: 10px;
    }
    label[class = funcionario]
    {
      margin-left: 20px;
      margin-right: 41px;
    }
    label[class = id]
    {
      margin-left: 20px;
      margin-right: 45px;
      width: 100px;
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


<div class="transfers form large-9 medium-8 columns content">
  <fieldset>
    <?= $this->Form->create($transfer,['type' => 'file']) ?>
    <legend>Editar traslado</legend>
    <br>
        <div class= 'form-control sameLine' style="border-color: transparent;">
            <div class ="row">                
                <label class="label-h">Nº traslado:</label>
                <?php echo '<input type="text" class="form-control col-sm-2 col-xs-2 col-md-4 col-lg-4" readonly="readonly" value="' . htmlspecialchars($transfer->transfers_id). '">'; ?> 
            </div>

            <div  class="row">
                <label class="label-h">Fecha:</label>
                <?php
                // para dar formato a la fecha
                $tmpDate= $transfer->date->format('d-m-Y');
                ?>  
                <?php echo '<input type="text" style="width: 120px;" id ="date" class="form-control " readonly="readonly" value="' . htmlspecialchars($tmpDate) . '">'; ?>
            </div>
 
        </div>
    <br>
    <table>
        <!-- Tabla para rellenar los datos de las unidades académicas -->
        <tr>
            <th class="transfer-h"><h5>Unidad que entrega<h5></th>
            <th class="transfer-h"><h5>Unidad que recibe<h5></th>
        </tr>
        <tr>
            <!-- Fila para la Unidad que entrega -->
            <td>

                <div class="row" >
                    <label class="label-t" ><b>Unidad académica:</b><font color="red"> * </font></label>
                   
                    <label><?php echo h($Unidad); ?></label>
                </div>
                <br>
                <div class="row">
                    <label class = "label-t" ><b>Funcionario:</b><font color="red"> * </font></label>
                    <?php 
                    echo $this->Form->select('functionary',
                      $users,
                      ['empty' => '(Escoja un usuario)','class'=>'form-control', 'style'=>'width:220px;','id'=>'functionary']
                    );
                    ?>
                </div>
                <br>
                <div class="row">
                    <label class="id" style ="margin-right: 67px;"><b>Cédula:</b><font color="red"> * </font></label>

                    <?php 
                        echo $this->Form->control('identification', 
                            [
                            'templates' => [
                                'inputContainer' => '<div class="row">{{content}}</div>',
                                'inputContainerError' => '<div {{type}} error"> {{content}} {{error}}</div>'
                                ],
                                "required"=>"required",
                            'label'=>['text' => '' ,'style'=>'margin-left:7px;'],
                            'id' =>'identification',
                            'class'=>'form-control col-sm-6'
                            ]);
                    ?>
                </div>
            </td>
            <!-- Fila para la Unidad que recibe -->
            <td>
                <div class="row">
                        <label class="label-t"><b>Unidad académica:</b><font color="red"> * </font></label>
                        <?php 
                        echo $this->Form->input('Acade_Unit_recib', 
                            [
                            'templates' => [
                                'inputContainer' => '{{content}}',
                                'inputContainerError' => '<div {{type}} error"> {{content}} {{error}}</div>'
                                ],
                                "required"=>"required",
                            'label'=>['text' => ''],
                            'id' =>'Acade_Unit_recib',
                            'class'=>'form-control col-sm-6 col-md-4 col-lg-4'
                            ]);
                    ?>      
                </div>
                <br>
                <div class="row">
                    <label class = "label-t" style ="margin-right: 20px;"><b>Funcionario:</b><font color="red"> * </font></label>
                    <?php 
                        echo $this->Form->imput('functionary_recib', [ 'id'=>'functionary_recib','class'=>'form-control','style'=>'width: 130px;']);
                    ?>
                </div>
                <br>
                <div class="row">
                    <label class="id" style ="margin-right: 77px;"><b>Cédula:</b><font color="red"> *</label>
                    <?php 
                        echo $this->Form->control('identification_recib', 
                            [
                            'templates' => [
                                'inputContainer' => '<div class="row">{{content}}</div>',
                                'inputContainerError' => '<div {{type}} error"> {{content}} {{error}}</div>'
                                ],
                                "required"=>"required",
                            'label'=>['text' => '' ,'style'=>'margin-left:7px;'],
                            'id' =>'identification_recib',
                            'class'=>'form-control col-sm-6'
                            ]);
                    ?>
                </div>               
            </td>
        </tr>
    </table>
    <br>


    <!-- AQUI ESTA LO IMPORTANTE. RECUERDEN COPIAR LOS SCRIPTS -->
    <div class="related">
        <legend><?= __('Activos a trasladar') ?></legend>
          </fieldset>

        <!-- tabla que contiene  datos básicos de activos-->
        <table id='assets-transfers-grid' cellpadding="0" cellspacing="0">
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
                <?php foreach ($asset as $a): ?>
                <tr>
                    <td><?= h($a->plaque) ?></td>
                    <td><?= h($a->brand) ?></td>
                    <td><?= h($a->model) ?></td>
                    <td><?= h($a->series) ?></td>
                    <td><?= h($a->state) ?></td>
                     <?php
                        // If que verifica si el checkbox debe ir activado o no
                        
                        if(in_array($a->plaque, array_column($result, 'plaque'),true) )
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
                                echo '</td >';
                            }
                    ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
    <br>
    <div class='form-control' style="border-color: transparent;">
        <?php
          if($loan->file_solicitud == ''){
              echo "<b>1- "; 
              echo $this->Html->link(__('Descargar'), ['controller'=> 'Transfers', 'action' => 'download',$transfer->transfer_id], [ 'confirm' => __('Seguro que desea descargar el archivo?')]);
              echo " el acta para llenar y luego subirlo al sitema.</b>";
              echo "<br><br><br>";
              echo "<div >";
              echo "<b>";
              echo $this->Form->input('file_name',['type' => 'file','label' => '2- Subir Acta de Traslado una vez llena para Finalizar', 'class' => 'form-control-file']);
              echo "</b>";
              echo "</div>";
              echo "<div class=\"col-12 text-right\">";

          }
         
        ?>

    </div>
    <br>

    <!-- input donde coloco la lista de placas checkeadas -->
    <input type="hidden" name="checkList" id="checkList">
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary','id'=>'aceptar','style'=>'text-transform: capitalize;']) ?>
    </form>

    

</div>



<script type="text/javascript">
      $(document).ready(function() 
    {
        var equipmentTable = $('#assets-transfers-grid').DataTable( {
              dom: 'Bfrtip',
                    buttons: [],
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
        $('#assets-transfers-grid input[type="checkbox"]').on('change', function() {
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

// funcion para colocar los valores de las placas de los activos seleccionados
//dentor de un input
    $("document").ready(
    function() {
      $('#aceptar').click( function()
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
        var res = document.getElementById('date').value;
        res=res+","+document.getElementById('Acade_Unit_recib').value;

        var pos= document.getElementById('functionary');
        res=res+","+pos.options[pos.selectedIndex].text;
        res=res+","+document.getElementById('identification').value;
        res=res+","+document.getElementById('functionary_recib').value;
        res=res+","+document.getElementById('identification_recib').value;
        $('#pdf').val(res);
        $('#plaques').val(check);
        } );
    }
    );
// funcion para colocar los valores de las placas de los activos seleccionados
//dentor de un input
    $("document").ready(
    function() {
      $('#aceptar').click( function()
      {
        var check = getValueUsingClass();
        $('#checkList').val(check);

        });
        }
    );

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