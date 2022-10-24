<?php

namespace cv6\Core\Setup;

trait SetupTrait
{

    /**
     * inherit from this for the tables which need to be created.
     */
    function getTables() {
        return [];
    }

    protected function createTables()
    {
        $sm = $this->schemaManager();

        foreach ($this->getTables() as $tableName => $closure)
        {
            if (!$sm->tableExists($tableName))
            {
                $sm->createTable($tableName, $closure);
            }      
        }        
    }

    protected function dropTables()
    {
        $sm = $this->schemaManager();

        foreach (array_keys($this->getTables()) as $tableName)
        {
            if ($sm->tableExists($tableName)) 
            {
                $sm->dropTable($tableName);
            }
        }
    }    
}