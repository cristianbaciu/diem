<?php

class dmModuleManager
{

  protected
  $types,
  $modules,
  $projectModules;
  
  public function __construct(array $options = array())
  {
    $this->initialize($options);
  }

  public function initialize(array $options = array())
  {
    $this->options = $options;
  }
  
  public function load(array $types, array $modules, array $projectModules)
  {
    $this->types          = $types;
    $this->modules        = $modules;
    $this->projectModules = $projectModules;
  }
  
  public function getTypes()
  {
    return $this->types;
  }

  public function checkModulesConsistency()
  {
    if (!$this->getModuleOrNull('main'))
    {
      throw new dmException('You must have a main module');
    }
  }

  public function getType($typeName)
  {
    return dmArray::get($this->getTypes(), $typeName);
  }

  public function getTypeBySlug($slug, $default = null)
  {
    foreach($this->getTypes() as $type)
    {
      if ($type->getSlug() === $slug)
      {
        return $type;
      }
    }
    
    return $default;
  }
  
  public function getModules()
  {
    return $this->modules;
  }

  public function getProjectModules()
  {
    return $this->projectModules;
  }

  
  public function hasModule($moduleKey)
  {
    return isset($this->modules[$moduleKey]);
  }
  
  public function getModule($something, $orNull = false)
  {
    if ($something instanceof dmModule)
    {
      return $something;
    }

    $moduleKey = dmString::modulize($something);

    if (isset($this->modules[$moduleKey]))
    {
      return $this->modules[$moduleKey];
    }

    if ($orNull)
    {
      return null;
    }

    throw new dmException(sprintf("The %s module does not exist", $something));
  }

  public function getModuleOrNull($something)
  {
    return $this->getModule($something, true);
  }

  public function getModulesWithPage()
  {
    $modulesWithPage = array();
    
    foreach($this->projectModules as $key => $module)
    {
      if ($module->hasPage())
      {
        $modulesWithPage[$key] = $module;
      }
    }
    
    return $modulesWithPage;
  }

  public function getModulesWithModel()
  {
    $modulesWithModel = array();
    
    foreach($this->projectModules as $key => $module)
    {
      if ($module->hasModel())
      {
        $modulesWithModel[$key] = $module;
      }
    }
    
    return $modulesWithModel;
  }

  public function getModuleByModel($model)
  {
    $model = dmString::camelize($model);
    
    foreach($this->getProjectModules() as $module)
    {
      if ($module->getModel() === $model)
      {
        return $module;
      }
    }

    foreach($this->getModules() as $module)
    {
      if ($module->getModel() === $model)
      {
        return $module;
      }
    }

    return null;
  }

  
  public function keysToModules(array $keys)
  {
    $modules = array();
    
    foreach($keys as $key)
    {
      $modules[$key] = $this->getModule($key);
    }
    
    return $modules;
  }
  /*
   * Remove modules wich are child of another modified module
   * Keep only rooter modified modules
   * @return array of dmModule
   */
  public static function removeModulesChildren(array $modules)
  {
    $unsettedModules = array();
    
    foreach($modules as $moduleKey => $module)
    {
      foreach($modules as $_moduleKey => $_module)
      {
        if (!isset($unsettedModules[$_moduleKey]) && $_module->hasAncestor($moduleKey))
        {
          $unsettedModules[$_moduleKey] = $_moduleKey;
        }
      }
    }

    foreach(array_unique($unsettedModules) as $unsettedModuleKey)
    {
      unset($modules[$unsettedModuleKey]);
    }

    return $modules;
  }

}