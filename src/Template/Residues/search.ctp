<style>
    input[name=plaque]{
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
</style>

  <div class="row">
        <div class="col-md-6">
          <div class='input-group mb-3'>
            <label>Nº placa:</label>
            <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" name="plaque" value="' . htmlspecialchars($serchedAsset->plaque) . '">'; ?> 
          </div>
        </div>

        <div class="col-md-6">
          <div class='row'>
          <label>Marca:  </label>
          <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" name="brand" value="' . htmlspecialchars($serchedAsset->brand). '">'; ?> 
          </div>
        </div>
  </div>

  <div class="row">
        <div class="col-md-6">
          <div class='input-group mb-3'>
            <label >Nº serie:</label>
            <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" name="series" value="' . htmlspecialchars($serchedAsset->series) . '">'; ?> 
          </div>
        </div>

        <div class="col-md-6">
          <div class='row'>
          <label>Modelo:  </label>
          <?php echo '<input type="text" class="form-control col-sm-6" readonly="readonly" name="model" name="fecha" value="' . htmlspecialchars($serchedAsset->model). '">'; ?> 
          </div>
        </div>
  </div>
  <div>
      <label>Descripción:</label><br>
          <textarea class="form-control col-md-8" readonly="readonly" rows="3" cols="10"><?= h($serchedAsset->description);?></textarea>     
</div>