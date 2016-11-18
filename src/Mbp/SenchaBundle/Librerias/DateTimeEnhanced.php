<?php
namespace Mbp\SenchaBundle\Librerias;
use \DateTime;


class DateTimeEnhanced extends DateTime 
{
    public function returnAdd($interval)
    {
        $dt = clone $this;
        $dt->add($interval);
        return $dt;
    }
    
    public function returnSub($interval)
    {
        $dt = clone $this;
        $dt->sub($interval);
        return $dt;
    }
}