<?php

namespace  cv6\Core\Cli\Command;

trait cv6CommandTrait {
    
   /**
    * @return string
    */
    abstract function getInternalShortCode();

    public function configure()
    {
        parent::configure();
        $this->setName('cv6:' . $this->getInternalShortCode() . ":" .$this->getRebuildName());
    }


}