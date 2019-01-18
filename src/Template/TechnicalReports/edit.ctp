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
          width:120px;
          margin-left: 10px;
        }

        input[id=internalCompoundId]{
          width:160px;
          margin-left: 10px;
        }
        
        input[name=plaque]{
          width: 110px;
          margin-left: 10px;
        }
        input[name=brand]{
          margin-left: 19px;
        }
        input[name=series]{
          margin-left: 13px;
        }
        input[name=model]{
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
  <?= $this->Form->create($technicalReport,['type' => 'file']) ?>
  <fieldset>
    <legend><?= __('Editar informe técnico') ?></legend>
    <?php echo '<input type="hidden" name="technical_report_id" id="technical_report_id" value="'.$technicalReport->technical_report_id.'" >'; ?>
    <br>
    
    <div class="form-control sameLine">

      <div class="row">
          <label>Nº Reporte:</label>
          <?php echo '<input id= "internalCompoundId"  disabled class="form-control" value="'.$internalID.'">'; ?>       
        </div>

      <div class="row">
        <label required="required"><b>Fecha:</b><font color="red"> * </font></label>
        <?php
        $tmpDate= $technicalReport->date->format('Y-m-d');
        echo $this->Form->imput('date', ['class'=>'form-control ','id'=>'datepicker','value'=>$tmpDate]); 
        ?>
      </div>
  </div>
  <br>
    

    <label required="required"><b>Placa del activo:</b><font color="red"> * </font></label>
    <div class='input-group mb-3'>
        
          <?php 
            echo $this->form->imput('assets_id',['class'=>'form-control col-sm-3', 'id'=>'assetImput','value'=>$technicalReport->assets_id,'required'=>'required'])
          ?>
          <div class= 'input-group-append'>
          <?php echo $this->Html->link('Buscar','#',['type'=>'button','class'=>'btn btn-default','id'=>'assetButton','onclick'=>'return false']);
          ?>
          </div>
          <br>
          

    </div>
    <div id=assetResult>
        <div class="row">
          <div class="col-md-6">
            <div class='input-group mb-3'>
              <label>Nº placa:</label>
              <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" name="plaque" value="' . htmlspecialchars($assets->plaque) . '">'; ?> 
            </div>
          </div>

          <div class="col-md-6">
            <div class='row'>
            <label>Marca:  </label>
            <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" name="brand" value="' . htmlspecialchars($assets->brand). '">'; ?> 
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class='input-group mb-3'>
              <label >Nº serie:</label>
              <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" name="series" value="' . htmlspecialchars($assets->series) .  '">'; ?> 
            </div>
          </div>

          <div class="col-md-6">
            <div class='row'>
            <label>Modelo:  </label>
            <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" name="model" name="fecha" value="' . htmlspecialchars($assets->model). '">'; ?> 
            </div>
          </div>
        </div>

        <div>
          <label>Descripción:</label><br>
            <textarea class="form-control col-md-8" readonly="readonly" rows="3" cols="10"><?= h($assets->description);?></textarea>     
        </div>

    </div><br>
   
    

    <div>
      <label required="required"><b>Evaluación:</b><font color="red"> * </font></label>
      <?php 
        echo $this->Form->textarea('evaluation', ['label' =>['text'=>'Evaluación:'],'id'=>'evaluation','class'=>'form-control col-md-8']);
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
          <label>Nombre del Técnico Especializado:</label>
            <?php
              echo $this->Form->imput('evaluator_name', ['class'=>'form-control col-md-5 ', 'id'=>'evaluator_name']); 
            ?>
      
    </div>
    <br>
    <div class='form-control' style="border-color: transparent;">
        
         <?php
          if($technicalReport->file_name == ''){
              echo "<b>1- Descargar el reporte para llenar y luego subirlo al sitema.</b>";
              echo "<br><br><br>";
              echo "<div >";
              echo "<b>";
              echo $this->Form->input('file_name',['type' => 'file','label' => '2- Subir Reporte Técnico una vez llena para Finalizar', 'class' => 'form-control-file']);
              echo "</b>";
              echo "</div>";
              echo "<div class=\"col-12 text-right\">";

          }
         
        ?>


    </div>
    <br>

  </fieldset>


</div>
  <div>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->button(__('Aceptar'), ['class' => 'btn btn-primary']) ?>
  </form>
  </div>

        <!-- Creo un nuevo from para hacer el post hacia el método de descarga
            Además hay 2 inputs hidden para poder colocar ahí los datos actualues de la vista.
        -->
        
        <?= $this->Form->create(null,['type'=>'post',
                                        'url'=>'/technical-reports/download/'.$technicalReport->technical_report_id
                                    ]) ?>
        <!-- input donde coloco todo los datos de los demás imput exepto el input checkList -->
        <input type="hidden" name="pdf" id="pdf">

        <?= $this->Form->button(__('Descargar PDF'), ['class' => 'btn btn-primary', 'id'=>'generate','style'=>'float:left;']) ?>
        </form>


</body>

<script>
  $( function Picker() {
    $( "#datepicker" ).datepicker({ 
          dateFormat: 'y-mm-dd',
          monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
          dayNamesMin: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'] });
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

  //  Funcion para meter todos los datos en el input pdf para posteriormente 
    //usar los datos en el método download del controlador
    $("document").ready(
    function() {
      $('#generate').click( function()
      {
        //concateno todos los valores
        var res = document.getElementById('technical_report_id').value;
        res=res+","+document.getElementById('datepicker').value;
        res=res+","+document.getElementById('assetImput').value;
        res=res+","+document.getElementById('evaluation').value;
        res=res+","+translateRecomendatio();
        res=res+","+document.getElementById('evaluator_name').value;
        $('#pdf').val(res);
        });
        }
    );

    // FUncion para traducir el valor de radiobutton a hilera
    function translateRecomendatio() {
    /** esta parte del codigo en base a la respuesta de  https://stackoverflow.com/questions/9618504/how-to-get-the-selected-radio-button-s-value , jbabey */
    var radios = document.getElementsByName('recommendation');
    var i = 0, length = radios.length;
    var stop = false;
    var ret;
    while ( i < length && !stop) {
      if (radios[i].checked) {

          ret= radios[i].value;
      stop= true;
      }
        i++
    }
    return ret;
    
}
</script>