<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Asset $asset
 */
    use Cake\Routing\Router;
?>


<style>
    .btn-primary {
          color: #fff;
          background-color: #0099FF;
          border-color: #0099FF;
          margin-left: 10px;
          margin: 10px;
          margin-top: 15px;
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
</style> 

<div class="locations form large-9 medium-8 columns content">
    <legend><?= __('Insertar préstamo') ?></legend>
    
    <br>

    <?= $this->Form->create($loan) ?>

    <div class="form-control sameLine">
			<div class="row col-lg-5">
				<label> <b>Responsable:</b><b style="color:red;">*</b> </label>
				<?php echo $this->Form->select('id_responsables', $users, array('empty' => true, 'class' => 'form-control col-md-7', 'id'=> 'userDropdown')); ?>
			</div>

			<div class="row">
				<label> <b>Fecha inicio:</b><b style="color:red;">*</b> </label>
				<?php echo $this->Form->imput('fecha_inicio', ['class'=>'form-control date', 'value' => date("y-m-d"), 'id'=>'datepicker']); ?>
			</div>
			
			<!--div class="row">
				<label> Fecha de devolución: </label>
                <?php echo $this->Form->imput('fecha_devolucion', ['class'=>'form-control date', 'id'=>'datepicker2']); ?>
			</div-->
			
		</div> <br>

    <br>

    <div>
      <label> Observaciones: </label>
      <?php echo $this->Form->textarea('observations', ['class'=>'form-control col-md-8']); ?>
    </div> <br>

    <br>

   
 <!-- AQUI ESTA LO IMPORTANTE. RECUERDEN COPIAR LOS SCRIPTS -->
        <div class="related">
            <legend><?= __('Activos a Prestar') ?></legend>
			<br>
            <!-- tabla que contiene  datos básicos de activos-->
            <table id='assets-transfers-grid2' cellpadding="0" cellspacing="0">
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
                      foreach ($asset_old as $a): ?>
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


        <div class="related">
            <legend><?= __('Activos Disponibles') ?></legend>
      <br>
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



    <!-- input donde coloco la lista de placas checkeadas -->
    <input type="hidden" name="checkList" id="checkList">

	


    <div class="col-12 text-right">

       
        <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>

         <?= $this->Form->button(__('Siguiente'), ['class' => 'btn btn-primary', 'id' => 'acept']) ?>




    </div>
    
    <?= $this->Form->end(); ?>

</div>

<script>


function download() {
      $.ajax({
           type: "POST",
           url: '/Decanatura/loans/add.php',
           data:{action:'download'},
           success:function(html) {
             alert("html");
           }

      });
 }

	$( function Picker() {
    $( "#datepicker" ).datepicker({ 
            dateFormat: 'y-mm-dd',
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            dayNamesMin: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do']
     });
  } );
  
	$( function Picker() {
    $( "#datepicker2" ).datepicker({ 
            dateFormat: 'y-mm-dd',
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            dayNamesMin: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do']
     });
  } );
    /*prueba para autocompletar*/
    /*
    jQuery('#assetImput').autocomplete({
            source:'<?php echo Router::url(array('controller' => 'Loan', 'action' => 'getPlaques')); ?>',
            minLength: 2
        });
    */





//Copy start


  $("document").ready(
    function() {
  
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


      $('#acept').click( function()
      {
        
        var check = getSelectedList();
        
        $('#checkList').val(check);
        
      });
    }
  );



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
         
        //var $row = $(this).closest('tr');

        var addRow = $('#assets-transfers-grid2').dataTable().fnGetData($row);
        //addRow[5] = addRow[6];
        //addRow.pop();

        var plaque = addRow[0];
        $('#assets-transfers-grid').DataTable().row.add(addRow).draw();
        $('#assets-transfers-grid2').dataTable().fnDeleteRow($row);


        
        document.getElementById(plaque).checked = false;
        
        
        $('#' + plaque).on('click', function() {

          if(this.checked == 1){
            var $row = $(this).closest('tr');

            //console.log($(this).closest('td').prev('td').find("select").val());

            var addRow = $('#assets-transfers-grid').dataTable().fnGetData($row);

            //var chk = addRow[5]['display'];
            //addRow.push(addRow[5]);
            //addRow[5] = '<select name="state" class="form-control" style="width:113px;" required="required"><option value="Bueno">Bueno</option><option value="Malo">Malo</option></select>';

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
        
        //var chk = addRow[5]['display'];

        //addRow.push(addRow[5]);
        //addRow[5] = '<select name="state" class="form-control" style="width:113px;" required="required"><option value="Bueno">Bueno</option><option value="Malo">Malo</option></select>';


        $('#assets-transfers-grid2').DataTable().row.add(addRow).draw();
        $('#assets-transfers-grid').dataTable().fnDeleteRow($row);


        document.getElementById(plaque).checked = true;

        $('#' + plaque).on('click', function() {

          if(this.checked == 0){
            var $row = $(this).closest('tr');

            //console.log($(this).closest('td').prev('td').find("select").val());

            var addRow = $('#assets-transfers-grid2').dataTable().fnGetData($row);

            //addRow[5] = addRow[6];
            //addRow.pop();

            var plaque = addRow[0];

            $('#assets-transfers-grid').DataTable().row.add(addRow).draw();

            $('#assets-transfers-grid2').dataTable().fnDeleteRow($row);

          }

        } );

          

        }




    } );






} );



//Copy end





    
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
