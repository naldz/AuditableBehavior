<?php

class AuditableBehavior extends Behavior
{
    public function objectAttributes($builder)
    {
		return "
            protected \$originalFieldValues = null; \n 
            protected \$wasNew = true; \n 
            protected \$wasModified = null; \n 
        ";
    }

    public function preSave()
    {   
        return "
            if (is_null(\$this->wasModified)) { \n
                \$this->wasModified = \$this->isModified(); \n
            }
        ";
    }
    
    public function postHydrate()
    {
        return "
            \$this->wasNew = \$this->isNew(); \n 
            \$this->originalFieldValues = \$row;
        ";
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