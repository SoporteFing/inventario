<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Asset[]|\Cake\Collection\CollectionInterface $assets
 */
?>

<div class="types index content">
    <h3><?= __('Activos') ?></h3>
</div>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="assets-grid"  class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Placa</th>
                        <th scope="col">Tipo</th>        
                        <th scope="col">Marca</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Serie</th>
                        <!--th scope="col">Descripción</th-->
                        <th scope="col">Estado</th>
                        <th scope="col">Asignado</th>
                        <th scope="col">Ubicación</th>                
                        <th scope="col">Año</th>
                        <th scope="col" class="actions">Acciones<?= __('') ?></th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assets as $asset): ?>
                        <tr>
                                                        
                            <td><?= h($asset->plaque) ?></td>
                            <td><?= $asset->has('Types') ? h($asset->Types['name']) : '' ?></td>
                            <td><?= $asset->has('Brands') ? h($asset->Brands['name']) : '' ?></td>
                            <td><?= $asset->has('Models') ? h($asset->Models['name']) : '' ?></td>
                            <td><?= h($asset->series) ?></td>
                            <!--td><?= h($asset->description) ?></td-->
                            <td><?= h($asset->state) ?></td>
                            <td><?= h($asset->Users['nombre'] . " " . $asset->Users['apellido1']) ?></td>
                            <td><?= h($asset->Locations['nombre']) ?></td>
                            <td><?= h($asset->year) ?></td>
                            <td class="actions">
                                <?php if($allowC) : ?>
                                <?= $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-eye')), ['action' => 'view', $asset->plaque], array('escape'=> false, 'target' => '_blank')) ?>
                                <?php endif; ?>

                                <?php if($allowM) : ?>
                                <?= $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-edit')), ['action' => 'edit', $asset->plaque],  array('escape'=> false, 'target' => '_blank')) ?>
                                <?php endif; ?>

                                <?php if($allowE) : ?>
                                <?= $this->Form->postLink($this->Html->tag('i', '', array('class' => 'fa fa-trash')), ['action' => 'softDelete', $asset->plaque],  ['escape'=> false,'confirm' => __('¿Está seguro que desea eliminar este activo? # {0}?', $asset->id)]) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        
                        <th>Placa</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Serie</th>
                        <!--th>Descripción</th-->
                        <th>Estado</th>
                        <th>Asignado a</th>
                        <th>Ubicación</th>
                        <th>Año</th>
                        <td></td>
                    </tr>

                </tfoot>
            </table>
           
        </div>
    </div>
</div>

<br>

<style>
.btn-primary {
    margin: 10px;
    margin-top: 15px;
    color: #fff;
    background-color: #FF9933;
    border-color: #FF9933;
}
</style>

<?php if($allowI) : ?>

<?= $this->Html->link(__('Insertar Activo'), ['action' => 'add'] ,['class' => 'btn btn-primary']) ?>

<?= $this->Html->link(__('Insertar Activos por Lote'), ['action' => 'batch'] ,['class' => 'btn btn-primary']) ?>

<?php endif; ?>

<script type="text/javascript">

    $(document).ready(function() {
        var table = $('#assets-grid').DataTable( {
          dom: 'Bfrtip',
                buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
                ]
            } );
        // Setup - add a text input to each footer cell
        $('#assets-grid tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="&#xF002; '+title+'" style="font-family:Arial, FontAwesome" />' );
        } );

        // DataTable
       // var table = $('#assets-grid').DataTable();

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
    }
    );


</script>
