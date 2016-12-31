<?php
namespace app\forms;

use php\util\Regex;
use app\modules\VDF;
use php\io\File;
use php\lib\str;
use php\lib\fs;
use facade\Json;
use action\Animation;
use php\gui\UXDialog;
use php\gui\paint\UXColor;
use php\gui\UXTabPane;
use php\gui\UXTab;
use php\gui\framework\AbstractForm;
use php\gui\event\UXEvent; 
use php\gui\event\UXMouseEvent; 


class Settings extends AbstractForm
{

    /**
     * @event SettingsProfilesEdit.click-Left 
     */
    function doSettingsProfilesEditClickLeft(UXMouseEvent $event = null)
    {    
        // Not working right now
    }

    /**
     * @event SettingsTF2DirectorySave.click-Left 
     */
    function doSettingsTF2DirectorySaveClickLeft(UXMouseEvent $event = null)
    {    
        global $gamedirstatus;
        $gamedirstatus = "false";
        $this->form('Main')->doShow();
    }




 

    
    

}
