<?php

require_once(dm::getDir().'/dmCorePlugin/lib/config/dmApplicationConfiguration.php');

/**
 * sfConfiguration represents a configuration for a symfony application.
 *
 * @package    symfony
 * @subpackage config
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfApplicationConfiguration.class.php 13947 2008-12-11 14:15:32Z fabien $
 */
abstract class dmAdminApplicationConfiguration extends dmApplicationConfiguration
{  

  /**
   * @see sfProjectConfiguration
   */
  public function initConfiguration()
  {
    require_once(sfConfig::get('dm_admin_dir').'/lib/config/dmAdminModuleManagerConfigHandler.php');
    
    parent::initConfiguration();
  }
  
  protected function getDmPlugins()
  {
    return array('dmGuardPlugin', 'dmAdminPlugin');
  }
}