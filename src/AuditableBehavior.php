<?php

class AuditableBehavior extends Behavior
{
    public function objectAttributes($builder)
    {
        return 'protected $originalFieldValues = null;';
    }
    
    public function postHydrate()
    {
        return '$this->originalFieldValues = $row;';
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