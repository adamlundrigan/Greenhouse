<?php
namespace Greenhouse\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatDateInterval extends AbstractHelper
{
    public function __invoke(\DateInterval $di)
    {
        $formatString = array();
        foreach ( array('y'=>'y','m'=>'m','d'=>'d','h'=>'h','i'=>'m','s'=>'s') as $k=>$v )
            if ( $di->{$k} > 0 )
                $formatString[] = "%$k$v";
        return $di->format(implode(' ', $formatString));
    }   
} 
