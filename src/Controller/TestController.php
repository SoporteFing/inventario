    
<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
/**
 * Employees Controller
 *
 * @property \App\Model\Table\EmployeesTable $Employees
 */
class TestController extends AppController
{
   /**
     * staticData Method
     */
    public function staticData()
    {
        $this->viewBuilder()->layout('datatables');
    }
}
    
