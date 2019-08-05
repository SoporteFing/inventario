<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Dompdf\Dompdf;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Date;

/**
 * Transfers Controller
 *
 * @property \App\Model\Table\TransfersTable $Transfers
 *
 * @method \App\Model\Entity\Transfer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransfersController extends AppController
{


    private $UnidadAcademica='Ingeniería';
    private $Acronimo = 'IN';


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
                if($item['nombre'] == 'Insertar Traslados'){
                    $allowI = true;
                }else if($item['nombre'] == 'Modificar Traslados'){
                    $allowM = true;
                }else if($item['nombre'] == 'Eliminar Traslados'){
                    $allowE = true;
                }else if($item['nombre'] == 'Consultar Traslados'){
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
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {

        $this->loadModel('Transfers');

        $transfers = $this->Transfers->find()
            ->select([
                'Transfers.transfers_id',
                'Transfers.file_name',
                'Transfers.date',
                'Transfers.Acade_Unit_recib',
                'asset_list' => 'group_concat(Assets_Transfers.assets_id)'
            ])
            ->join([
        'table'=>'assets_transfers',
        'alias' => 'Assets_Transfers',
        'type'=>'INNER',
        'conditions'=> 'Transfers.transfers_id = Assets_Transfers.transfer_id'
            ])
            ->group('Transfers.transfers_id');
            ;



            $this->set('transfers', $this->paginate($transfers));

    /*
        ORIGINAL

        $transfers = $this->paginate($this->Transfers);

        $this->set(compact('transfers'));
    
    */

    }

    /**
     * View method
     *
     * @param string|null $id Transfer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        $transfer = $this->Transfers->get($id);

        // obtengo la tabla assets
        $assets_transfers = TableRegistry::get('AssetsTransfers');

        $this->loadModel('Assets');

        $result = $this->Assets->find()
            ->select([
                'Assets.plaque',
                'Types.name',
                'Models.name',
                'Assets.series',
                'Assets_Transfers.transfers_state',
                'Brands.name',
            ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'INNER',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'INNER',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'INNER',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->join([
        'table'=>'assets_transfers',
        'alias' => 'Assets_Transfers',
        'type'=>'INNER',
        'conditions'=> 'Assets.plaque= Assets_Transfers.assets_id'
            ])
            ->where(['Assets_Transfers.transfer_id'=>$id])
            ;




        // reallizo un join  a assets_tranfers para obtener los activos
        //asosiados a un traslado
        /*
        $query = $assets_transfers->find()
                    ->select(['assets.plaque','brands.name','models.name','assets.series','assets.state'])
                    ->join([
                      'assets'=> [
                        'table'=>'assets',
                        'type'=>'INNER',
                        'conditions'=> [ 'assets.plaque= AssetsTransfers.assets_id']
                        ]
                    ])
                    ->join([
                            'models' => [
                                    'table' => 'models',
                                    'type'  => 'LEFT',
                                    'conditions' => ['assets.models_id= models.id']
                                ]
                                ])
                    ->join([
                            'brands' => [
                                    'table' => 'brands',
                                    'type'  => 'LEFT',
                                   'conditions' => ['Assets.brand = brands.id']
                                ]
                    ])
                    ->where(['AssetsTransfers.transfer_id'=>$id])
                    ->toList();

        
        $size = count($query);
        $result=   array_fill(0, $size, NULL);
        
        for($i=0;$i<$size;$i++)
        {
            //* se acomodan los valores dentro de un mismo [$i]
            $result[$i]['plaque']= $query[$i]->assets['plaque'];
            $result[$i]['brand']= $query[$i]->brands['name'];
            $result[$i]['model']= $query[$i]->models['name'];
            $result[$i]['series']= $query[$i]->assets['series'];
            $result[$i]['state']= $query[$i]->assets['state'];

            // se realiza una conversion a objeto para que la vista lo use sin problemas
            $result[$i]= (object)$result[$i];

        }
        //$user =$this->Auth->user();
        

        */
        $Unidad= $this->UnidadAcademica;

        $this->set(compact('transfer','result','Unidad'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        //empieza el área para la función de post///////////////
        $transfer = $this->Transfers->newEntity();

        $check = array();
        $states = array();
        
        if ($this->request->is('post')) {

            $check = $this->request->getData("checkList");
            $check = explode(",",$check);

            if($this->request->getData("checkList") == '') {
                 AppController::insertLog($transfer['transfers_id'], TRUE);
                $this->Flash->error(__('Debe ingresar por lo menos un Activo para trasladar.'));

            }else{

                $states = $this->request->getData("statesList");
                $states = explode(",",$states);

                $transfer = $this->Transfers->patchEntity($transfer, $this->request->getData());

                //Se concatena el id de la vista con la constante en este caso (VRA-) que esdiferente para cada unidad académica
                //$transfer->transfers_id = $this->request->getData('transfers_id');
                $users = TableRegistry::get('users');
                //se obtiene el nombre del usuario con la posición del dropdown
                $functionary = $users->find()
                    ->select(['users.nombre','users.apellido1','users.apellido2'])
                    ->where(['users.id' => $this->request->getData("functionary")])
                    ->first();
                $transfer->functionary = $functionary['nombre'] .' '.$functionary['apellido1'].' '.$functionary['apellido2'];
                $transfer->identification = $this->request->getData("functionary");
     

                //Se verifica que el id no esté duplicado, por alguna razón la base de datos no lo estaba haciendo.

                $returnId = $this->Transfers->find('all')
                ->where([
                'Transfers.transfers_id' => $transfer->transfers_id
                ])
                ->first();
                
                if($returnId ){
                    $transfer->setError('transfers_id', ['El número de traslado ya existe.']);
                }else if($transfer->transfers_id == ''){
                    $transfer->setError('transfers_id', ['Debe tener un número de traslado.']);
                }else{


                    //Se verifica que todos los activos seleccionados sigan disponibles
                    $this->loadModel('Assets');
                    $not_available = $this->Assets->find()          
                        ->select([
                            'Assets.plaque'
                        ])
                        ->where(['Assets.state !=' => 'Disponible'])
                        ->where(['Assets.plaque IN' => $check]);
                        ;


                    if(!empty($not_available->toList())){
                        $this->Flash->error(__('Hay activos que ya no están disponibles.'));
                        
                    }else{


                        $transfer->transfers_id = $this->Acronimo . '-' . $transfer->transfers_id;

                                //comienza el ciclo para agregar la relación entre activos y acta.

                        
                        if($transfer->identification == ''){
                            $transfer->setError(
                                'identification',

                             ['El campo es requerido']

                            );
                        }
                        //  debug($transfer);
                        //die();

                        if ($this->Transfers->save($transfer)) {
                            foreach($check as $index => $placa)
                            {
                                $transferAssetTable = TableRegistry::get('AssetsTransfers');
                                $transferAsset = $transferAssetTable->newEntity();
                                //se asigna id de traslado a tabla de relación
                                $transferAsset->transfer_id =  $transfer->transfers_id;
                                $transferAsset->assets_id = $placa;
                                $transferAsset->transfers_state = $states[$index];
                                //se guarda en tabla conjunta (assets y traslado)
                                $transferAssetTable->save($transferAsset);

                                //Se le cambia el estado al activo.
                                $assets = TableRegistry::get('Assets')->find('all');
                                    
                                $assets->update()
                                        ->set(['state' => "Trasladado"])
                                        ->where(['plaque IN' => $placa])
                                        ->execute();
                            }
                            $this->Flash->success(__('El traslado fue exitoso.'));
                            return $this->redirect(['action' => 'viewDownload', $transfer->transfers_id]);
                        }
                        //debug($transfer->errors());
                        AppController::insertLog($transfer['transfers_id'], TRUE);
                        $this->Flash->error(__('No se pudo realizar el traslado.'));
                    }
                }
            }
        }

        // obtengo la tabla assets
        $assets_transfers = TableRegistry::get('AssetsTransfers');


        // reallizo un join  a assets_tranfers para obtener los activos
        //asosiados a un traslado
        $query = $assets_transfers->find()
                    ->select(['assets.plaque'])
                    ->join([
                      'assets'=> [
                        'table'=>'assets',
                        'type'=>'INNER',
                        'conditions'=> [ 'assets.plaque= AssetsTransfers.assets_id']
                        ]
                    ])
                    ->toList();
        // Aqui paso el resultado de $query a un objeto
        $size = count($query);
        $result=   array_fill(0, $size, NULL);
        
        for($i=0;$i<$size;$i++)
        {
            $result[$i] =(object)$query[$i]->assets;
        }

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


/*
        $asset = $this->Assets->find('all', [
            'conditions' => ['Assets.state' => 'Disponible']
        ]);
*/

        /** obtengo una lista de usuarios para cargar un dropdown list en la vista */
        $this->loadModel('Users');
        $users = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);
        // variable para colocar la unidad que entrega
        $paramUnidad = $this->UnidadAcademica;
        $paramAcronimo = $this->Acronimo;
        $this->set(compact('transfer', 'result','tmpId','users','paramUnidad', 'states','paramAcronimo'));
        $this->set('asset', $this->paginate($asset));


        //debug($asset->toList());
        //die();

    }

    /**
     * Edit method
     *
     * @param string|null $id Transfer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transfer = $this->Transfers->get($id);

        $check = array();
        $states = array();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $check = $this->request->getData("checkList");
            $check = explode(",",$check);

            if($this->request->getData("checkList") == '') {
                 AppController::insertLog($transfer['transfers_id'], TRUE);
                $this->Flash->error(__('Debe ingresar por lo menos un Activo para trasladar.'));

            }else{

            //saco la lista de placas señaladas y luego las paso a Array

                $states = $this->request->getData("statesList");
                $states = explode(",",$states);

                 
                $transfer = $this->Transfers->patchEntity($transfer, $this->request->getData());

                if($transfer->transfers_id == ''){
                    $transfer->setError('transfers_id', ['Debe tener un número de traslado.']);
                }else{


                    if ($this->Transfers->save($transfer)) {
                        AppController::insertLog($transfer['transfers_id'], TRUE);
                        $this->Flash->success(__('Los cambios han sido guardados.'));



                        $nuevos = array_diff($checks,  $temp);
                        $viejos = array_diff($temp,  $checks);
                        
                        
                        //debug($nuevos);
                        //debug($viejos);

                        $assets = TableRegistry::get('Assets')->find('all');

                        if (count($viejos) > 0)
                        {

                          $assets_transfers->deleteall(array('transfer_id'=>$id, 'assets_id IN' => $viejos), false);

                          $assets->update()
                            ->set(['state' => "Disponible"])
                            ->where(['plaque IN' => $viejos])
                            ->execute();
                        }

                        if (count($nuevos) > 0)
                        {
                            foreach ($nuevos as $index => $n)
                            {
                                $at = TableRegistry::get('AssetsTransfers')->newEntity([
                                        'transfer_id'=> $id,
                                        'assets_id' => $n,
                                        'Transfers_state' => $states[$index]
                                ]);

                                $at->assets_id = $n;
                                $at->transfer_id = $id;
                                $at->transfers_state = $states[$index];
                                
                                $assets_transfers->save($at);
                            }

                            $assets->update()
                            ->set(['state' => "Trasladado"])
                            ->where(['plaque IN' => $nuevos])
                            ->execute();
                        }
                        return $this->redirect(['action' => 'index']);
                    }

                }

                AppController::insertLog($transfer['transfers_id'], FALSE);
                //debug($transfer);
                $this->Flash->error(__('El traslado no se pudo guardar, porfavor intente nuevamente'));
            }
        }

        //No hay post carga listas de activos

        $this->loadModel('Assets');


        $assets = $this->Assets->find()          
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
        'type' => 'INNER',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'INNER',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'INNER',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->where(['Assets.state' => 'Disponible'])
            ;


        /** obtengo una lista de usuarios para cargar un dropdown list en la vista */
        $usersTable= TableRegistry::get('Users');
        $queryUsers = $usersTable->find()
                        ->select(['Users.nombre','Users.apellido1','Users.apellido2'])
                        ->toList();

        $size = count($queryUsers);
        /** obtengo una lista de usuarios para cargar un dropdown list en la vista */
        $this->loadModel('Users');
        $users = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);


        if ($this->request->is(['patch', 'post', 'put'])) {

            $current_assets = $this->Assets->find()          
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
            'type' => 'INNER',
            'conditions' => 'Assets.type_id = Types.type_id',
                ])
                ->join([
            'table' => 'models',
            'alias' => 'Models',
            'type' => 'INNER',
            'conditions' => 'Assets.models_id = Models.id',
                ])
                ->join([
            'table' => 'brands',
            'alias' => 'Brands',
            'type' => 'INNER',
            'conditions' => 'Assets.brand = Brands.id',
                ])
                ->where(['Assets.plaque IN' => $check]);
                ;
        }else{

            $current_assets = $this->Assets->find()
            ->select([
                'Assets.plaque',
                'Types.name',
                'Models.name',
                'Assets.series',
                'Assets_Transfers.transfers_state',
                'Brands.name',
            ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'INNER',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'INNER',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'INNER',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->join([
        'table'=>'assets_transfers',
        'alias' => 'Assets_Transfers',
        'type'=>'INNER',
        'conditions'=> 'Assets.plaque= Assets_Transfers.assets_id'
            ])
            ->where(['Assets_Transfers.transfer_id'=>$id])
            ;

        }



        //debug($asset[0]);
        //die();

        $Unidad= $this->UnidadAcademica;
        $this->set(compact('transfer', 'assets', 'current_assets','Unidad','users','states'));

    }

    /**
     * Delete method
     *
     * @param string|null $id Transfer id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        // Obtengo el transfer que necesito eliminar
        $transfer = $this->Transfers->get($id);
        
        // Con el ID del transfer, obtengo el todos los Transfers_Assets Relacionados al mismo transfer desde la tabla 
        // intermedia Assets_Transfers
        $assets_transfers = TableRegistry::get('AssetsTransfers')->find()->where(['transfer_id' => $transfer->transfers_id]);
        
        // Proceseo para actualizar el estado del activo en la tabla de activos
        
        // Itero sobre cada Asset_Transfer en la variable indTransfer
        foreach ($assets_transfers as $indTransfer) {
                
                // Obtengo el asset ID associado a éste transfer particular
                $assetID = $indTransfer->assets_id;
                
                // Obtengo el asset correspondiente a éste transfer
                $assets = TableRegistry::get('Assets')->find()->where(['plaque' => $assetID]);
                  
                //se actualiza el estado del activo en la tabla de activos
                $assets->update()
                ->set(['state' => "Disponible"])
                ->where(['plaque' => $assetID])
                ->execute();
                
            }    

        if ($this->Transfers->delete($transfer)) {
            AppController::insertLog($transfer['transfers_id'], TRUE);
            $this->Flash->success(__('El traslado ha sido eliminado.'));
        } else {
            AppController::insertLog($transfer['transfers_id'], FALSE);
            $this->Flash->error(__('El traslado no pudo ser eliminado. Por favor, intente de nuevo.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function viewDownload($id = null)
    {

        $transfer = $this->Transfers->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {


            //saco la lista de placas señaladas y luego las paso a Array
            //debug($this->request->getData());
            //die();        
            
            $transfer = $this->Transfers->patchEntity($transfer, $this->request->getData());

            if ($this->Transfers->save($transfer)) {
                AppController::insertLog($transfer['transfers_id'], TRUE);
                $this->Flash->success(__('Los cambios han sido guardados.'));

                return $this->redirect(['action' => 'index']);
            }
        }
        // obtengo la tabla assets
        $this->loadModel('Assets');

        $result = $this->Assets->find()
            ->select([
                'Assets.plaque',
                'Types.name',
                'Models.name',
                'Assets.series',
                'Assets_Transfers.transfers_state',
                'Brands.name',
            ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'INNER',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'INNER',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'INNER',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->join([
        'table'=>'assets_transfers',
        'alias' => 'Assets_Transfers',
        'type'=>'INNER',
        'conditions'=> 'Assets.plaque= Assets_Transfers.assets_id'
            ])
            ->where(['Assets_Transfers.transfer_id'=>$id])
            ;
        
        $Unidad= $this->UnidadAcademica;

        $this->set(compact('transfer','result','Unidad'));
    }



/** funcion que genera el pdf con los datos actuales de la vista de editar
*/
public function download($id = null)
    {
        /*
        $this->Assets = $this->loadModel('Assets');
        $this->AssetsTransfers = $this->loadModel('AssetsTransfers');
        */

        // se crea una entidad para luego poder hacer los validadores
        $transfer = $this->Transfers->newEntity();
        // Esta variable es simplemente para contener los datos en una estructura de array
        //que entienda el patchEntity
        $transferTMP;

        // Aqui queda el resultado, en un vector genérico, de lo que contiene la vista
        $transferArray= explode(',',$this->request->data('pdf') );

        debug($this->request->data('pdf'));
        
        //re realiza una relacion 1 a 1
        $transferTMP['residues_id']= $id;
        $date = new Date($transferArray[0]);
        $transferTMP['date']= $date->format('Y-m-d');


        $transferTMP['Acade_Unit_recib']= $transferArray[1];
        $transferTMP['functionary']= $transferArray[2];
        $transferTMP['identification']= $transferArray[3];
        $transferTMP['functionary_recib']= $transferArray[4];
        $transferTMP['identification_recib']= $transferArray[5];

        $transfer = $this->Transfers->patchEntity($transfer,$transferTMP);
        $errors = $transfer->errors();

        if(/*$errors== null && $this->Transfers->save($transfer)*/true)
        {
            // linea para marcar el desecho como descargado, haciendo que ya no se pueda borrar
            $transfer->descargado = true;

            // pide la lista de placas a la vista
            $plaques= explode(',',$this->request->data('plaques') );

            //  las placas se pasan a un formato de string de manera que seaan válidas en
            //el where Assets.plaque in
            $plaqueList;
            $plaqueList.="'".$plaques[0]."'";
            $size=count($plaques);
            for($p=1;$p< $size;$p++)
            {
                $plaqueList.=",'".$plaques[$p]."'";
            }
            /*
            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute("select a.plaque, a.description, b.name as brand, m.name as model, a.series, a.state
            from assets a
            inner join assets_transfers on a.plaque= assets_id
            inner join transfers on transfer_id= transfer_id
            inner join brands b on  b.id=a.brand
            inner join models m on m.id=a.models_id
            where a.plaque in (" . $plaqueList . ");");
        ¨   */
            //MASTER

        $this->loadModel('Assets');

        $results = $this->Assets->find()
            ->select([
                'Assets.plaque',
                'Types.name',
                'Models.name',
                'Assets.series',
                'Assets_Transfers.transfers_state',
                'Brands.name',
            ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'INNER',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'INNER',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'INNER',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->join([
        'table'=>'assets_transfers',
        'alias' => 'Assets_Transfers',
        'type'=>'INNER',
        'conditions'=> 'Assets.plaque= Assets_Transfers.assets_id'
            ])
            ->where(['Assets_Transfers.transfer_id'=>$id])
            ;


            //$results = $stmt ->fetchAll('assoc');


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


            <center><img src="https://botw-pd.s3.amazonaws.com/styles/logo-thumbnail/s3/032011/logo_ucr.png"></center>
            <h2 align="center">Universidad de Costa Rica</h2>
            <h2 align="center">Vicerrector&iacute;a de Administraci&oacute;n</h2>
            <h2 align="center">Oficina de Administraci&oacute;n Financiera</h2>
            <h3 align="center">Unidad de Control de Activos Fijos y Seguros</h3>
            <h2 align="center">FORMULARIO PARA TRASLADO DE ACTIVOS FIJOS</h2>
            <h1>&nbsp;</h1>
            <div id="element1" align="left">  Fecha: '.$transferArray[0].' </div> <div id="element2" align="right"> No.'.$id.' </div> 
            <p align="right">(Lo asigna el usuario)</p>
            <p><strong>&nbsp;</strong></p>

            <table>
            <tr>
                <th align="center"><span style="font-weight:bold">ENTREGA</span></th>
                <th align="center"><span style="font-weight:bold">RECIBE</span></th>
            </tr>
            <tr>
                <td height="50"><strong>Unidad: '.$this->UnidadAcademica.'</td>
                <td height="50"><strong>Unidad: '.$transfer->Acade_Unit_recib.'</td>
            </tr>
            <tr>
                <td height="50"><strong>Nombre del Funcionario: '.$transfer->functionary.'</td>
                <td height="50"><strong>Nombre del Funcionario: '.$transfer->functionary_recib.'</td>
            </tr>
            <tr>
                <td height="75"><strong>Firma:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cedula: '.$transfer->identification.'</strong></td>
                <td height="75"><strong>Firma:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cedula: '.$transfer->identification_recib.'</td>
            </tr>
            </table>

            <h2 align="center">Detalle de los bienes a trasladar</h2>
            <table width="0" border="1">
            <tbody>
            <tr>
            <th align="center">Descripcion del Activo</th>
            <th align="center">Placa</th>
            <th align="center">Tipo</th>
            <th align="center">Marca</th>
            <th align="center">Modelo</th>
            <th align="center">Serie</th>
            <th align="center">Estado Actual</th>
            </tr>';

            foreach ($results as $a) {
                $html .= 
                '<tr>
                <td align="center">' . $a->description . '</td>
                 <td align="center">' . $a->plaque . '</td>
                 <td align="center">' . $a->Types['name'] . '</td>
                 <td align="center">' . $a->Brands['name'] . '</td>
                 <td align="center">' . $a->Models['name'] . '</td>
                 <td align="center">' . $a->series . '</td>
                 <td align="center">' . $a->Assets_Transfers['transfers_state'] . '</td>
                 </tr>';
            }


            $html .=

            '</table>
            <br><br><br>
            <p><strong>Observaciones: </strong></p>
            <p><strong>Nota: El formulario debe estar firmado por el encargado de activos fijos u otro funcionario autorizado en cada unidad.</strong></p>
            <p><strong>Original: Oficina de Administraci&oacute;n Financiera&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copia: Unidad que entrega&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copia: Unidad que recibe</strong></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p align="center">Tels: 2511 5759/1149 </p>      
            <p align="center">www.oaf.ucr.ac.cr</p>      
            <p align="center">correo electrónico: activosfijos.oaf@ucr.ac.cr</p>
            ';


            $document->loadHtml($html);

            //set page size and orientation
            $document->setPaper('A3', 'portrait');
            //Render the HTML as PDF
            $document->render();
            //Get output of generated pdf in Browser
            $document->stream("Formulario de Traslado-".$id, array("Attachment"=>1));
            //1  = Download
            //0 = Preview
        }
        $this->Flash->error(__('El traslado no se ha generado. Existe un error en los campos editables.'));
        return $this->redirect(['action' => 'edit',$id]);

    }


    public function generate($id = null)
    {


        // se crea una entidad para luego poder hacer los validadores
        $transfer = $this->Transfers->get($id);

        $date = new Date($transfer->date);
        $date= $date->format('Y-m-d');

        // linea para marcar el desecho como descargado, haciendo que ya no se pueda borrar
        $transfer->descargado = true;

        /*
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("select a.plaque, a.description, b.name as brand, m.name as model, a.series, a.state
            from assets a
            inner join assets_transfers atr on a.plaque= assets_id
            inner join transfers tr on tr.transfers_id= atr.transfer_id
            inner join brands b on  b.id=a.brand
            inner join models m on m.id=a.models_id
            where tr.transfers_id = '" . $id . "';");
        $results = $stmt ->fetchAll('assoc');

        */

        $this->loadModel('Assets');

        $results = $this->Assets->find()
            ->select([
                'Assets.plaque',
                'Types.name',
                'Models.name',
                'Assets.series',
                'Assets_Transfers.transfers_state',
                'Brands.name',
            ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'INNER',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'INNER',
        'conditions' => 'Assets.models_id = Models.id',
            ])
            ->join([
        'table' => 'brands',
        'alias' => 'Brands',
        'type' => 'INNER',
        'conditions' => 'Assets.brand = Brands.id',
            ])
            ->join([
        'table'=>'assets_transfers',
        'alias' => 'Assets_Transfers',
        'type'=>'INNER',
        'conditions'=> 'Assets.plaque= Assets_Transfers.assets_id'
            ])
            ->where(['Assets_Transfers.transfer_id'=>$id])
            ;


        $size= count($results);
        /*debug($results);
        die();*/
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


            <center><img src="https://botw-pd.s3.amazonaws.com/styles/logo-thumbnail/s3/032011/logo_ucr.png"></center>
            <h2 align="center">Universidad de Costa Rica</h2>
            <h2 align="center">Vicerrector&iacute;a de Administraci&oacute;n</h2>
            <h2 align="center">Oficina de Administraci&oacute;n Financiera</h2>
            <h3 align="center">Unidad de Control de Activos Fijos y Seguros</h3>
            <h2 align="center">FORMULARIO PARA TRASLADO DE ACTIVOS FIJOS</h2>
            <h1>&nbsp;</h1>
            <div id="element1" align="left">  Fecha: '.$date.' </div> <div id="element2" align="right"> No.'.$id.' </div> 
            <p align="right">(Lo asigna el usuario)</p>
            <p><strong>&nbsp;</strong></p>

            <table>
            <tr>
                <th align="center"><span style="font-weight:bold">ENTREGA</span></th>
                <th align="center"><span style="font-weight:bold">RECIBE</span></th>
            </tr>
            <tr>
                <td height="50"><strong>Unidad: '.$this->UnidadAcademica.'</td>
                <td height="50"><strong>Unidad: '.$transfer->Acade_Unit_recib.'</td>
            </tr>
            <tr>
                <td height="50"><strong>Nombre del Funcionario: '.$transfer->functionary.'</td>
                <td height="50"><strong>Nombre del Funcionario: '.$transfer->functionary_recib.'</td>
            </tr>
            <tr>
                <td height="75"><strong>Firma:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cedula: '.$transfer->identification.'</strong></td>
                <td height="75"><strong>Firma:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cedula: '.$transfer->identification_recib.'</td>
            </tr>
            </table>

            <h2 align="center">Detalle de los bienes a trasladar</h2>
            <table width="0" border="1">
            <tbody>
            <tr>
            <th align="center">Placa</th>
            <th align="center">Tipo</th>
            <th align="center">Marca</th>
            <th align="center">Modelo</th>
            <th align="center">Serie</th>
            <th align="center">Estado Actual</th>
            </tr>';


            foreach ($results as $a) {
                $html .= 
                '<tr>
                 <td align="center">' . $a->plaque . '</td>
                 <td align="center">' . $a->Types['name'] . '</td>
                 <td align="center">' . $a->Brands['name'] . '</td>
                 <td align="center">' . $a->Models['name'] . '</td>
                 <td align="center">' . $a->series . '</td>
                 <td align="center">' . $a->Assets_Transfers['transfers_state'] . '</td>
                 </tr>';
            }

/*
        for($a=0;$a < $size; $a++) {
            $html .= 
            '<tr>
            <td align="center">' . $results[$a]['description'] . '</td>
             <td align="center">' . $results[$a]['plaque'] . '</td>
             <td align="center">' . $results[$a]['brand'] . '</td>
             <td align="center">' . $results[$a]['model'] . '</td>
             <td align="center">' . $results[$a]['series'] . '</td>
             <td align="center">' . $results[$a]['state'] . '</td>
             </tr>';
        }

*/
        $html .=

            '</table>
            <br><br><br>
            <p><strong>Observaciones: </strong></p>
            <p><strong>Nota: El formulario debe estar firmado por el encargado de activos fijos u otro funcionario autorizado en cada unidad.</strong></p>
            <p><strong>Original: Oficina de Administraci&oacute;n Financiera&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copia: Unidad que entrega&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copia: Unidad que recibe</strong></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p align="center">Tels: 2511 5759/1149 </p>      
            <p align="center">www.oaf.ucr.ac.cr</p>      
            <p align="center">correo electrónico: activosfijos.oaf@ucr.ac.cr</p>
            ';


        $document->loadHtml($html);

        //set page size and orientation
        $document->setPaper('A3', 'portrait');
        //Render the HTML as PDF
        $document->render();
        //Get output of generated pdf in Browser
        $document->stream("Formulario de Traslado-".$id, array("Attachment"=>1));
            //1  = Download
            //0 = Preview
    }

    public function download2($id = null)
    {
        $transfer = $this->Transfers->get($id);
        $path=WWW_ROOT.'files'.DS.'Transfers'.DS.'file_name'.DS.$transfer->transfers_id.DS.$transfer->file_name;
        $this->response->file($path, array(
        'download' => true,
        'name' =>$transfer->file_name ,
        ));
        return $this->response;
        /*$this->Flash->error($path);
        return $this->redirect(['action' => 'view',$transfer->transfers_id]);*/
    }
}
