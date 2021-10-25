<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Dompdf\Dompdf;
use Cake\Datasource\ConnectionManager;


/**
* Controlador para los préstamos de la aplicación
*/
class LoansController extends AppController
{
    public function isAuthorized($user)
    {

        $this->Roles = $this->loadModel('Roles');
        $this->Permissions = $this->loadModel('Permissions');
        $this->RolesPermissions = $this->loadModel('RolesPermissions');

        $allowI = false;
        $allowM = false;
        $allowE = false;
        $allowC = false;
        
        $query = $this->Roles->find('all', array(
                    'conditions' => array(
                        'id' => $user['id_rol']
                    )
                ))->contain(['Permissions']);

        foreach ($query as $roles) {
            $rls = $roles['permissions'];

            foreach ($rls as $item){
                //$permisos[(int)$item['id']] = 1;
                if($item['nombre'] == 'Insertar Prestamos'){
                    $allowI = true;
                }else if($item['nombre'] == 'Modificar Prestamos'){
                    $allowM = true;
                }else if($item['nombre'] == 'Eliminar Prestamos'){
                    $allowE = true;
                }else if($item['nombre'] == 'Consultar Prestamos'){
                    $allowC = true;
                }

            }
        } 

        $this->set('allowI',$allowI);
        $this->set('allowM',$allowM);
        $this->set('allowE',$allowE);
        $this->set('allowC',$allowC);


        if ($this->request->getParam('action') == 'add'){
            return $allowI;
        }else if($this->request->getParam('action') == 'edit'){
            return $allowM;
        }else if($this->request->getParam('action') == 'delete'){
            return $allowE;
        }else if($this->request->getParam('action') == 'view'){
            return $allowC;
        }else{
            return $allowC;
        }


    }

    /**
     * Método para desplegar una lista con un resumen de los datos de prestamos
     */
    public function index()
    {

        $this->paginate = [
            'contain' => ['Users']
        ];

        $loans = $this->paginate($this->Loans);

        $this->set(compact('loans'));
    }

    /**
     * Método para ver los datos completos de un activo
     */
    public function view($id = null)
    {


        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->loadModel('Assets');

            $loan = $this->Loans->get($id, [
            'contain' => []
            ]);
            

            //$loan = $this->Loans->patchEntity($loan, $this->request->getData());
            
            $loan->file_solicitud = $this->request->getData()['file_solicitud'];
            //print_r($loan);
            //die();

            
            if ($this->Loans->save($loan)){
                

                $this->Flash->success(__('Archivo subido correctamente.'));
                return $this->redirect(['action' => 'view', $loan->id]);

            }
            else{
                $this->Flash->error(__('Error al subir el archivo'));
                return $this->redirect(['action' => 'view', $loan->id]);
            }
            $assets = $this->Loans->Assets->find('list');
            $users = $this->Loans->Users->find('list', ['limit' => PHP_INT_MAX ]);
            $this->set(compact('assets', 'loan', 'users'));


        }
        

        $loan = $this->Loans->get($id, [
            'contain' => ['Users']
        ]);
        $this->loadModel('Assets');
        

        $result = $this->Assets->find()          
        ->select([
            'Assets.plaque',
            'Types.name',
            'Models.name',
            'Assets.series',
            'Assets.state',
            'Brands.name',
        ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'LEFT',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'LEFT',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'LEFT',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->where(['Assets.loan_id' => $id])
            ;
 
        $this->set(compact('loan', 'result'));
    }

    /**
     * Método para agregar nuevos activos al sistema
     */
    public function add()
    {
        $this->loadModel('Assets');

        $loan = $this->Loans->newEntity();
        if ($this->request->is('post')) {
            $listaPlaques = $this->request->getData('checkList');
            
            $listaPlaques = array_filter( explode(",", $listaPlaques ));

            $random = uniqid();
            $loan->id = $random;
            $loan->estado = 'En proceso';
            $loan = $this->Loans->patchEntity($loan, $this->request->getData());

            //debug();
            //die();

            if(!empty($listaPlaques)){

                if ($this->Loans->save($loan)) {
                    
                    foreach($listaPlaques as $plaque){
                        
                        $asset= $this->Assets->get($plaque, [
                            'contain' => []
                        ]);
                        
                        $asset->loan_id = $random;
                        $asset->state = 'Prestado';
                        $asset->deletable = false;
                        
                        if(!($this->Assets->save($asset))){
                            debug('didnt save asset');
                            AppController::insertLog($loan['id'], FALSE);
                            $this->Flash->error(__('El préstamo no se pudo guardar. Uno de los activos no se pudo guardar correctamente'));
                            $this->Loans->delete($loan);
                            return $this->redirect(['action' => 'index']);
                        }
                    }
                    debug('success');
                    AppController::insertLog($loan['id'], TRUE);
                    $this->Flash->success(__('Verifique la información del préstamo y suba el archivo firmado para finalizar'));
                    return $this->redirect(['action' => 'finalizar', $loan->id]);
                }

                $this->Flash->error(__('El préstamo no se pudo guardar, por favor intente nuevamente.'));
                return $this->redirect(['action' => 'index']);    

            }else{

                $this->Flash->error(__('El préstamo no se pudo guardar. Debe seleccionar al menos un activo.'));
                

            }

        
            
            
        }
        

        $this->loadModel('Assets');

        $query = $this->Assets->find('all')
                        ->select(['Assets.plaque', 'Assets.models_id', 'Assets.series'])
                        ->where(['Assets.state' => "Disponible"])
                        ->where(['Assets.lendable' => true])
                        ->where(['Assets.deleted' => false])
                        ->toList();

        $size = count($query);

        $result = array_fill(0, $size, NULL);
        
        for($i = 0; $i < $size; $i++)
        {
            $result[$i] =(object)$query[$i]->assets;
        }


        $users = $this->Assets->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);


        //Buscca los activos para cargarlos en el grid.

        $this->loadModel('Assets');


        if(!empty($check)){

            $asset_old = $this->Assets->find()          
                ->select([
                    'Assets.plaque',
                    'Types.name',
                    'Models.name',
                    'Assets.series',
                    'Assets.state',
                    'Brands.name',
                ])
                ->join([
            'table' => 'types',
            'alias' => 'Types',
            'type' => 'LEFT',
            'conditions' => 'Assets.type_id = Types.type_id',
                ])
                ->join([
            'table' => 'models',
            'alias' => 'Models',
            'type' => 'LEFT',
            'conditions' => 'Assets.models_id = Models.id',
                ])
                ->join([
            'table' => 'brands',
            'alias' => 'Brands',
            'type' => 'LEFT',
            'conditions' => 'Assets.brand = Brands.id',
                ])
                ->where(['Assets.plaque IN' => $check]);
                ;

            $this->set('asset_old', $this->paginate($asset_old));

            $asset = $this->Assets->find()          
            ->select([
                'Assets.plaque',
                'Types.name',
                'Models.name',
                'Assets.series',
                'Assets.state',
                'Brands.name',
            ])
                ->join([
            'table' => 'types',
            'alias' => 'Types',
            'type' => 'LEFT',
            'conditions' => 'Assets.type_id = Types.type_id',
                ])
                ->join([
            'table' => 'models',
            'alias' => 'Models',
            'type' => 'LEFT',
            'conditions' => 'Assets.models_id = Models.id',
                ])
                ->join([
            'table' => 'brands',
            'alias' => 'Brands',
            'type' => 'LEFT',
            'conditions' => 'Assets.brand = Brands.id',
                ])
                ->where(['Assets.state' => 'Disponible'])
                ->where(['Assets.plaque NOT IN' => $check])
                ;



        }else{

            $asset = $this->Assets->find()          
            ->select([
                'Assets.plaque',
                'Types.name',
                'Models.name',
                'Assets.series',
                'Assets.state',
                'Brands.name',
            ])
                ->join([
            'table' => 'types',
            'alias' => 'Types',
            'type' => 'LEFT',
            'conditions' => 'Assets.type_id = Types.type_id',
                ])
                ->join([
            'table' => 'models',
            'alias' => 'Models',
            'type' => 'LEFT',
            'conditions' => 'Assets.models_id = Models.id',
                ])
                ->join([
            'table' => 'brands',
            'alias' => 'Brands',
            'type' => 'LEFT',
            'conditions' => 'Assets.brand = Brands.id',
                ])
                ->where(['Assets.state' => 'Disponible'])
                ;



        }


        $this->set(compact('asset', 'loan', 'users'));


    }

    /*Segundo paso para ingresar prestamo
    public function finalizar($id)
    {
        $loan = $this->Loans->get($id, [
            'contain' => ['Users']
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $loan->estado = "Activo";
            $loan = $this->Loans->patchEntity($loan, $this->request->getData());

            if ($this->Loans->save($loan)) {
                $loan = $this->Loans->get($id, [
                    'contain' => ['Users']
                ]);
                
                $this->Flash->success(__('El préstamo fue creado exitosamente.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('El préstamo no se pudo finalizar, por favor intente nuevamente.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->loadModel('Assets');
        $result = $this->Assets->find()          
        ->select([
            'Assets.plaque',
            'Types.name',
            'Models.name',
            'Assets.series',
            'Assets.state',
            'Brands.name',
        ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'LEFT',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'LEFT',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'LEFT',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->where(['Assets.loan_id' => $id])
            ;
        $this->set(compact('loan', 'result'));
    }

    */

    /*Terminar para varios activos*/
    public function finalizar($id)
    {
        $this->loadModel('Assets');
        $this->loadModel('Users');
        
        $loan = $this->Loans->get($id, [
            'contain' => []
        ]);


        if ($this->request->is(['patch', 'post', 'put'])) {

/*
	    Estas l�neas se encargan de manejar la carga de archivos, cuando lo programaron por primera vez, trataban de meter el archivo en la base y la p�gina se ca�a, lo cual no tiene sentido. Como tampoco se tiene  mucha informaci�n sobre la clases y c�mo se asigna, por lo que se soluciona por PHP.
	    Edit: se agrega c�digo en las l�neas 447 a 456
	    $devolucion =  $this->request->getData()['input file'];  // Esto es lo que hab�a en un princio. Ni sirve ni tiene sentido porque trata de meter el archivo en la base de datos y, evidentemente se trae la p�gina, pero no da error siempre porque la sintaxis está ma y devuelve NULL.
*/
            $direccion = './files/Uploads/Loans/';
	    $loan->file_devolucion = $loan->id . '.' . $_FILES['file_devolucion']['name'];
	    $loan->file_devolucion_dir = $direccion;
	    $ubicacion_final = $direccion . ($loan->id . '.' . $_FILES['file_devolucion']['name']);
	    move_uploaded_file(($_FILES['file_devolucion']['tmp_name']), $ubicacion_final);
	    // $this->Flash->success(__('Error, s�lo se pueden subir archivos pdf.'));

            $loan->estado = print_r($Users); // 'Terminado';
            $loan->fecha_devolucion = date('y-m-d', time());
     
            if ($this->Loans->save($loan)){

              
                $assets = $this->Assets->find()
                ->where(['Assets.loan_id' => $id])
                ->toList();
                    
                foreach($assets as $asset){
                    $asset->state = 'Disponible';
                    $asset->loan_id = NULL;

                    if(!($this->Assets->save($asset))){
                        $this->Flash->error(__('Error al terminar el préstamo'));
                        $this->Loans->delete($loan);
                        return $this->redirect(['action' => 'index']);
                    }
                }

                $this->Flash->success(__('El préstamo ha sido finalizado.'));
                return $this->redirect(['action' => 'index']);

            }
            else{
                $this->Flash->error(__('Error al finalizar el préstamo'));
                return $this->redirect(['action' => 'index']);
            }

        }


        $this->loadModel('Assets');
        $result = $this->Assets->find()          
        ->select([
            'Assets.plaque',
            'Types.name',
            'Models.name',
            'Assets.series',
            'Assets.state',
            'Brands.name',
        ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'LEFT',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'LEFT',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'LEFT',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->where(['Assets.loan_id' => $id])
            ;
        $this->set(compact('loan', 'result'));

    }

    /**
     * Método para obtener todas las placas de activos del sistema y 
     * enviarlas como un JSON para que lo procese AJAX
     */
    public function getPlaques()
    {
        $this->loadModel('Assets');
        if ($this->requrest->is('ajax')) {
            $this->autoRender = false;

            $plaqueRequest = $this->request->query['term'];
            $results = $this->Assets->find($id, [
                'conditions' => [ 'OR' => [
                    'plaque LIKE' => $plaqueRequest . '%',
                    ]
                ]
            ]);
            
            $resultsArr = [];
            
            foreach ($results as $result) {
                $resultsArr[] =['label' => $result['plaque'], 'value' => $result->plaque];
            }
            
            echo json_encode($resultsArr);

        }
    }

    /**
     * Método para enviar la vista parcial de búsqueda de un activo por medio de AJAX
     */
    public function searchAsset()
    {
        $this->loadModel('Assets');
        $id = $_GET['id'];
        $searchedAsset= $asset= $this->Assets->get($id, [
                    'contain' => []
                ]);
        if(empty($searchedAsset) )
        {
            throw new NotFoundException(__('Activo no encontrado') );      
        }
        $this->set('serchedAsset', $searchedAsset);

        /*Asocia esta función a la vista /Templates/Layout/searchAsset.ctp*/
        $this->render('/Layout/searchAsset');
    }

    /**
    * M�todo para obtener el nombre de alguien a partir del id
    */
    public function Id_to_name($id = null)
    {
    $this->loadModel('Users');
    $this->loadModel('Loans');
    echo $id;

    }



    /**
     * Método para generar formulario
     */


    public function download($id = null)
    {

        $this->loadModel('Assets');
        

        $this->Assets = $this->loadModel('Assets');
        $this->AssetsTransfers = $this->loadModel('AssetsTransfers');

        $results = $this->Assets->find()          
        ->select([
            'Assets.plaque',
            'Types.name',
            'Models.name',
            'Assets.series',
            'Assets.state',
            'Brands.name',
        ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'LEFT',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'LEFT',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'LEFT',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->where(['Assets.loan_id' => $id])
            ;
         require_once 'dompdf/autoload.inc.php';
        //initialize dompdf class
        $document = new Dompdf();
        $html = 
        '
        <style>
        #element1 {float:left;margin-right:10px;} #element2 {float:right;} 
        table, td, th {
            border: 1px solid black;
        }
        body {
            border: 5px double;
            width:100%;
            height:100%;
            display:block;
            overflow:hidden;
            padding:30px 30px 30px 30px
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            height: 50px;
        }
        </style>
<center><img src="/var/www/html/Activos/src/Controller/images/logoucr.png"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <img src="/var/www/html/Activos/src/Controller/images/logofing.png"></center>
<h2 align="center">UNIVERSIDAD DE COSTA RICA</h2>
<h2 align="center">FACULTAD DE INGENIERIA</h2>
<h2 align="center">UNIDAD DE ACTIVOS FIJOS</h2>
<h2 align="center">PRESTAMO DE ACTIVO FIJO</h2>
<p>&nbsp;</p>
<p align="right">Fecha._________________</p>
<p>&nbsp;</p>
<p align="center">Yo:_______________________________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cedula o carne:_______________</p>
<p>En calidad de:</p>
<p>Recibo en calidad de prestamo los equipos que a continuacion se detallan, los cuales estan asignados a:</p>

<table width="0" border="1">
<tbody>
<tr>
<th align="center">Placa</th>
<th align="center">Tipo</th>
<th align="center">Marca</th>
<th align="center">Modelo</th>
<th align="center">Serie</th>

</tr>';

        foreach ($results as $item) {
            $html .= 
            '<tr>
             <td align="center">' . $item->plaque . '</td>
             <td align="center">' . $item->Types['name'] . '</td>
             <td align="center">' . $item->Brands['name'] . '</td>
             <td align="center">' . $item->Models['name'] . '</td>
             <td align="center">' . $item->series . '</td>
             </tr>';
        }


$html .=

'</table>
<p>Acepto las condiciones que establecen los articulos No.13, No.14, No.17 y No.18 del Reglamento para la Administracion y Control de Bienes Institucionales de la U.C.R me compreto a usar el equipo adecuadamente, darle mantenimiento y devolverlo en buen estado según lo acordado con el responsable.</p>
<p align="center"><strong>Recibe:</strong><p>
<p align="center"><strong>Firma:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;______________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha de devolucion: &nbsp;&nbsp;&nbsp;&nbsp;________________</strong></p>
<p>&nbsp;</p>
<p aling="center"> - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </p>
<p><strong>Para uso de la unidad receptora.</p>
<p align="center"><strong>Entrega:</strong><p>
<p align="center">Unidad de custodio:            Facultad de Ingeniería Universidad de Costa Rica<p>
<p align="center">Encargado de Bienes Institucionales:            _______________________<p>
<p align="center">Cedula:            _______________________<p>
<p align="center">Firma:            _______________________<p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><strong>Nota:</strong></p>
<p>El original de este documento sera entregado al solicitante despues de que se haya recibido satisfactoriamente el o los equipos.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center">Tels: 2511-6639 / 2511-4915      www.fing.ucr.ac.cr     Correo electrónico: decanato.fi@ucr.ac.cr</p>
        ';


        $document->loadHtml($html);

        //set page size and orientation
        $document->setPaper('A3', 'portrait');
        //Render the HTML as PDF
        $document->render();

        header( "refresh:5;url=index.php" );
        //Get output of generated pdf in Browser
        $document->stream("Formulario de Prestamo", array("Attachment"=>1));
        //1  = Download
        //0 = Preview

 

    }


}

