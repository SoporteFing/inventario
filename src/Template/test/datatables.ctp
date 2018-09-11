
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <?= $this->Html->meta('icon') ?>
    <title>CakePHP 3 DataTables</title>
    <?= $this->Html->css([ 'bootstrap.min', 'datatables-extensions/dataTables.bootstrap.min', 'style' ]) ?>
    <?=
        $this->Html->script([ 'jquery-1.12.3','bootstrap.min', 'jquery.dataTables.min',
                             'datatables-extensions/dataTables.bootstrap.min'
                            ])
    ?>
</head>

<body>


    <div class="container">
       <?= $this->Flash->render() ?>
       <div class="clearfix"></div>
       <?= $this->fetch('content') ?>
    </div>
    <!-- /container -->
</body>

</html>
