<?php
namespace Greenhouse\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(array(
            'sensors' => $this->getSensorService()->fetchAll(),
        ));
    }

    protected $sensorService;
    public function getSensorService()
    {
        if ( $this->sensorService === NULL ) {
            $this->sensorService = $this->getServiceLocator()->get('gh_sensor_service');
        }
        return $this->sensorService;
    }

}
