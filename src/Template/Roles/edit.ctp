<?php
/**
 * @var \App\View\AppView $this
 * 
 *@var \App\Model\Entity\Role $role
 */
?>

<?php
    $dis = "";
    if($rol->nombre == 'Administrador'){
        $dis = "Disabled";
    }
?>

<div class="roles x large-9 medium-8 columns content">
    <h4>Editar Rol <?php echo $rol->nombre ;?></h4>

    <?php echo $this->Form->create(false, array(
    'url' => array($rol['id'])
    ));
    ?>

    <div class="col-md-4">
        <label> <h5><b>Nombre</b></h5></label>
        <div class="row col-md-12">
          <input type="text" name="nombre" class="form-control col-md-9" id="nombre" value="<?php echo $rol->nombre ;?>" Disabled="<?php echo $dis ;?>">
        </div> 
    </div>

      

    <br>
    
    <div class="col-md-12">
      <h5><b>Permisos</b></h5>

      <br>

      <table class="table">
          <tr>
              <th><h5><?= __('Modulo') ?></h5></th>
              <td><h5><?= __('Insertar') ?></h5></td>
              <td><h5><?= __('Modificar') ?></h5></td>
              <td><h5><?= __('Eliminar') ?></h5></td>
              <td><h5><?= __('Consultar') ?></h5></td>
          </tr>
          
        <tr>
              <th><h5><?= __('Usuarios') ?></h5></th>

          <?php 
            for ($x = 1; $x < 5; $x++) {
              if ($permisos[$x] == 1) {
                echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'checked'=> true, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              } else {
               echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              }
            } 
          ?>

        </tr>
          
        <tr>
              <th><h5><?= __('Activos') ?></h5></th>
              

          <?php 
            for ($x = 5; $x < 9; $x++) {
              if ($permisos[$x] == 1) {
                echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'checked'=> true, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              } else {
               echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              }
            } 
          ?>


        </tr>

        <tr>
              <th><h5><?= __('Reporte Tecnico') ?></h5></th>
              
          <?php 
            for ($x = 9; $x < 13; $x++) {
              if ($permisos[$x] == 1) {
                echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'checked'=> true, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              } else {
               echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              }
            } 
          ?>



          </tr>
          

          
          <tr>
              <th><h5><?= __('Prestamos') ?></h5></th>
              
          <?php 
            for ($x = 13; $x < 17; $x++) {
              if ($permisos[$x] == 1) {
                echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'checked'=> true, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              } else {
               echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              }
            } 
          ?>


          </tr>
          
          <tr>
              <th><h5><?= __('Traslados') ?></h5></th>
              
          <?php 
            for ($x = 17; $x < 21; $x++) {
              if ($permisos[$x] == 1) {
                echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'checked'=> true, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              } else {
               echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              }
            } 
          ?>


          <tr>
              <th><h5><?= __('Desechos') ?></h5></th>
              
          <?php 
            for ($x = 21; $x < 25; $x++) {
              if ($permisos[$x] == 1) {
                echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'checked'=> true, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              } else {
               echo "<td>";
                echo $this->Form->input('', array( 'type'=>'checkbox','id' => $x, 'name' => $x, 'format' => array('before', 'input', 'between', 'label', 'after', 'error' ), $dis));
                echo "</td>";
              }
            } 
          ?>


          </tr>

          </tr>



      </table>

    </div>
    <?= $this->Form->button(__('Guardar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['controller' => 'Roles', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>

    <?= $this->Form->end() ?>

</div>

<script type="text/javascript">

    $("input[type=checkbox]").change(function() {
        if(this.checked && !(this.id % 4 == 0)) {
          var consultar = (parseInt(this.id / 4) + 1) * 4;
          $("#" + consultar).prop("checked", true );
        }
        if(!this.checked && this.id % 4 == 0) {
          console.log(this.id);
          $("#" + (this.id - 1)).prop("checked", false );
          $("#" + (this.id - 2)).prop("checked", false );
          $("#" + (this.id - 3)).prop("checked", false );
        }
    });
    



</script>