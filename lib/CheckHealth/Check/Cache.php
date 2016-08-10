<?php

namespace CheckHealth\Check;

class Cache implements \CheckHealth\CheckInterface
{
    public function check()
    {

        $putData = sha1(time());

        try {
    
            \Pimcore_Model_Cache::setForceImmediateWrite(true);
    
            \Pimcore_Model_Cache::save(
                $putData,
                $putData,
                array('check_write')
            );
    
            $getData = \Pimcore_Model_Cache::load($putData);
    
            \Pimcore_Model_Cache::clearTag("check_write");
    
        } catch (Exception $e) {
    
            throw new CheckException('Pimcore cache error ['.$e->getCode().']');
        }
    
        if ($putData !== $getData) {
    
            throw new CheckException('Pimcore cache failure - content mismatch.');
        }
    }
}