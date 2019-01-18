  <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <label>Nº placa:</label>
            <?php echo '<input type="text" class="form-control" readonly="readonly" name="plaque" value="' . htmlspecialchars($serchedAsset->plaque) . '">'; ?> 
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 offset-md-3 col-lg-4 offset-lg-3">
            <label>Marca: </label>
            <?php echo '<input type="text" class="form-control" readonly="readonly" name="brand" value="' . htmlspecialchars($serchedAsset->brand). '">'; ?> 
        </div>
  </div>

  <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <label >Nº serie: </label>
            <?php echo '<input type="text" class="form-control" readonly="readonly" name="series" value="' . htmlspecialchars($serchedAsset->series) . '">'; ?> 
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 offset-md-3 col-lg-4 offset-lg-3">
            <label>Modelo:  </label>
            <?php echo '<input type="text" class="form-control" readonly="readonly" name="model" name="fecha" value="' . htmlspecialchars($serchedAsset->model). '">'; ?> 
        </div>
  </div>
  
  <div>
      <label>Descripción:</label><br>
      <textarea class="form-control col-md-8" readonly="readonly" rows="3" cols="10"><?= h($serchedAsset->description);?></textarea>     
  </div>