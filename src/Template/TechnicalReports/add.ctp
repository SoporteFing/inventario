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

        .sameLine{
          display: flex; 
          justify-content: space-between; 
          border-color: transparent;
        }
        
  </style>

</head>


<body>
<div class="locations form large-9 medium-8 columns content">
  <?= $this->Form->create($technicalReport,['onsubmit'=>'return validateCheck()']) ?>
  <fieldset>
    <legend><?= __('Insertar informe técnico') ?></legend>
    <br>
    

    <div class="form-control sameLine" >

      <div class="row">
          <label required="required"><b>Nº Reporte:</b><font color="red"> * </font></label>
          <label><?php echo h($CompleteID); ?></label>
      </div>
      
      <div class="row">
        <label>Fecha:</label>
        <?php
        echo $this->Form->imput('date', ['class'=>'form-control ','id'=>'datepicker']); 
        ?>        
      </div>

        
    </div>
    
    <label required="required"><b>Placa del activo:</b><font color="red"> * </font></label>
    <div class='input-group mb-3'>
        
          <?php 
            echo $this->form->imput('assets_id',['class'=>'form-control col-sm-3', 'id'=>'assetImput'])
          ?>
          <div class= 'input-group-append'>
          <?php echo $this->Html->link('Buscar','#',['type'=>'button','class'=>'btn btn-default','id'=>'assetButton','onclick'=>'return false']);
          ?>
          </div>
          <br>
          

    </div>
    <div id=assetResult>
    <p id="errorMsg" style="color: red;"></p> 
    </div><br>
   
    

    <div>
      <label required="required"><b>Evaluación:</b><font color="red"> * </font></label>

      <?php 
        echo $this->Form->textarea('evaluation', ['label' => 'Evaluación:', 'class'=>'form-control col-md-8']);
      ?>
    </div>
    <br>

    <div>
      <label required="required"><b>Recomendación:</b><font color="red"> * </font></label>
      <br>
      <?php
       echo $this->Form->radio('recommendation',
          [
           ['value'=>'C', 'text'=>'Reubicar  '],
           ['value'=>'R', 'text'=>'Reparar  '],
           ['value'=>'D', 'text'=>'Desechar  '],
           ['value'=>'U', 'text'=>'Usar piezas  '],
           ['value'=>'O', 'text'=>'Otros'],
          ]);
      ?>
    </div> 
    <br>

    <div class="row col-md-8"> 
      <label>Nombre del Técnico Especializado: </label>
        <?php
        echo $this->Form->imput('evaluator_name', ['class'=>'form-control col-md-5']); 
        ?>   
    </div>
    <br><br>


    <div>
      <label>Nota * : El número de reporte es autogenerado.</label>

    </div>
    <br>

  </fieldset>


</div>
  <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
  <?= $this->Form->button(__('Siguiente'), ['class' => 'btn btn-primary','id'=>'Aceptar']) ?>
  </form>
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
                    alert("Ocurrió un error: Activo no encontrado.");
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
  // funcion para validad que se escocje un activo para el informe
  function validateCheck() {
    var search, error;

    // Get the value of the input field with id="numb"
    search = document.getElementById('assetImput').value;
    
    //If x is Not a Number or less than one or greater than 10
    if ( search.length == 0 ) {
        error = "Seleccione un activo para insertar el informe.";
        document.getElementById("errorMsg").innerHTML = error;
        return false;
    } else {
        return true;
    }
  }

</script>