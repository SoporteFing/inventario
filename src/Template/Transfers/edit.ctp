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
                   
                    <label><?php echo h($unidadAcademica); ?></label>
                </div>
                <br>
                <div class="row">
                    <label class = "label-t" ><b>Funcionario:</b><font color="red"> * </font></label>
                    <?php 
                    echo $this->Form->select('functionary',
                      $users,
                      ['empty' => '(Escoja un usuario)','class'=>'form-control', 'style'=>'width:220px;','id'=>'functionary', 'onChange' => 'fillID(this.value);', 'value'=>$transfer['identification']]
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
                            'class'=>'form-control col-sm-6',
                            'Disabled'
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

<!-- DEVELOPING START -->

<div class="related">
        <legend><?= __('Activos a Trasladar') ?></legend>
        <!-- tabla que contiene  datos básicos de activos-->
        <table id='assets-transfers-grid2' cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="transfer-h"><?= __('Placa') ?></th>
                    <th class="transfer-h"><?= __('Tipo') ?></th>
                    <th class="transfer-h"><?= __('Marca') ?></th>
                    <th class="transfer-h"><?= __('Modelo') ?></th>
                    <th class="transfer-h"><?= __('Serie') ?></th>
                    <th class="transfer-h"><?= __('Condición') ?></th>
                    <th class="transfer-h"><?= __('Seleccionados') ?></th>
                    </tr>
            <thead>
            <tbody>

                <?php 
                foreach ($result as $a): ?>
                <?php //debug($a)?>
                <tr>
                    <td><?= h($a->plaque) ?></td>
                    <td><?= $a->has('Types') ? h($a->Types['name']) : '' ?></td>
                    <td><?= $a->has('Brands') ? h($a->Brands['name']) : '' ?></td>
                    <td><?= $a->has('Models') ? h($a->Models['name']) : '' ?></td>
                    <td><?= h($a->series) ?></td> 
                    <td>
                        <?php if(empty($states)) : ?> 
                            <?= $this->Form->select('state', array('Bueno' => 'Bueno', 'Malo' => 'Malo'), array('default' => $a->Assets_Transfers['transfers_state'], 'class' => 'form-control col-md-11')); ?></td>
                        <?php else : ?>  
                                <?= $this->Form->input('state', array('type' => 'select', 'label' => false,'options' => array('Bueno' => 'Bueno', 'Malo' => 'Malo'), 'value' => $states[$i] , 'class' => 'form-control col-md-11')); ?>
                            
                        <?php endif; ?>  
                    <!--td><?= h($a->Assets_Transfers['transfer_state']) ?></td-->
                    <td><?php
                                echo $this->Form->checkbox('assets_id',
                                ['value'=>htmlspecialchars($a->plaque),'id'=>htmlspecialchars($a->plaque),"class"=>"chk", "checked"]
                                );
                         ?>
                    </td>
                </tr>
                <?php endforeach; ?>

                
            </tbody>
        </table>

    </div>


<!-- DEVELOPING END-->


        <div class="related">
        <legend><?= __('Activos Disponibles') ?></legend>
        <!-- tabla que contiene  datos básicos de activos-->
        <table id='assets-transfers-grid' cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="transfer-h"><?= __('Placa') ?></th>
                    <th class="transfer-h"><?= __('Tipo') ?></th>
                    <th class="transfer-h"><?= __('Marca') ?></th>
                    <th class="transfer-h"><?= __('Modelo') ?></th>
                    <th class="transfer-h"><?= __('Serie') ?></th>
                    <th class="transfer-h"><?= __('Seleccionados') ?></th>
                </tr>
            <thead>
            <tbody>
                <?php 
                foreach ($asset as $a): ?>
                <?php //debug($a)?>
                <tr>
                    <td><?= h($a->plaque) ?></td>
                    <td><?= $a->has('Types') ? h($a->Types['name']) : '' ?></td>
                    <td><?= $a->has('Brands') ? h($a->Brands['name']) : '' ?></td>
                    <td><?= $a->has('Models') ? h($a->Models['name']) : '' ?></td>
                    <td><?= h($a->series) ?></td> 
                    <td><?php
                                echo $this->Form->checkbox('assets_id',
                                ['value'=>htmlspecialchars($a->plaque),'id'=>htmlspecialchars($a->plaque),"class"=>"chk"]
                                );
                         ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <br>
   
    <br>

    <!-- input donde coloco la lista de placas checkeadas -->


    </div>
        <input type="hidden" name="checkList" id="checkList">
        <input type="hidden" name="statesList" id="statesList">
    </div>


    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary','id'=>'aceptar','style'=>'text-transform: capitalize;']) ?>
    </form>

    

</div>



<script type="text/javascript">


    function fillID(val) {
        $('#identification').val(val);
    }


      $(document).ready(function() 
        {
            
            fillID( document.getElementById("functionary").value);


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

        var selectionTable = $('#assets-transfers-grid2').DataTable( {
                responsive: true,
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
                }
        } );

        /*
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
        */


    $('#assets-transfers-grid2').on('click', 'input[type="checkbox"]', function () {

      if(this.checked == 0){
         
        var $row = $(this).closest('tr');

        var addRow = $('#assets-transfers-grid2').dataTable().fnGetData($row);
        addRow[5] = addRow[6];
        addRow.pop();

        var plaque = addRow[0];
        $('#assets-transfers-grid').DataTable().row.add(addRow).draw();
        $('#assets-transfers-grid2').dataTable().fnDeleteRow($row);

        document.getElementById(plaque).checked = false;

        $('#' + plaque).on('click', function() {

          if(this.checked == 1){
            var $row = $(this).closest('tr');

            //console.log($(this).closest('td').prev('td').find("select").val());

            var addRow = $('#assets-transfers-grid').dataTable().fnGetData($row);

            var chk = addRow[5]['display'];
            addRow.push(addRow[5]);
            addRow[5] = '<select name="state" class="form-control" style="width:113px;" required="required"><option value="Bueno">Bueno</option><option value="Malo">Malo</option></select>';

            var plaque = addRow[0];

            $('#assets-transfers-grid2').DataTable().row.add(addRow).draw();

            $('#assets-transfers-grid').dataTable().fnDeleteRow($row);

          }

        });




       }
    });





    $('#assets-transfers-grid').on('click', 'input[type="checkbox"]', function () {

      if(this.checked == 1){
         
        var $row = $(this).closest('tr');

        var addRow = $('#assets-transfers-grid').dataTable().fnGetData($row);
          
        var plaque = addRow[0];
        
        var chk = addRow[5]['display'];

        addRow.push(addRow[5]);
        addRow[5] = '<select name="state" class="form-control" style="width:113px;" required="required"><option value="Bueno">Bueno</option><option value="Malo">Malo</option></select>';


        $('#assets-transfers-grid2').DataTable().row.add(addRow).draw();
        $('#assets-transfers-grid').dataTable().fnDeleteRow($row);


        document.getElementById(plaque).checked = true;

        $('#' + plaque).on('click', function() {

          if(this.checked == 0){
            var $row = $(this).closest('tr');

            //console.log($(this).closest('td').prev('td').find("select").val());

            var addRow = $('#assets-transfers-grid2').dataTable().fnGetData($row);

            addRow[5] = addRow[6];
            addRow.pop();

            var plaque = addRow[0];

            $('#assets-transfers-grid').DataTable().row.add(addRow).draw();

            $('#assets-transfers-grid2').dataTable().fnDeleteRow($row);

          }

        } );

          

        }


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