<?php

namespace cv6\Core\Setup;

trait SetupTrait
{

    var $indexDefinitions = [];
    /**
     * inherit from this for the tables which need to be created.
     */
    function getTables($tableName = null) {
        return [];
    }

    function getInitialData($tableName = null) {
        return [];
    }

    protected function createTables($singleTable = null)
    {
        $sm = $this->schemaManager();
        
        foreach ($this->getTables() as $tableName => $closure)
        {
            if ( (!$sm->tableExists($tableName) && !$singleTable)
                || (!$sm->tableExists($tableName) && $singleTable == $tableName) )
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

    private function indexExists($indexName) : bool
    {
        if (empty($this->indexDefinitions))
        {
            $this->indexDefinitions = $this->schemaManager()->getIndexDefinitions();
        }

        return isset($indexes[$indexName]);
        
    }

    protected function addIndex($tableName, $indexName, $columns)
    {
        $sm = $this->schemaManager();
        $indexes = $this->getIndexDefinitions($tableName);

        if ($sm->tableExists($tableName) && !$this->indexExists($tableName, $indexName))
        {
            $sm->addIndex($tableName, $indexName, $columns);
        }
    }

    protected function insertInitialData($tableName = null) 
    {
        /** @var \XF\Db\SchemaManager $sm */
        $sm = $this->schemaManager();

        foreach ($this->getInitialData() as $tableName => $query)
        {
            $this->query($query);
        }
    }
}

