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
        return '
        /**
         * Returns the original field values of this object
         */
         
         public function getOriginalFieldValues()
         {
             return $this->originalFieldValues;
         }
         ';
    }
}