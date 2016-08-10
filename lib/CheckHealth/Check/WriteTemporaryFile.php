<?php

namespace CheckHealth\Check;

class WriteTemporaryFile implements \CheckHealth\CheckInterface
{
    public function check()
    {

        try {
    
            $putData = sha1(time());
            file_put_contents(PIMCORE_TEMPORARY_DIRECTORY.'/check_write.tmp', $putData);
            $getData = file_get_contents(PIMCORE_TEMPORARY_DIRECTORY.'/check_write.tmp');
            unlink(PIMCORE_TEMPORARY_DIRECTORY.'/check_write.tmp');
    
        } catch (Exception $e) {
    
            throw new CheckException('Unable to read/write a file. ['.$e->getCode().']');
        }
    
        if ($putData !== $getData) {
    
            throw new CheckExcepton('Error writing/reading a file.');
        }
    }
}