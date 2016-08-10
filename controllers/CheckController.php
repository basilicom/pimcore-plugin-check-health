<?php

use Website\Controller\Action;

class Checkhealth_CheckController extends Action
{    
    public function statusAction ()
    {
        // reachable via http://your.domain/plugin/CheckHealth/check/status

        $this->disableViewAutoRender();

        $response = $this->getResponse();
        $response->setHeader('Cache-Control', 'max-age=0');

        try {
            
            $checker = new \CheckHealth\Checker();
            $checker->check();

            $response->setBody('SUCCESS');

        } catch (\CheckHealth\CheckException $exception) {
            
            $response->setBody('FAILURE: ' . $exception->getMessage());
        
        } catch (\Exception $exception) {
            
            $id = uniqid();
            
            $logger = \Pimcore\Log\ApplicationLogger::getInstance("HealthCheck", true);
            $logger->error(
                "Check Exception $id\n"
                . 'Code: ' . $exception->getCode() . "\n"
                . $exception->getMessage() . "\n"
                . $exception->getTraceAsString() . "\n"
                );
            $response->setBody("FAILURE: INTERNAL [$id] - see application log.");
        }

        $response->sendResponse();
        exit();
    }
}