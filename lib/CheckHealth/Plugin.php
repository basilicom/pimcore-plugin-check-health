<?php
namespace CheckHealth;

use Pimcore\Model\WebsiteSetting;
use Pimcore\API\Plugin as PluginApi;
use Pimcore\Db;


class Plugin extends PluginApi\AbstractPlugin implements PluginApi\PluginInterface
{

    const SAMPLE_CONFIG_XML = "/CheckHealth/checkhealth.xml";
    const CONFIG_XML = '/var/config/checkhealth.xml';

    /**
     * @var bool store enabled state - set in config xml
     */
    private static $isEnabled = false;

    /**
     * @var array check class names
     */
    private static $checks = array();

    public function init()
    {
        parent::init();
        
        if (!self::isInstalled()) {
            return;
        }
        
        $config = new \Zend_Config_Xml(self::getConfigName());
        self::$isEnabled = ($config->checkhealth->get('enabled', '0') == '1');
        if (!self::$isEnabled) {
            return;
        }
        
        self::$checks = array();
        foreach ($config->checkhealth->checks->check as $check) {
            self::$checks[] = $check;
        }
    }

    public static function getCheckList()
    {
        return self::$checks;
    }

    public static function install()
    {
        if (!file_exists(self::getConfigName())) {
            $defaultConfig = new \Zend_Config_Xml(PIMCORE_PLUGINS_PATH . self::SAMPLE_CONFIG_XML);
            $configWriter = new \Zend_Config_Writer_Xml();
            $configWriter->setConfig($defaultConfig);
            $configWriter->write(self::getConfigName());
        }
        $config = new \Zend_Config_Xml(self::getConfigName(), null, array('allowModifications' => true));
        $config->checkhealth->installed = 1;
        $configWriter = new \Zend_Config_Writer_Xml();
        $configWriter->setConfig($config);
        $configWriter->write(self::getConfigName());
        if (self::isInstalled()) {
            return "Successfully installed.";
        } else {
            return "Could not be installed";
        }
    }

    public static function uninstall()
    {
        if (file_exists(self::getConfigName())) {
            $config = new \Zend_Config_Xml(self::getConfigName(), null, array('allowModifications' => true));
            $config->checkhealth->installed = 0;
            $configWriter = new \Zend_Config_Writer_Xml();
            $configWriter->setConfig($config);
            $configWriter->write(self::getConfigName());
        }
        if (!self::isInstalled()) {
            return "Successfully uninstalled.";
        } else {
            return "Could not be uninstalled";
        }
    }

    public static function isInstalled()
    {
        if (!file_exists(self::getConfigName())) {
            return false;
        }
        $config = new \Zend_Config_Xml(self::getConfigName());
        if ($config->checkhealth->installed != 1) {
            return false;
        }
        return true;
    }
    
    public static function isEnabled()
    {
        return self::$isEnabled;
    }

    /**
     * Return config file name
     *
     * @return string xml config filename
     */
    private static function getConfigName()
    {
        return PIMCORE_WEBSITE_PATH . self::CONFIG_XML;
    }
    
    public static function needsReloadAfterInstall()
    {
        return false; // backend only functionality!
    }

}