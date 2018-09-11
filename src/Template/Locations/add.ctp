<div class="locations form large-9 medium-8 columns content">
    <?= $this->Form->create($location) ?>
    <fieldset>
        <legend><?= __('Insertar Ubicación') ?></legend><br>
            <div class='row'>
                <label class='align'> <b> Ubicación: </b> <font color="red"> * </font> </label><br>
                <?php 
                echo $this->Form->imput('nombre', ['label' => 'Nombre:', 'class'=>'form-control col-sm-2']);
                ?>
            </div><br>
            <div class='row'>
                <label class='align'>Descripción:</label><br>
                <?php 
                echo $this->Form->textarea('description', ['label' => 'Descripción:', 'class'=>'form-control col-sm-4']);
                ?>
            </div><br>
    </fieldset>

    <style>
        .btn-primary {
          color: #fff;
          background-color: #0099FF;
          border-color: #0099FF;
          float: right;
          margin-left: 10px;
        }

        label {
          width: 100px;
        }

        label[class=align] {
          margin-left: 15px;
        }
    </style>
  </div>
    <?= $this->Html->link(__('Cancelar'), ['controller' => 'Locations', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->end() ?>

