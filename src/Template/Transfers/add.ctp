<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TechnicalReport $technicalReport
 */
   use Cake\Routing\Router;
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
        input[type=radio] {
          width:10px;
          clear:left;
          text-align:left;
        }
        input[name=date]{
          width:100px;
          margin-left: 10px;
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

</head>


<body>
<div class="locations form large-9 medium-8 columns content">
  <?= $this->Form->create($transfer,['novalidate','onsubmit'=>'return validateCheck()'])?>
  <fieldset>
    <legend><?= __('Insertar traslado') ?></legend>
    <br>

    <div class="form-control sameLine">
      <div>
        <div class="form-control sameLine">
        <label class='align'> <b> Número de traslado: </b> <font color="red"> * </font> <?php echo h($paramAcronimo . ' - '); ?> </label>

      <?php 
        echo $this->Form->control('transfers_id', 
                [
                    'templates' => [
                    'inputContainer' => '<div class="row">{{content}}</div>',
                    'inputContainerError' => '<div class="row {{type}} error"> {{content}} {{error}}</div>'
                    ],
                'label'=>['text'=>''],
                'class'=>'form-control col-sm-4 col-lg-6 col-md-6',
                'type'=>'text',
                'id' =>'transfers_id'
                ]);
      ?>
    </div>
      </div>
      <br>
      <div>
        <div class="form-control sameLine">
        <label class='align' required="required"> <b> Fecha: </b> <font color="red"> * </font></label>
      <?php 
        echo $this->Form->control('date', 
          [
            'templates' => [
              'inputContainer' => '<div class="row">{{content}}</div>',
              'inputContainerError' => '<div class="row {{type}} error"> {{content}} {{error}}</div>'
            ],
            'label'=>['text'=>'', 'style'=>'margin-left= 10px;'],
            'class'=>'form-control',
            'type'=>'text',
            'id'=>'datepicker'
          ]);
      ?>
    </div>

      </div>
  </div>
    <div id=assetResult> 
    </div><br>
    <div>
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
                    <label class = "funcionario" ><b>Funcionario:</b><font color="red"> * </font></label>
                    <?php 
                    echo $this->Form->control('functionary',
                      ['id' => 'functionary', 'empty' => '(Escoja un usuario)','class'=>'form-control','onChange' => 'fillID(this.value);', 'style'=>'width:220px;', 'options' => $users, 'type' => 'select', 'label' => false]
                    );
                    ?>
                </div>
                <br>
                <div class="row">
                    <label class="id"><b>Cédula:</b><font color="red"> * </font></label>

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
                            'class'=>'form-control col-sm-8',
                            'disabled'
                            ]);
                    ?>



                </div>
            </td>
            <!-- Fila para la Unidad que recibe -->
            <td>
                <div class="row">
                        <label style="width: 160px;margin-left: 20px;" ><b>Unidad académica:</b><font color="red"> * </font></label>
                        <?php 
                        echo $this->Form->control('Acade_Unit_recib', 
                            [
                            'templates' => [
                                'inputContainer' => '<div  class="row">{{content}}</div>',
                                'inputContainerError' => '<div {{type}} error"> {{content}} {{error}}</div>'
                                ],
                                "required"=>"required",
                            'label'=>['text' => '' ,'style'=>'margin-left:7px;'],
                            'id' =>'Acade_Unit_recib',
                            'class'=>'form-control col-sm-6 col-md-10 col-lg-10'
                            ]);
                    ?>

                </div>
                <br>

                <div class="row">
                    <label style="width: 160px;margin-left: 20px;" ><b>Funcionario:</b><font color="red"> * </font></label>
                    <?php 
                      echo $this->Form->control('functionary_recib', [
                                'label' => ['text' => '' ,'style'=>'margin-left:7px;'], 
                                'class'=>'form-control col-sm-6 col-md-10 col-lg-10', 'templates' => [
                                'inputContainer' => '<div class="row">{{content}}</div>',
                                'inputContainerError' => '<div {{type}} error"> {{content}} {{error}}</div>'
                                ],
                                "required"=>"required",
                                'id' =>'functionary_recib'

                            ]);
                     ?>
                </div>

                <br>
                <div class="row">
                    <label style="width: 160px;margin-left: 20px;"><b>Cédula:</b><font color="red"> * </font></label>
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
                            'class'=>'form-control col-sm-8'
                            ]);
                    ?>


                </div>               
            </td>
        </tr>
    </table>

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
                foreach ($asset_old as $i => $a): ?>
                <tr>
                    <td><?= h($a->plaque) ?></td>
                    <td><?= $a->has('Types') ? h($a->Types['name']) : '' ?></td>
                    <td><?= $a->has('Brands') ? h($a->Brands['name']) : '' ?></td>
                    <td><?= $a->has('Models') ? h($a->Models['name']) : '' ?></td>
                    <td><?= h($a->series) ?></td> 
                    <td><?= $this->Form->input('state', array('type' => 'select', 'label' => false,'options' => array('Bueno' => 'Bueno', 'Malo' => 'Malo'), 'value' => $states[$i] , 'class' => 'form-control col-md-11')); ?></td>
                    <td><?php
                                echo $this->Form->checkbox('assets_id',
                                ['value'=>htmlspecialchars($a->plaque),'id'=>htmlspecialchars($a->plaque),"class"=>"chk", 'checked']
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

    </div>
        <input type="hidden" name="checkList" id="checkList">
        <input type="hidden" name="statesList" id="statesList">
    </div>
    <br>
    
  </fieldset>
</div>

  <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
  <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary','id'=>'acept']) ?>

</body>

<script>

    function fillID(val) {
        $('#identification').val(val);
    }

  $( function Picker() {
    $( "#datepicker" ).datepicker({ 
            dateFormat: 'y-mm-dd',
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            dayNamesMin: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do']
     });
  } );
  $("document").ready(
    function() {
        
      fillID( document.getElementById("functionary").value);

      $('#assetButton').click( function()
      {
        var plaque = $('#assetinput').val();
        if(''!=plaque)
        {
         $.ajax({
                type: "GET",
                url: '<?php echo Router::url(['controller' => 'TechnicalReports', 'action' => 'search' ]); ?>',
                data:{id:plaque},
                beforeSend: function() {
                     $('#assetResult').html('<label>Cargando</label><i class="fa fa-spinner fa-spin" style="font-size:25px"></i>');
                     },
                success: function(msg){
                    $('#assetResult').html(msg);
                    },
                error: function(e) {
                    alert("Ocurrió un error: artículo no encontrado.");
                    console.log(e);
                    $('#assetResult').html('Introduzca otro número de placa.');
                    }
              });
          
        }
        else
        {
          $('#assetResult').html('Primero escriba un número de placa.');
        }
      });
    }
  );
</script>

<script type="text/javascript">
$(document).ready(function() 
{
    var equipmentTable = $('#assets-transfers-grid').DataTable( {
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

// función para validar que algún checkbox ha sido marcado
    function validateCheck() {
      var checks, error;

      // Get the value of the input field with id="numb"
      checks = getSelectedList();


      // If x is Not a Number or less than one or greater than 10
      if ( checks.length == 0 ) {
        error = "Seleccione al menos un activo";
        document.getElementById("errorMsg").innerHTML = error;
        return false;
      } else {
        return true;
      }
    };

    $("document").ready(
    function() {
      $('#acept').click( function()
      {
        
        var check = getSelectedList();
        var states = getStatesList();
        $('#checkList').val(check);
        $('#statesList').val(states);
      });
    }
    );

/** función obtenida de http://bytutorial.com/blogs/jquery/jquery-get-selected-checkboxes */

    function getSelectedList(){
        /* declare an checkbox array */
        var chkArray = [];
        
        /* look for all checkboxes that have a class 'chk' attached to it and check if it was checked */
        $(".chk:checked").each(function() {
            chkArray.push($(this).val());
        });

        console.log(chkArray);
        
        /* we join the array separated by the comma */
        var selected;
        selected = chkArray.join(',') ;
        return selected;
    };


    function getStatesList(){
        /* declare an checkbox array */
        var statesArray = [];
        
        /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
        $(".chk:checked").each(function() {
            statesArray.push($(this).closest('td').prev('td').find("select").val());
        });
        
        /* we join the array separated by the comma */
        var selected;
        selected = statesArray.join(',') ;
        return selected;
    };


</script>



