<?php
namespace app\modules;

use php\lib\arr;
use facade\Json;
use php\gui\UXNodeWrapper;
use php\gui\UXNode;
use php\gui\event\UXEvent;
use php\gui\UXDialog;
use php\xml\XmlProcessor;
use php\util\Regex;
use php\io\MemoryStream;
use php\gui\UXComboBox;
use php\lib\fs;
use php\io\File;
use php\lib\str;
use Files;
use php\gui\framework\AbstractModule;
use php\gui\framework\ScriptEvent; 

class Module extends AbstractModule
{

    /**
     * @event profilesrefresh.action 
     **/
    function doProfilesrefreshAction(ScriptEvent $event = null)
    {
        $profiles = $this->profiles->toArray();
        foreach ($profiles as $profile) {
            $profilelist[] = $profile['ProfileName'];
        }
        
        $this->WelcomeProfileList->items->clear();
        $this->WelcomeProfileList->items->addAll($profilelist);
        
        //$this->SettingsProfileList->items->clear();
        //$this->SettingsProfileList->items->addAll($profilelist);
    }

}
