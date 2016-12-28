<?php
namespace app\forms;

use php\gui\UXDatePickerWrapper;
use php\gui\UXDatePicker;
use app\modules\VDF;
use facade\Json;
use php\util\Regex;
use php\xml\XmlProcessor;
use php\gui\UXScreen;
use php\lang\System;
use php\gui\UXDialog;
use php\io\File;
use php\time\Time;
use php\gui\UXData;
use behaviour\custom\BloomEffectBehaviour;
use php\gui\effect\UXGaussianBlurEffect;
use app\forms\Welcome;
use php\gui\framework\AbstractForm;
use php\gui\event\UXMouseEvent; 
use action\Animation; 
use php\gui\event\UXWindowEvent; 
use php\lib\fs;
use php\gui\event\UXEvent; 

class Main extends AbstractForm
{ 
    
   /**
     * @event showing 
     **/
     
    function doShowing(UXWindowEvent $event = null)
    {      
        global $checkerrequired, $gamedir;
        $checkerrequired['github'] = true; $checkerrequired['hudstf'] = true;
        
        // Caching scenes
        $this->Forms->phys->loadScene('Manager'); 
        $this->Forms->phys->loadScene('Customize'); 
        $this->Forms->phys->loadScene('Settings'); 
        
        // Load main scene
        $this->Forms->phys->loadScene('Welcome');
        
        $this->Forms->y = 700;
        $this->ClosePanel->y = -150;
        $this->DirPanel->x = 0;    $this->DirPanel->y = -150; 
        $this->HeaderWarningButton->x = -110;
        
        Animation::moveTo($this->HeaderButtonPanel, 250, 830.0, 10.0);
        Animation::moveTo($this->Forms, 250, 1.0, 170.0);       
          
        if ( fs::isFile("settings.dat") ) { 
            $this->form('Welcome')->HeaderWelcomeLabel->text = "WELCOME BACK!";
            $gamedir = $this->settings->toArray(); 
            $gamedir = $gamedir['SETTINGS']['GameDirectory'];
            if ( fs::isDir($gamedir) and fs::isDir("$gamedir\\tf") ) {
                $gamedirstatus = "true";
            } else {
                $gamedirstatus = "false";
            }
        } else {
            $this->form('Welcome')->HeaderWelcomeLabel->text = "WELCOME!";
            $gamedirstatus = "false";
        }
        
        if ( $gamedirstatus == "false" ) {
            waitAsync(750, function () use ($event) {            
                Animation::moveTo($this->HeaderButtonPanel, 300, 900.0, 10.0);
                waitAsync(150, function () use ($event) {
                    Animation::moveTo($this->DirPanel, 300, 1.0, 1.0);
                    $this->AHUDMLogo->enabled = false;
                    $this->MenuWelcomeButton->enabled = false;    
                    $this->MenuManagerButton->enabled = false;   
                    $this->MenuCustomizeButton->enabled = false;  
                    $this->MenuOtherButton->enabled = false;
                    $this->MenuSettingsButton->enabled = false;   
                    $this->Forms->enabled = false;   
                });                   
           });
        }
        $this->AHUDMLogo->opacity = 0; $this->AHUDMLogo->y = -50;
        Animation::fadeIn($this->AHUDMLogo, 250);
        Animation::moveTo($this->AHUDMLogo, 250, 0.0, 0.0);
        
        $month = Time::now()->month();
        if ( $month == 12 or $month == 1) {
            $this->HeaderXmasBackground->visible = true;
        } else {
            $this->HeaderXmasBackground->visible = false;
        }    
    }
    
    /**
     * @event DirExitButton.click-Left 
     **/
    function doDirExitButtonClickLeft(UXMouseEvent $event = null)
    {    
        Animation::fadeOut($this, 500, function () use ($event) {
            app()->hideForm($this->getContextFormName());
        });                        
    }
    /**
     * @event DirApplyButton.click-Left 
     **/
    function doDirApplyButtonClickLeft(UXMouseEvent $event = null)
    {        
        $gamedir = $this->DirPath->text;
        if ( fs::isDir($gamedir) and fs::isDir("$gamedir\\tf") ) {
            Animation::moveTo($this->DirPanel, 300, 1.0, -150.0);
            waitAsync(150, function () use ($event) {
                Animation::moveTo($this->HeaderButtonPanel, 300, 830.0, 10.0);
                $this->AHUDMLogo->enabled = true;
                $this->MenuWelcomeButton->enabled = true;    
                $this->MenuManagerButton->enabled = true;   
                $this->MenuCustomizeButton->enabled = true;  
                $this->MenuOtherButton->enabled = true;
                $this->MenuSettingsButton->enabled = true;   
                $this->Forms->enabled = true;   
            });
            $this->settings->set('GameDirectory', $gamedir, 'SETTINGS');
            $this->toast("Success! Directory was saved.", 2000); 
        } else {  
            $this->toast("Error! Wrong directory.", 2000);  
        }  
    } 
    
    /**
     * @event DirBrowseButton.click-Left 
     **/
    function doDirBrowseButtonClickLeft(UXMouseEvent $event = null)
    {    
        $this->tfdirchooser->execute();     
    }       
    
    /**
     * @event MenuWelcomeButton.click-Left 
     **/
    function doMenuWelcomeButtonClickLeft(UXMouseEvent $event = null)
    {    
        $this->MenuPanel->enabled = false;
        waitAsync(350, function () use ($event) {
            $this->MenuPanel->enabled = true;
        });             
        $this->Bugfix->visible = false;
        Animation::moveTo($this->MenuSelector, 200, 0.0, 1.0);
        Animation::moveTo($this->Forms, 150, 1.0, 700.0);   
        waitAsync(150, function () use ($event) {
            $this->Forms->phys->loadScene('Welcome');
            Animation::moveTo($this->Forms, 150, 1.0, 170.0);
        });     
        waitAsync(1500, function () use ($event) {
            $this->Bugfix->visible = true;
        });           
    }
    
    /**
     * @event MenuManagerButton.click-Left 
     **/
    function doMenuManagerButtonClickLeft(UXMouseEvent $event = null)
    {   
        $this->MenuPanel->enabled = false;
        waitAsync(350, function () use ($event) {
            $this->MenuPanel->enabled = true;
        });      
        $this->Bugfix->visible = false;
        Animation::moveTo($this->MenuSelector, 200, 170.0, 1.0);
        Animation::moveTo($this->Forms, 150, 1.0, 700.0);   
        waitAsync(150, function () use ($event) {
            $this->Forms->phys->loadScene('Manager');
            Animation::moveTo($this->Forms, 150, 1.0, 170.0);
        });  
        waitAsync(1500, function () use ($event) {
            $this->Bugfix->visible = true;
        });             
    }
    
    /**
     * @event MenuCustomizeButton.click-Left 
     **/
    function doMenuCustomizeButtonClickLeft(UXMouseEvent $event = null)
    { 
        $this->MenuPanel->enabled = false;
        waitAsync(350, function () use ($event) {
            $this->MenuPanel->enabled = true;
        });        
        $this->Bugfix->visible = false;
        Animation::moveTo($this->MenuSelector, 200, 354.0, 1.0);
        Animation::moveTo($this->Forms, 150, 1.0, 700.0);   
        waitAsync(150, function () use ($event) {
            $this->Forms->phys->loadScene('Customize');
            Animation::moveTo($this->Forms, 150, 1.0, 170.0);
        });  
        waitAsync(1500, function () use ($event) {
            $this->Bugfix->visible = true;
        });                 
    }
    
    /**
     * @event MenuOtherButton.click-Left 
     **/    
    function doMenuOtherButtonClickLeft(UXMouseEvent $event = null)
    {  
        $this->MenuPanel->enabled = false;
        waitAsync(350, function () use ($event) {
            $this->MenuPanel->enabled = true;
        });       
        $this->Bugfix->visible = false;
        Animation::moveTo($this->MenuSelector, 200, 535.0, 1.0);
        Animation::moveTo($this->Forms, 150, 1.0, 700.0);   
        waitAsync(150, function () use ($event) {
            //$this->Forms->phys->loadScene('Other');
            Animation::moveTo($this->Forms, 150, 1.0, 170.0);
        });  
        waitAsync(1500, function () use ($event) {
            $this->Bugfix->visible = true;
        });           
    }
    
    /**
     * @event MenuSettingsButton.click-Left 
     **/    
    function doMenuSettingsButtonClickLeft(UXMouseEvent $event = null)
    {   
        $this->MenuPanel->enabled = false;
        waitAsync(350, function () use ($event) {
            $this->MenuPanel->enabled = true;
        });      
        $this->Bugfix->visible = false;
        Animation::moveTo($this->MenuSelector, 200, 703.0, 1.0);
        Animation::moveTo($this->Forms, 150, 1.0, 700.0);   
        waitAsync(150, function () use ($event) {
            $this->Forms->phys->loadScene('Settings');
            Animation::moveTo($this->Forms, 150, 1.0, 170.0);
        });  
        waitAsync(1500, function () use ($event) {
            $this->Bugfix->visible = true;
        });           
    }
    
    /**
     * @event HeaderCloseButton.click-Left 
     **/
    function doHeaderCloseButtonClickLeft(UXMouseEvent $event = null)
    {    
        global $headerwarningreturn;
        
        Animation::moveTo($this->HeaderButtonPanel, 300, 900.0, 10.0);
        if ( $this->HeaderWarningButton->x != -110 ) {
            Animation::moveTo($this->HeaderWarningButton, 300, -110.0, 10.0);
            $headerwarningreturn = true;
        }
        waitAsync(150, function () use ($event) {
            Animation::moveTo($this->ClosePanel, 300, 1.0, 1.0);
            $this->AHUDMLogo->enabled = false;
            $this->MenuWelcomeButton->enabled = false;    
            $this->MenuManagerButton->enabled = false;   
            $this->MenuCustomizeButton->enabled = false;  
            $this->MenuOtherButton->enabled = false;
            $this->MenuSettingsButton->enabled = false;   
            $this->Forms->enabled = false;   
        });   
    }
    /**
     * @event HeaderHideButton.click-Left 
     **/
    function doHeaderHideButtonClickLeft(UXMouseEvent $event = null)
    {
        Animation::fadeOut($this, 500, function () use ($event) {
            app()->minimizeForm($this->getContextFormName());
            Animation::fadeIn($this, 100); 
        });
    }
    
    /**
     * @event CloseYesButton.click-Left 
     **/
    function doCloseYesButtonClickLeft(UXMouseEvent $event = null)
    {
        Animation::fadeOut($this, 500, function () use ($event) {
            app()->hideForm($this->getContextFormName());
        });
    }

    /**
     * @event CloseNoButton.mouseDown-Left 
     **/
    function doCloseNoButtonMouseDownLeft(UXMouseEvent $event = null)
    {    
        global $headerwarningreturn;
        
        Animation::moveTo($this->ClosePanel, 300, 1.0, -150.0);
        
        waitAsync(150, function () use ($event) {
            if ( $headerwarningreturn = true ) {
                $headerwarningreturn = false;
                Animation::moveTo($this->HeaderWarningButton, 300, 10.0, 10.0);
            }
            Animation::moveTo($this->HeaderButtonPanel, 300, 830.0, 10.0);
            $this->AHUDMLogo->enabled = true;
            $this->MenuWelcomeButton->enabled = true;    
            $this->MenuManagerButton->enabled = true;   
            $this->MenuCustomizeButton->enabled = true;  
            $this->MenuOtherButton->enabled = true;
            $this->MenuSettingsButton->enabled = true;   
            $this->Forms->enabled = true;   
        });         
    }

    /**
     * @event Bugfix.click-Left 
     **/
    function doBugfixClickLeft(UXMouseEvent $event = null)
    {    
        $this->Bugfix->visible = false;
        Animation::moveTo($this->Forms, 250, 1.0, 170.0);           
    }    

    /**
     * @event HeaderWarningButton.click-Left 
     */
    function doHeaderWarningButtonClickLeft(UXMouseEvent $event = null)
    {    
        global $checkerstatus, $notifications;
        $notifications['connections'] = false;
        $outputinfo['internet'] = ($checkerstatus['internet'] == 1) ? "+" : "-"; 
        $outputinfo['server'] = ($checkerstatus['server'] == 1) ? "+" : "-";
        $outputinfo['github'] = ($checkerstatus['github'] == 1) ? "+" : "-";
        $outputinfo['hudstf'] = ($checkerstatus['hudstf'] == 1) ? "+" : "-";
        
        UXDialog::show("Some services are unavaliable!\n\n"."Internet connection: ".$outputinfo['internet']."\n"."AHUDM Services: ".$outputinfo['server']."\n"."GitHub Services: ".$outputinfo['github']."\n"."HudsTF Services: ".$outputinfo['hudstf']."\n");
    }

    /**
     * @event HeaderWarningButton.mouseEnter 
     */
    function doHeaderWarningButtonMouseEnter(UXMouseEvent $event = null)
    {    
        $this->HeaderWarningButton->blinkAnim->disable();
    }

    /**
     * @event HeaderWarningButton.mouseExit 
     */
    function doHeaderWarningButtonMouseExit(UXMouseEvent $event = null)
    {    
        if ( $notifications['connections'] == true ) {
            $this->HeaderWarningButton->blinkAnim->enable();
        }
    }
}
