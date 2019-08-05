<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="types index content">
    <h3><?= __('Usuarios') ?></h3>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="users-grid"  class="table table-striped">
                <thead>
                    <tr>
                        <!--<th scope="col"><?= $this->Paginator->sort('id') ?></th>-->
                        
                        <th scope="col">Cédula</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido 1</th>
                        <th scope="col">Apellido 2</th>
                        <!--<th scope="col"><?= $this->Paginator->sort('Correo') ?></th>-->
                        <th scope="col">Usuario</th>
                        <!--<th scope="col"><?= $this->Paginator->sort('password') ?></th>-->
                        <!--<th scope="col"><?= $this->Paginator->sort('id_rol') ?></th>-->
                        <th scope="col">Estado</th>
                        <th scope="col" class="actions">Acciones</th>
                        <!--<th scope="col">Rol</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            
                            <!--<td><?= $this->Number->format($user->id) ?></td>-->
                            <td><?= h($user->id) ?></td>
                            <td><?= h($user->nombre) ?></td>
                            <td><?= h($user->apellido1) ?></td>
                            <td><?= h($user->apellido2) ?></td>
                            <!--<td><?= h($user->correo) ?></td>-->
                            <td><?= h($user->username) ?></td>
                            <!--<td><?= h($user->password) ?></td>-->
                            <!--<td><?= $this->Number->format($user->id_rol) ?></td>-->
                            <td><?= h($user->account_status == 1 ? 'Activo' : 'Inoperante') ?></td>
                            <!--<td><?= $user->has('roles') ? h($user->roles->nombre) : '' ?></td>-->
                            <td class="actions">
                                <?php if($allowC) : ?>
                                <?= $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-eye')), ['action' => 'view', $user->id], array('escape'=> false)) ?>
                                <?php endif; ?>
                                <?php if($allowM) : ?>
                                <?= $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-edit')), ['action' => 'edit', $user->id],  array('escape'=> false)) ?>
                                <?php endif; ?>

                                <?php if($allowE) : ?>
                                <?= $this->Form->postLink($this->Html->tag('i', '', array('class' => 'fa fa-trash')), ['action' => 'delete', $user->id],  ['escape'=> false,'confirm' => __('¿Está seguro que desea eliminar este usuario? # {0}?', $user->id)]) ?>
                                <?php endif; ?>

                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido1</th>
                        <th>Apellido2</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <td></td>
                        <!--<th>Rol</th>-->
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

    <style>
    .btn-primary {
        margin: 10px;
        margin-top: 15px;
        color: #fff;
        background-color: #FF9933;
        border-color: #FF9933;
    }
</style>

<?= $this->Html->link(__('Nuevo Usuario'), ['action' => 'add'] ,['class' => 'btn btn-primary']) ?>
<!--<?= $this->Form->button(__('PROBAR'), ['class' => 'btn btn-primary', 'id' => 'boton1']) ?>-->
<?= $this->Form->end() ?>

<!--    TEST     -->

        <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Busqueda</h4>
              </div>

              <div class="modal-body">
                   
                <h4 class="modal-title" id="myModalLabel">Seleccione el usuario:</h4>
                <br><br><br>

            <table id="select-users-grid"  class="table table-striped">
                <thead>
                    <tr>
                        
                        <th scope="col" class="actions"></th>
                        <th scope="col">Cédula</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido 1</th>
                        <th scope="col">Apellido 2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="actions">
                                <?= $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-check')), ['action' => 'view', $user->id], array('escape'=> false)) ?>
                                
                            </td>
                            <td><?= h($user->id) ?></td>
                            <td><?= h($user->nombre) ?></td>
                            <td><?= h($user->apellido1) ?></td>
                            <td><?= h($user->apellido2) ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>







              </div>
            </div>
          </div>
        </div>


<!--    -->


<script type="text/javascript">

    $(document).ready(function() {
        var table = $('#users-grid').DataTable( {
            dom: 'Bfrtip',
            buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
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
        // Setup - add a text input to each footer cell
        $('#users-grid tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="&#xF002; '+title+'" style="font-family:Arial, FontAwesome" />' );
        } );

        // DataTable
        //var table = $('#users-grid').DataTable();

        // Apply the search
        table.columns().every( function () {
            var that = this;

            $( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                    .search( this.value )
                    .draw();
                }
            } );
        } );

        $('#boton1').on('click', function () {
          $('#edit-item').modal("show");
          var table2 = $('#select-users-grid').DataTable( {
            dom: 'Bfrtip',
            buttons: [  
            'pdfHtml5'
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
        });




    } );


</script>
