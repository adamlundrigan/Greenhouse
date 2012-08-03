<?php
namespace Greenhouse\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Header\ContentType;
use Greenhouse\Entity\Sensor as SensorEntity;

class SensorController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(array());
    }

    public function graphAction()
    {
        $startTime = time();
        $startTime = strtotime('2012-08-02 22:48:00');

        $sensor_code = $this->params()->fromRoute('code');
        $sensor = $this->getSensorMapper()->findByCode($sensor_code);
        if ( ! $sensor instanceof SensorEntity ) {
            throw new \InvalidArgumentException('Invalid sensor!');
        } 

        $count = 75;

        // Read 75 minutes of data
        $readings = $this->getSensorReadingMapper()->findLatestBySensorId($sensor->getId(), $count)->toArray(); 
        $data = array(); $min = NULL; $max = NULL;
        foreach ( $readings as $reading ) {
            $dt = $startTime - strtotime($reading['date_time']);
            $min = is_null($min) ? $reading['value'] : min($min,$reading['value']);
            $max = is_null($max) ? $reading['value'] : max($max,$reading['value']);
            $data[$dt] = $reading['value'];
        }
        ksort($data);

        // Some defaults
        $w = 540; $h = 60; $yMargin = $xMargin = 3;

        // Create base image
        $im = imagecreatetruecolor($w,$h);
        $bg_color   = imagecolorallocate($im, 245,245,245);
        $text_color = imagecolorallocate($im, 0,0,0);
        imagefill($im,0,0,$bg_color);

        // Draw the squiggly
        $secondsPerPixel = round(($count*60) / $w, 0);
        $last_x = $w; $last_y = 0;
        foreach ( $data as $time=>$value )
        {
            $y = (($h-($yMargin*2)) * ($value / ($max - $min))) - (($h-($yMargin*2)) / 2);
            $x = $w - ( $xMargin + round($time / $secondsPerPixel) );
            imageline($im, $last_x, $last_y, $x, $y, $text_color);
            $last_x = $x; $last_y = $y;
        }

        // Capture the image
        ob_start();
        imagepng($im);
        $contents = ob_get_contents();
        ob_clean();

        $response = $this->getResponse();
        $response->getHeaders()->addHeader(ContentType::fromString('Content-type: image/png'));
        return $this->getResponse()->setContent($contents);
    }

    protected $sensorMapper;
    public function getSensorMapper()
    {
        if ( $this->sensorMapper === NULL ) {
            $this->sensorMapper = $this->getServiceLocator()->get('gh_sensor_mapper');
        }
        return $this->sensorMapper;
    }

    protected $sensorReadingMapper;
    public function getSensorReadingMapper()
    {
        if ( $this->sensorReadingMapper === NULL ) {
            $this->sensorReadingMapper = $this->getServiceLocator()->get('gh_sensor_reading_mapper');
        }
        return $this->sensorReadingMapper;
    }

}
