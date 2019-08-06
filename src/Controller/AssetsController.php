<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
use Imagine;

/**
* Controlador para los activos de la aplicaciÃ³n
*/
class AssetsController extends AppController
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
                if($item['nombre'] == 'Insertar Activos'){
                    $allowI = true;
                }else if($item['nombre'] == 'Modificar Activos'){
                    $allowM = true;
                }else if($item['nombre'] == 'Eliminar Activos'){
                    $allowE = true;
                }else if($item['nombre'] == 'Consultar Activos'){
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
        }else if($this->request->getParam('action') == 'softDelete'){
            return $allowE;
        }else if($this->request->getParam('action') == 'view'){
            return $allowC;
        }else{
            return $allowC;
        }


    }
    /**
     * MÃ©todo para desplegar una lista con un resumen de los datos de activos
     */
    public function index()
    {

        $as = $this->Assets->find();
        $assets = $this->Assets->find()          
            ->select([
                'Assets.plaque',
                'Assets.deleted',
                'Types.name',
                'Models.name',
                'Assets.year',
                'Locations.nombre',
                'Locations.location_id',
                'Assets.series',
                'Assets.state',
                'Brands.name',
                'Users.nombre',
                'Users.apellido1',
                'Users.apellido2',

            ])
            ->join([
        'table' => 'types',
        'alias' => 'Types',
        'type' => 'INNER',
        'conditions' => 'Assets.type_id = Types.type_id',
            ])
            ->join([
        'table' => 'users',
        'alias' => 'Users',
        'type' => 'INNER',
        'conditions' => 'Assets.assigned_to = Users.id',
            ])
            ->join([
        'table' => 'locations',
        'alias' => 'Locations',
        'type' => 'INNER',
        'conditions' => 'Assets.location_id = Locations.location_id',
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
            
            ->where(['Assets.deleted' => '0'])
            ;


    $this->set('assets', $this->paginate($assets));

    }
    /**
     * MÃ©todo para ver los datos completos de un activo
     */
    public function view($id = null)
    {

        $asset = $this->Assets->get($id, [
            'contain' => ['Users', 'Locations', 'Models', 'Types']
        ]);

        $this->set('asset', $asset);
    }
    /**
     * MÃ©todo para agregar nuevos activos al sistema
     */
    public function add()
    {


        $asset = $this->Assets->newEntity();
        if ($this->request->is('post')) {
            
 

            $random = uniqid();
            $fecha = date('Y-m-d H:i:s');
            $asset->created = $fecha;
            $asset->modified = $fecha;
            $asset->unique_id = $random;
            $asset->deletable = false;
            $asset->deleted = false;
            $asset->state = "Disponible";
            $asset = $this->Assets->patchEntity($asset, $this->request->getData());


			if ($_POST['models_id'] == '') {
				$asset->models_id = null;
			}

            if ($_POST['brands_id'] != '') {
                $asset->brand = $_POST['brands_id'];
            }


            /** verifica que el id no sea repetido y se setea el error manualmente */
            $returnId = $this->Assets->find('all')
            ->where([
            'Assets.plaque' => $asset->plaque
            ])
            ->first();
            if($returnId){
                $asset->setError('plaque', ['El nÃºmero de placa ya existe.']);
            }


            if ($this->Assets->save($asset)) {
                AppController::insertLog($asset['plaque'], TRUE);
                $this->Flash->success(__('El activo fue guardado exitosamente.'));
                return $this->redirect(['action' => 'index']);
            }else{

                foreach ($asset->getErrors() as $field => $error) {
                    if($field == 'series'){
                        foreach ($error as $id => $message) {
                            if($id == '_isUnique'){
                                //debug($message);
                                $asset->setError('series', [$message]);
                            }
                        }
                    }
                } 

            }
            AppController::insertLog($asset['plaque'], FALSE);
            $this->Flash->error(__('El activo no se pudo guardar, por favor intente nuevamente.'));
        }
        
        $this->loadModel('Brands');
        $brands = $this->Brands->find('list', ['limit' => 200]);

        $users = $this->Assets->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);

        $locations = $this->Assets->Locations->find('list', ['limit' => 200]);
		$types = $this->Assets->Types->find('list', ['limit' => 200]);
        
        $this->set(compact('asset', 'brands', 'users', 'locations', 'models', 'types'));
    }
    /**
     * MÃ©todo para editar un activo en el sistema
     */
    public function edit($id = null)
    {
        $asset = $this->Assets->get($id, [
            'contain' => []
        ]);

        //debug($asset);
        //die();

        $this->loadModel('Models');

        if($asset->models_id != null){    
            $model = $this->Models->get($asset->models_id, [
                'contain' => []
            ]);
        }


        $this->loadModel('Brands');
        if($asset->brand != null){
            
            $brand = $this->Brands->get($asset->brand, [
                'contain' => []
            ]);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $fecha = date('Y-m-d H:i:s');
            $asset->modified = $fecha;
            
            $asset = $this->Assets->patchEntity($asset, $this->request->getData());
			if ($_POST['models_id'] == '') {
				$asset->models_id = null;
			}
            if ($_POST['series'] == '') {
                $asset->series = null;
            }

            if ($this->Assets->save($asset)) {
                AppController::insertLog($asset['plaque'], TRUE);
                $this->Flash->success(__('El activo fue guardado exitosamente.'));
                return $this->redirect(['action' => 'index']);
            }
            
            AppController::insertLog($asset['plaque'], FALSE);
            $this->Flash->error(__('El activo no se pudo guardar, por favor intente nuevamente.'));
        }

        
        $brands = $this->Brands->find('list')          
            ->select([
                'Brands.id',
                'Brands.name'
            ])
            ->distinct(['Brands.name'])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'RIGHT',
        'conditions' => 'Models.id_brand = Brands.id',
            ])
            ->where(['Models.id_type' => $asset->type_id]);

        $this->loadModel('Models');
        $models = $this->Models->find('list')
            ->where(['Models.id_brand' => $asset->brand]);

        
        $users = $this->Assets->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);

        $locations = $this->Assets->Locations->find('list', ['limit' => 200]);
		$types = $this->Assets->Types->find('list', ['limit' => 200]);
		
        $this->set(compact('asset', 'brands', 'users', 'locations', 'models', 'types', 'model','brand'));

    }


    /**
     * Restaura un activo desactivado
     */
    public function restore($plaque){
        $asset = $this->Assets->get($plaque);
        $asset->deleted = false;
        $asset->state = 'Disponible';
        $fecha = date('Y-m-d H:i:s');
        $asset->modified = $fecha;

        if ($this->Assets->save($asset)) {
            $this->Flash->success(__('El activo fue activado exitosamente.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('El activo no se pudo activar correctamente.'));
        return $this->redirect(['action' => 'index']);
    }


    /**
     * Elimina solo logicamente los activos de la base de datos
     * 
     * @param asset
     * @return 0 - archivo no se eliminÃ³ correctamente, 1 - hard delete completado, 2 - soft delete completado
     */
    public function softDelete($plaque){
        $asset = $this->Assets->get($plaque);
        
        if($asset->deletable){
            if($this->Assets->delete($asset)){
                AppController::insertLog($asset['plaque'], TRUE);
                $this->Flash->success(__('El activo fue eliminado exitosamente.'));
                return $this->redirect(['action' => 'index']);
            }
            AppController::insertLog($asset['plaque'], FALSE);
            $this->Flash->error(__('El activo no se pudo eliminar correctamente.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $fecha = date('Y-m-d H:i:s');
        $asset->deleted = true;
        $asset->state = 'Desactivado';
        $asset->modified = $fecha;
        
        if ($this->Assets->save($asset)) {
            $this->Flash->success(__('El activo fue desactivado exitosamente.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('El activo no se pudo desactivar correctamente.'));
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * MÃ©todo para eliminar un activo del sistema
     */
    public function delete($asset)
    {
        /* 
        DEPRECATED 
            if ($this->Assets->delete($asset)) {
                return 1;
            } else {
                return 0;
            }
        */
    }

    /**
     * MÃ©todo para mostrar listas dependientes
     */
    public function brandsList()
    {
        $this->loadModel('Models');
        $this->loadModel('Brands');
        $this->loadModel('Types');

        $type_id = $_GET['type_id'];
        
        $type = $this->Types->get($type_id);

        if($type == NULL)
        {
            throw new NotFoundException(__('Tipo no encontrado') );      
        }
        
        $brands = $this->Brands->find('list')          
            ->select([
                'Brands.id',
                'Brands.name'
            ])
            ->distinct(['Brands.name'])
            ->join([
        'table' => 'models',
        'alias' => 'Models',
        'type' => 'RIGHT',
        'conditions' => 'Models.id_brand = Brands.id',
            ])
            ->where(['Models.id_type' => $type_id]);

        if(empty($brands))
        {
            throw new NotFoundException(__('Marcas no encontradas') );      
        }

        //debug($brands->toList());
        //die();

        $this->set('brands', $brands);

        /*Asocia esta funciÃ³n a la vista /Templates/Layout/model_list.ctp*/
        $this->render('/Layout/brand_list');
    }
    
    /**
     * MÃ©todo para mostrar listas dependientes
     */
    public function modelsList()
    {
        $this->loadModel('Models');
        $this->loadModel('Brands');
        
        $brand_id = $_GET['brand_id'];
        $type_id = $_GET['type_id'];
        
        $brand = $this->Brands->get($brand_id);
        if($brand == NULL)
        {
            throw new NotFoundException(__('Marca no encontrada') );      
        }
        
        $models = $this->Models->find('list')
            ->where(['Models.id_brand' => $brand_id, 'Models.id_type' => $type_id]);
        
        if(empty($models))
        {
            throw new NotFoundException(__('Modelos no encontrados') );      
        }
        $this->set('models', $models);
        /*Asocia esta funciÃ³n a la vista /Templates/Layout/model_list.ctp*/
        $this->render('/Layout/model_list');
    }
    



    /**
     * MÃ©todo para agregar activos por lotes
     */
    public function batch($cantidad = null)
    {
        $asset = $this->Assets->newEntity();
        //$asset = $this->Assets->newEntity();
        if ($this->request->is('post')) {
            //guarda en variables todos los campos reutilizables
            $cantidad = $this->request->getData('quantity');
            $placa = $this->request->getData('plaque');
            $marca = $this->request->getData('brand');
            $modelo = $this->request->getData('models_id');
            $tipo = $this->request->getData('type_id');
			//$type = $this->request->getData('type_id');
            if ($_POST['brand'] == '') {
                $marca = null;
            } else {
                $marca = $this->request->getData('brand');
            }
            
			if ($_POST['models_id'] == '') {
				$modelo = null;
			} else {
				$modelo = $this->request->getData('models_id');
			}
            $descripcion = $this->request->getData('description');
            $dueno = $this->request->getData('owner_id');
            $responsable = $this->request->getData('responsable_id');
            $asignado = $this->request->getData('assigned_to');
            $ubicacion = $this->request->getData('location_id');
            $subUbicacion = $this->request->getData('sub_location');
            $aÃ±o = $this->request->getData('year');
            $prestable = $this->request->getData('lendable');
            $observaciones = $this->request->getData('observations');
            $imagen = $this->request->getData('image');
            $archivo = $this->request->getData('file');
            $series = $this->request->getData('series');
            $listaSeries = preg_split("/(, )| |,/", $series, -1);
            //parseo la placa con letras para dividirla en predicado+numero (asg21fa34)
            //divide con una expresion regular: (\d*)$
            //pregunta si hay letras en la placa
            if (preg_match("/([a-z])\w+/", $placa)){
                list($predicado, $numero) = preg_split("/(\d*)$/", $placa, NULL ,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            }
            //$predicado = asg21fa
            //$numero = 34
            //realiza el ciclo
            for ($i = 0; $i < $cantidad; $i++){
                $asset = $this->Assets->newEntity();
                if (array_key_exists($i, $listaSeries)){
                        $serie = $listaSeries[$i];
                    } else{
                        $serie = null;
                    }
                $random = uniqid();
                $fecha = date('Y-m-d H:i:s');
                $asset->created = $fecha;
                $asset->modified = $fecha;
                $asset->unique_id = $random;
                $asset->deletable = true;
                $asset->deleted = false;
                $asset->state = "Disponible";
                if(!preg_match("/([a-z])\w+/", $placa)){ //pregunto si las placas solo son de numeros
                    $data = [
                        'plaque' => $placa,
                        'brand' => $marca,
                        'models_id' => $modelo,
                        'type_id' => $tipo,
                        'series' => $serie,
                        'description' => $descripcion,
                        'owner_id' => $dueno,
                        'responsable_id' => $responsable,
                        'assigned_to' => $asignado,
                        'location_id' => $ubicacion,
                        'sub_location' => $subUbicacion, 
                        'year' => $aÃ±o,
                        'lendable' => $prestable,
                        'observations' => $observaciones,
                        'image' => $imagen,
                        'file' => $archivo
                    ];
                    //incrementa la placa
                    $placa = $placa + 1;
                }
                else{ //entonces las placas son alfanumericas, agrego predicado+numero como placa
                    $data = [
                        'plaque' => $predicado . $numero,
                        'brand' => $marca,
                        'models_id' => $modelo,
                        'type_id' => $tipo,
                        'series' => $serie,
                        'description' => $descripcion,
                        'owner_id' => $dueno,
                        'responsable_id' => $responsable,
                        'assigned_to' => $asignado,
                        'location_id' => $ubicacion, 
                        'sub_location' => $subUbicacion, 
                        'year' => $aÃ±o,
                        'lendable' => $prestable,
                        'observations' => $observaciones,
                        'image' => $imagen,
                        'file' => $archivo
                    ];
                    //incrementa la placa
                    $numero = $numero + 1;
                }
                
                $asset = $this->Assets->patchEntity($asset, $data);
                //meter una por una a la base
                $this->Assets->save($asset);
            }
            $this->Flash->success(__('Los activos fueron guardados'));
            return $this->redirect(['action' => 'index']);
        }
        $this->loadModel('Brands');
        $brands = $this->Brands->find('list', ['limit' => 200]);
        //$types = $this->Assets->Types->find('list', ['limit' => 200]);
        $users = $this->Assets->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['nombre'] . ' ' . $row['apellido1'] . ' ' . $row['apellido2'];
             }
        ])
        ->where([
            'Users.username NOT IN' => 'root'
        ]);

        $locations = $this->Assets->Locations->find('list', ['limit' => 200]);
        $types = $this->Assets->Types->find('list', ['limit' => 200]);
        $this->set(compact('asset', 'types', 'brands', 'users', 'locations','models'));

    }


    public function print($id = null)
    {
        $check = array();

        if ($this->request->is('post')) {

            $check = $this->request->getData("checkList");
            $check = explode(",",$check);

            if($this->request->getData("checkList") == '') {
                AppController::insertLog($transfer['transfers_id'], TRUE);
                $this->Flash->error(__('Debe ingresar por lo menos un Activo imprimir la Etiqueta.'));

            }else{

                $resp = '';

                foreach ($check as $plaque) {
                    //$this->send_to_printer('Placa',$plaque);
                    $resp = $this->status();
                    debug($resp);
                    print_r($resp);

                    //revisar errores
                    if($resp == false){
                        $this->Flash->error(__('Ocurrio un error durante la impresion'));
                        break;
                    }
                }
                
                if($resp != false){

                    $this->Flash->success(__('Impresion Exitosa'));
                }

            }

        }
        
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
            'conditions' => 'Models.id_brand = Brands.id',
                ])
                ;


        if(!empty($check)){
 
                       
            $printing_assets = $this->Assets->find()   
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
            'conditions' => 'Models.id_brand = Brands.id',
                ])
                ->where(['Assets.plaque IN' => $check]);
                ;
            $this->set('printing_assets', $this->paginate($printing_assets));

        }

    
    $this->set('assets', $this->paginate($assets));


    
    }


  
  private function send_to_printer($label,$code){
	$fing_label = "GAP 3 mm, 0 mm\n DIRECTION 0,0\n REFERENCE 0,0\n OFFSET 0 mm\n SET PEEL OFF\n SET CUTTER OFF\n SET PARTIAL_CUTTER OFF\n CLS\n BITMAP 186,113,25,64,1,ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿ8ã8ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿü     ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿü     ÿÿÿÿÿÿÿÿÿÿÿÿÀ?ÿÿÿÿü     ÿÿÿÿÿÿÿÿÿÿÿÿ€ÿÿÿÿü     ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿşÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿş?ÿÿÿÿÿÿÿÿÿÿÿÿÿÿïüÿÏşûÿ¿óş>ÿïÿóÿÿÿÿÿÿÿÿÀÃøpøğş< ş0ááÿÿÿÿÿÿÿÿàÃøx øğş< > áãÿÿÿÿÿÿÿÿáàÃøÿğxğş?ü<ããÿÿÿÿÿÿÿÿãğãøÿøpøş?ş‡ããÿüÿÿÿàãğÃøüxğş?ÿ?ÇããÿüÿÿÿàãáÃøş8ğş?ÿ?ÃááÿüÿÿÿÀàAÇøp 8ñş< ?ãááÿüÿÿÿÀàÃøp 8ğş8 ?ÃáãÿüÿÿÿààÃøá|xğş8?Çããÿüÿÿÿàãÿãğñüpøü8?‡ããÿüÿÿÿàãÿÃàpøxø|<~‡‡ÃãÿüÿÿÿààóÃ x`øø <8>ÃáÿüÿÿÿÀøÇxøü> ~ ÀáÿüÿÿÿÀüÃ~øÿ?ş0à1ãÿüÿÿÿàÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿãÿüÿÿÿàÿÿóÿÿÿÿÿÿÿÿÿÿÿÿÿãÿüÿÿÿàÿÿÇÿÿÿøÿÿÿÿÿÿÿÿÿãÿüÿÿÿàÿÿ‡ÿÿÿøÿÿÿÿÿÿÿÿáÿüÿÿÿÀÿÿÿÿÿøÿÿÿÿÿÿÿÿÿáÿüÿÿÿÀÿşÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿÿÿàÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿÿÿàÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿÿÿàÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿÿÿàÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿÿÿÀÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿÿÿÀÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿ   ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿ   ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüş   ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿ   ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿ   ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿÿÿÀÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿüÿÿÿàÀş0üÁùƒÁùóà?8óÿüÿÿÿà€> ?ü ù€ÀùâÀ ?óÿüÿÿÿàÿü8x8øùğaïóÿüÿÿÿàÿø~<xüøññÿ‡ñÿüÿÿÿÀÿÎ?øş<üüøññÿÇñÿüÿÿÿÀÿÎ?øÿ8øüøóùÿÇóÿüÿÿÿàã?Çüÿ8ğøùóùÿÇ?óÿüÿÿÿà ?Çüÿ8øùãøÿÇóÿüÿÿÿà?Ïüÿ8üùóùÿÇÿóÿüÿÿÿà??øş<ÿüøóùÿÇøÿüÿÿÿÀŸøş<ÿüøóùÿÇŸøÿüÿÿÿÀø<|}üøóùÿ¿óÿüÿÿÿàÀ> ?ü ş À9óùÀ€?óÿüÿÿÿààş0ÿüÿÀ9ãøà?àÿóÿüÿÿÿàÿş?ÿüÿÿÿüùÿÿÿÿÿÿóÿüü   ÿş?ÿøÿÿÿüøÿÿÿÿÿÿñÿüü   ÿÿ?ÿøÿÿÿüøÿÿÿÿÿÿñÿüü   ÿş?ÿøÿÿÿıøÿÿÿÿÿğÿüü   ÿş?ÿüÿÿÿÿùÿÿÿÿÿğÿüü   ÿş?ÿüÿÿÿÿùÿÿÿÿÿ÷Ÿÿüü   ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿ \n CODEPAGE 1252\n TEXT 383,100,\"0\",180,15,15,\"$label\"\n TEXT 383,50,\"0\",180,15,15,\"$code\"\n QRCODE 45,20,H,4,A,0,M2,\"$code\"\n PRINT 1,1\n";//end
	
     

    return $this->curl($fing_label);
  }

private function status(){


    $stat = chr(27) + "!?";
    #  $qrCode = "SIZE 4,2.5\n GAP 0,0\n DIRECTION 1\n CLS\n QRCODE 56,24,H,4,A,0,M2,\"ABCabc123\"\n PRINT 1\n";
    #  $pTest = "DIRECTION 1\n SIZE 2,1\n GAP 3mm,0mm\n SPEED 4\n DENSITY 12\n CLS\n BAR 8,8,300,100\n PRINT 1\n";


    return $this->curl($stat);

}
  

private function curl($request){

  $curl = curl_init();
  // Set some options - we are passing in a useragent too here
  curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://163.178.109.21/admin/cgi-bin/function.cgi',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
	CURLOPT_USERNAME => 'rid',
	CURLOPT_PASSWORD => '3xq23IAedH',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
      send => $request
    )
  ));

  $resp = curl_exec($curl);  // Send the request & save response to $resp

  curl_close($curl);  // Close request to clear up some resources

  return $resp;
    }

}
