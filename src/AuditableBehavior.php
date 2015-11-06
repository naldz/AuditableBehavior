<?php

class AuditableBehavior extends Behavior
{
    public function objectAttributes($builder)
    {
		return "protected \$originalFieldValues = null; \n protected \$wasNew = false; \n protected \$wasModified = false;";
    }

    public function preSave()
    {   
        return "\$this->wasNew = \$this->isNew();";
    }

    public function preUpdate()
    {
        return "\$this->wasModified = \$this->isModified();";
    }
    
    public function postHydrate()
    {
        return "\$this->wasNew = \$this->getWasNew(); \n \$this->wasModified = \$this->getWasModified(); \n \$this->originalFieldValues = \$row;";
    }
    
    public function objectMethods($builder)
    {
        $columnNames = array();
        $columns = $this->getTable()->getColumns();
        foreach ($columns as $iColumn) {
            $columnNames[] = $iColumn->getName();
        }
        
        $tpl = $this->renderTemplate('ObjectMethodGetOriginalFieldValues', array(
           'columnNames' => $columnNames
        ));
        
        return $tpl;
    }

}