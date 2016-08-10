<?php

namespace CheckHealth;

class Checker
{
    public function check()
    {
        
        if (!Plugin::isEnabled()) {
            
            throw new CheckException('HealthCheck not enabled!');
        }
        
        foreach (Plugin::getCheckList() as $check) {
            
            $check = new $check();
            $check->check();
        }

    }
}
