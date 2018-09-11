<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TechnicalReport $technicalReport
 */
   use Cake\Routing\Router;
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <?php echo $this->Html->css('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');?>
  <link rel="stylesheet" href="/resources/demos/style.css">

  <script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
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
        <label class='align'> <b> Número de traslado: </b> <font color="red"> * </font> VRA- </label>

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
                   
                    <label><?php echo h($paramUnidad); ?></label>
                </div>
                <br>
                <div class="row">
                    <label class = "funcionario" ><b>Funcionario:</b><font color="red"> * </font></label>
                    <?php 
                    echo $this->Form->select('functionary',
                      $users,
                      ['empty' => '(Escoja un usuario)','class'=>'form-control', 'style'=>'width:220px;']
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
                            'class'=>'form-control col-sm-6'
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
                      echo $this->Form->imput('functionary_recib', ['label' => 'functionary:', 'class'=>'form-control col-sm-4 col-xs-4']);
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
                            'class'=>'form-control col-sm-6'
                            ]);
                    ?>


                </div>               
            </td>
        </tr>
    </table>

        <div class="related">
        <legend><?= __('Activos a trasladar') ?></legend>
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
                <?php 
                foreach ($asset as $a): ?>
                <?php //debug($a)?>
                <tr>
                    <td><?= h($a->plaque) ?></td>
                    <td><?= h($a->brand) ?></td>
                    <td><?= h($a->model) ?></td>
                    <td><?= h($a->series) ?></td>
                    <td><?= h($a->state) ?></td>
                    <td data-order="0"><?php
                                echo $this->Form->checkbox('assets_id',
                                ['value'=>htmlspecialchars($a->plaque),"class"=>"chk"]
                                );
                         ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
        <input type="hidden" name="checkList" id="checkList">

    </div>
    <br>
    
  </fieldset>
</div>

  <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
  <?= $this->Form->button(__('Siguiente'), ['class' => 'btn btn-primary','id'=>'acept']) ?>

</body>

<script>
  $( function Picker() {
    $( "#datepicker" ).datepicker({ 
            dateFormat: 'y-mm-dd',
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            dayNamesMin: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do']
     });
  } );
  $("document").ready(
    function() {
      $('#assetButton').click( function()
      {
        var plaque = $('#assetImput').val();
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
                    $('#assetResult').html('Introdusca otro número de placa.');
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

// función para validar que algún checkbox ha sido marcado
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
    };

    $("document").ready(
    function() {
      $('#acept').click( function()
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
    };
</script>



