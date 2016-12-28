<?php
namespace app\forms;

use php\gui\UXDialog;
use facade\Json;
use php\lib\arr;
use action\Animation;
use php\lib\str;
use php\lang\InterruptedException;
use php\desktop\Runtime;
use php\io\FileStream;
use php\io\Stream;
use php\io\IOException;
use php\io\File;
use php\lib\fs;
use php\gui\paint\UXColor;
use php\gui\UXColorChooser;
use php\gui\framework\AbstractForm;
use php\gui\event\UXEvent; 
use php\gui\event\UXWindowEvent; 
use php\gui\event\UXMouseEvent; 


class Manager extends AbstractForm
{
     
    /**
     * @event ManagerCreateNewBackupButton.click-Left 
     */
    function doManagerCreateNewBackupButtonClickLeft(UXMouseEvent $event = null)
    {
        $this->ManagerCreateNewBackupButton->enabled = false;     Animation::fadeOut($this->ManagerCreateNewBackupButton, 300);    Animation::moveTo($this->ManagerCreateNewBackupButton, 300, 300.0, 144.0); 
        $this->ManagerRefreshBackupsListButton->enabled = false;  Animation::fadeOut($this->ManagerRefreshBackupsListButton, 300); Animation::moveTo($this->ManagerRefreshBackupsListButton, 300, 250.0, 184.0);
        $this->ManagerRemoveBackupButton->enabled = false;        Animation::fadeOut($this->ManagerRemoveBackupButton, 300);       Animation::moveTo($this->ManagerRemoveBackupButton, 300, 200.0, 224); 
        $this->ManagerChangeBackupFolderButton->enabled = false;  Animation::fadeOut($this->ManagerChangeBackupFolderButton, 300); Animation::moveTo($this->ManagerChangeBackupFolderButton, 300, 150.0, 264.0); 
        $this->ManagerOpenBackupFolderButton->enabled = false;    Animation::fadeOut($this->ManagerOpenBackupFolderButton, 300);   Animation::moveTo($this->ManagerOpenBackupFolderButton, 300, 100.0, 304.0); 
        
        Animation::fadeOut($this->ManagerCreateBackupPanel, 100, function () use ($event) {
            $this->ManagerCreateBackupPanel->x = 8;
            $this->ManagerCreateBackupPanel->y = 50;
        });    
        
        waitAsync(150, function () use ($event) {
            Animation::moveTo($this->ManagerBackupFunctionsLabel, 300, 16.0, 8.0);
            Animation::fadeOut($this->ManagerBackupFunctionsLabel, 150, function () use ($event) {
                $this->ManagerBackupFunctionsLabel->text = "Create backup:";    
                $this->ManagerBackupFunctionsLabel->x = 0;
            });
            
            Animation::fadeIn($this->ManagerCreateBackupPanel, 300);
            Animation::moveTo($this->ManagerCreateBackupPanel, 300, 8.0, 30.0);
            
            waitAsync(300, function () use ($event) {  
                Animation::moveTo($this->ManagerBackupFunctionsLabel, 300, 8.0, 8.0); 
                Animation::fadeIn($this->ManagerBackupFunctionsLabel, 300);
            });                   
        });
    }

    /**
     * @event ManagerCreateBackupClose.click-Left 
     */
    function doManagerCreateBackupCloseClickLeft(UXMouseEvent $event = null)
    {   
        Animation::moveTo($this->ManagerBackupFunctionsLabel, 300, 0.0, 8.0);
        Animation::fadeOut($this->ManagerBackupFunctionsLabel, 150, function () use ($event) {
            $this->ManagerBackupFunctionsLabel->text = "Functions:";    
            $this->ManagerBackupFunctionsLabel->x = 16;
        });
        
        Animation::moveTo($this->ManagerCreateBackupPanel, 300, 8.0, 50.0); 
        Animation::fadeOut($this->ManagerCreateBackupPanel, 300, function () use ($event) {  
            $this->ManagerCreateBackupPanel->y = 368;
            $this->ManagerCreateNewBackupButton->enabled = true;     Animation::fadeIn($this->ManagerCreateNewBackupButton, 300);    Animation::moveTo($this->ManagerCreateNewBackupButton, 300, 8.0, 144.0); 
            $this->ManagerRefreshBackupsListButton->enabled = true;  Animation::fadeIn($this->ManagerRefreshBackupsListButton, 300); Animation::moveTo($this->ManagerRefreshBackupsListButton, 300, 8.0, 184.0);
            $this->ManagerRemoveBackupButton->enabled = true;        Animation::fadeIn($this->ManagerRemoveBackupButton, 300);       Animation::moveTo($this->ManagerRemoveBackupButton, 300, 8.0, 224); 
            $this->ManagerChangeBackupFolderButton->enabled = true;  Animation::fadeIn($this->ManagerChangeBackupFolderButton, 300); Animation::moveTo($this->ManagerChangeBackupFolderButton, 300, 8.0, 264.0); 
            $this->ManagerOpenBackupFolderButton->enabled = true;    Animation::fadeIn($this->ManagerOpenBackupFolderButton, 300);   Animation::moveTo($this->ManagerOpenBackupFolderButton, 300, 8.0, 304.0); 
        });  
        
        waitAsync(300, function () use ($event) {  
            Animation::moveTo($this->ManagerBackupFunctionsLabel, 300, 8.0, 8.0); 
            Animation::fadeIn($this->ManagerBackupFunctionsLabel, 300);
        });   
    }

    /**
     * @event ManagerCreateBackupBrowse.mouseDown-Left 
     */
    function doManagerCreateBackupBrowseMouseDownLeft(UXMouseEvent $event = null)
    {    
        $this->backupdirchooser->execute();
    }

    /**
     * @event ManagerCreateBackupCreateButton.mouseDown-Left 
     */
    function doManagerCreateBackupCreateButtonMouseDownLeft(UXMouseEvent $event = null)
    {    
        global $huddir;
        $text = $this->ManagerCreateBackupName->text;
        $symbols = array("\\", "/", "*", "?", "|", '"', "<", ">", ":", "+", " ", ".", "%", "!", "@" );
        for($i = 0; $i < count($symbols); $i++) {
            $text = $this->ManagerCreateBackupName->text;
            $replace = str::replace($text, $symbols[$i], '');
            $this->ManagerCreateBackupName->text = $replace;
        }
        $backupname = $this->ManagerCreateBackupName->text;
        $backupdir = $this->ManagerCreateBackupDirectory->text;
        if ( fs::isDir($backupdir) ) {
            if (! fs::isDir("$backupdir/$backupname") ) {
                $this->copyDir($huddir, "$backupdir\\$backupname");
                $this->form('Main')->toast("Backup created", 2000);               
            } else {
                $this->form('Main')->toast("Backup directory already exist. Remove it or change directory", 2000);               
            } 
        } else {
            $this->form('Main')->toast("Backup directory isn't valide", 2000);
        }
    }

    /**
     * @event ManagerLinksList.click-2x 
     */
    function doManagerLinksListClick2x(UXMouseEvent $event = null)
    {   
        global $huddir;
        $this->iniahudm->path = "$huddir\\ahudm.ini";
        $iniread = $this->iniahudm->toArray();
        $linkselected = $this->ManagerLinksList->selectedItem;
        browse('http:\/\/'.$iniread["Links"]["$linkselected"]);
        
    }

    /**
     * @event ManagerCheckforHUDUpdatesButton.click-Left 
     */
    function doManagerCheckforHUDUpdatesButtonClickLeft(UXMouseEvent $event = null)
    {    
        global $ahudmarray, $checkerstatus;
        
        if ( $checkerstatus['GitHub'] == true ) {
            $this->ManagerCheckforHUDUpdatesButton->enabled = false;
            $getcommits = Stream::getContents("https://api.github.com/repos/".$ahudmarray["Main"]["GitHubUser"]."/".$ahudmarray["Main"]["GitHubRepo"]."/"."commits");
            $commits = Json::decode($getcommits, true);
            
            $updatedate = str::split($commits[0]['commit']['author']['date'], "T");
            $updatetime = str::sub($updatedate[1], 0, 5);
            $this->ManagerLastVersion->text = $updatedate[0]."@$updatetime";
            $this->ManagerLastVersion->tooltipText = "Update: ".$commits[0]['commit']['message'];
    
            $yourvestioncommit = $ahudmarray['Main']['YourVersion'];      
             
            if ( $commits[0]['sha'] != $yourvestioncommit ) {
                $this->ManagerYourVersionBackground->fillColor = UXColor::of('#ff0000');
                Animation::fadeIn($this->ManagerYourVersionBackground, 1000);  
                $commitscount = count($commits);
                for ( $i = 0; $i < $commitscount; $i++ ) {
                    $commitfounded = 'false';
                    if ( $yourvestioncommit == $commits[$i]['sha'] ) {
                        $commitfounded = 'true';
                        $yourversion = str::split($commits[$i]['commit']['author']['date'], "T");
                        $yourversiontime = str::sub($yourversion[1], 0, 5);
                        $this->ManagerYourVersion->text = $yourversion[0]."@$yourversiontime";
                        break;
                    }   
                }
                
                $this->doManagerUpdateHUDButtonClickLeft();
                $this->ManagerUpdateHUDButton->visible = true;
                if ( $commitfounded == 'false') {
                    $this->ManagerGitHubUpdateButton->enabled = false;
                    $this->ManagerGitHubUpdateButton->tooltipText = "This function disabled because your version is very old";  
                } else {
                    $this->ManagerGitHubUpdateButton->enabled = true;
                }   
            } else {
                $this->ManagerYourVersionBackground->fillColor = UXColor::of('#00ff00');
                Animation::fadeIn($this->ManagerYourVersionBackground, 1000);    
            } 
            
            waitAsync(3000, function () use ($event) {
                Animation::fadeOut($this->ManagerYourVersionBackground, 700);
                $this->ManagerCheckforHUDUpdatesButton->enabled = true;      
            }); 
        } else {
            $this->toast('GitHub Services are unavaliavle', 2000);
        }
    }

    /**
     * @event ManagerGitHubAddIssue.click-Left 
     */
    function doManagerGitHubAddIssueClickLeft(UXMouseEvent $event = null)
    {    
        global $ahudmarray;
        browse('https://github.com/'.$ahudmarray["Main"]["GitHubUser"].'/'.$ahudmarray["Main"]["GitHubRepo"].'/issues/new');
    }

    /**
     * @event ManagerCloseUpdateButton.click-Left 
     */
    function doManagerCloseUpdateButtonClickLeft(UXMouseEvent $event = null)
    {   
        $this->ManagerUpdateHUDPanel->enabled = false;
        $this->ManagerCheckforHUDUpdatesButton->enabled = false; 
        Animation::moveTo($this->ManagerUpdateHUDPanel, 300, 302.0, 202.0);
        Animation::fadeOut($this->ManagerUpdateHUDPanel, 300, function () use ($event) {
            $this->ManagerUpdateHUDPanel->x = 272;
            $this->ManagerUpdateHUDPanel->y = 376;  
            $this->ManagerUpdateHUDPanel->enabled = true;
            $this->ManagerCheckforHUDUpdatesButton->enabled = true; 
        });      
    }

    /**
     * @event ManagerUpdateHUDButton.click-Left 
     */
    function doManagerUpdateHUDButtonClickLeft(UXMouseEvent $event = null)
    {    
        Animation::fadeOut($this->ManagerUpdateHUDPanel, 150, function () use ($event) {
            $this->ManagerUpdateHUDPanel->x = 302;
            $this->ManagerUpdateHUDPanel->y = 202;
            Animation::moveTo($this->ManagerUpdateHUDPanel, 300, 272.0, 202.0);
            Animation::fadeIn($this->ManagerUpdateHUDPanel, 300);
        });
    }

    /**
     * @event ManagerGitHubUpdateIssue.click-Left 
     */
    function doManagerGitHubUpdateIssueClickLeft(UXMouseEvent $event = null)
    {    
        $githubissues = Stream::getContents("https://api.github.com/repos/".$ahudmarray["Main"]["GitHubUser"]."/".$ahudmarray["Main"]["GitHubRepo"]."/"."issues");
        $githubissues = Json::decode($githubissues);
        $githubissuesarraycount = count($githubissues);
        for ( $i = 0; $i < $githubissuesarraycount; $i++ ) {
        $githubisseslist .= $githubissues[$i]['title']."|+|";  
        }
        $githubisseslist = str::split($githubisseslist, "|+|");
        $this->form('Manager')->ManagerGitHubIssuesList->items->clear(); 
        $this->form('Manager')->ManagerGitHubIssuesList->items->addAll($githubisseslist);
    }

    /**
     * @event ManagerAboutDescription.click-2x 
     */
    function doManagerAboutDescriptionClick2x(UXMouseEvent $event = null)
    {    
        UXDialog::showAndWait($this->ManagerAboutDescription->text);
    }

    /**
     * @event ManagerOpenHUDFolderButton.click-Left 
     */
    function doManagerOpenHUDFolderButtonClickLeft(UXMouseEvent $event = null)
    {    
        global $huddir;
        open("$huddir\\");
    }
}
