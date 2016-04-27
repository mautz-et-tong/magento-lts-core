<?php
/**
 * @author    Daniel Niedergesäß <daniel.niedergesaess@gmail.com>
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 * @link      https://github.com/smart-devs/magento-lts-core/
 *
 *
 * need following flag in app/etc/{local.xml/config.xml}
 *
 * <config>
 *      <global>
 *          <skip_process_modules_updates>1</skip_process_modules_updates>
 *      </global>
 * </config>
 */

umask(0);
ini_set('memory_limit', '512M');
set_time_limit(0);
require realpath(dirname(__FILE__).'/../').'/app/Mage.php';

//start app without cache
Mage::app('admin', 'store', array('global_ban_use_cache' => TRUE));

echo "Start applying updates...\n";
Mage_Core_Model_Resource_Setup::applyAllUpdates();
Mage_Core_Model_Resource_Setup::applyAllDataUpdates();
echo "End applying updates...\n";

//reinit cache with fresh version
Mage::getConfig()->getOptions()->setData('global_ban_use_cache', FALSE);
Mage::app()->baseInit(array());
Mage::getConfig()->loadModules()->loadDb()->saveCache();
echo "finished updates.\n";