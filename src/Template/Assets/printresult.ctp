<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Asset[]|\Cake\Collection\CollectionInterface $assets
 */
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



    <!-- RENDER FOR RESULT -->
    <div class="locations form large-9 medium-8 columns content">

  
        <br>


        <div class="related">

            <?php if($printing_assets == null): ?>

                <legend><?= __('No hay activos impresos') ?></legend>    

            <?php else:?>

              <legend><?= __('Activos Impresos:') ?></legend>
              <!-- tabla que contiene  datos básicos de activos-->
              <table id='assets-transfers-grid' cellpadding="0" cellspacing="0">
                  <thead>
                      <tr>
                          <th class="transfer-h"><?= __('Placa') ?></th>
                          <th class="transfer-h"><?= __('Tipo') ?></th>
                          <th class="transfer-h"><?= __('Marca') ?></th>
                          <th class="transfer-h"><?= __('Modelo') ?></th>
                          <th class="transfer-h"><?= __('Serie') ?></th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php 
                      foreach ($printing_assets as $a): ?>
                      <?php //debug($a)?>
                      <tr>
                          <td><?= h($a->plaque) ?></td>
                          <td><?= $a->has('Types') ? h($a->Types['name']) : '' ?></td>
                          <td><?= $a->has('Brands') ? h($a->Brands['name']) : '' ?></td>
                          <td><?= $a->has('Models') ? h($a->Models['name']) : '' ?></td>
                          <td><?= h($a->series) ?></td> 
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
            <?php endif; ?>
            
        </div>


        <?= $this->Html->link(__('Regresar'), ['action' => 'print'], ['class' => 'btn btn-primary']) ?>
      
    </div>




</body>

<br><br><br>

<script type="text/javascript">

$(document).ready(function() {

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

</script>


