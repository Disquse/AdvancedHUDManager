<?php
namespace app\forms;

use app\modules\VDF;
use php\lang\System;
use timer\AccurateTimer;
use php\time\TimeFormat;
use php\time\Time;
use php\gui\UXDatePicker;
use php\gui\paint\UXColor;
use php\io\IOException;
use php\lang\IllegalArgumentException;
use ArrayAccess;
use php\lang\Thread;
use php\gui\UXImageArea;
use php\gui\UXImage;
use php\format\JsonProcessor;
use facade\Json;
use php\io\Stream;
use php\lib\arr;
use php\gui\UXComboBox;
use php\lib\fs;
use php\util\RegexException;
use php\lib\str;
use php\gui\framework\AbstractForm;
use php\gui\event\UXWindowEvent; 
use action\Animation; 
use php\gui\event\UXMouseEvent; 
use php\gui\event\UXEvent; 
use php\gui\event\UXKeyEvent; 
use php\util\Regex;

class Welcome extends AbstractForm
{
    /**
     * @event WelcomeCreateProfileButton.click-Left 
     **/
    function doWelcomeCreateProfileButtonClickLeft(UXMouseEvent $event = null)
    {   
        $this->WelcomeCreateProfileMethodsPanel->enabled = false;    
        Animation::fadeOut($this->WelcomeCreateProfilePanel, 100, function () use ($event) {
            $this->WelcomeCreateProfilePanel->x = 16;
            $this->WelcomeCreateProfilePanel->y = 188;
        });    
        
        waitAsync(150, function () use ($event) {
            Animation::moveTo($this->WelcomeCreateProfileLabel, 300, 32.0, 136.0);
            Animation::fadeOut($this->WelcomeCreateProfileLabel, 150, function () use ($event) {
                $this->WelcomeCreateProfileLabel->text = "Create profile:";    
                $this->WelcomeCreateProfileLabel->x = 0;
            });
            Animation::fadeOut($this->WelcomeCreateProfileMethodsPanel, 300);
            Animation::moveTo($this->WelcomeCreateProfileMethodsPanel, 300, 16.0, 128.0, function () use ($event) {
                $this->WelcomeCreateProfileMethodsPanel->y = 416;
            });
            Animation::fadeIn($this->WelcomeCreateProfilePanel, 300);
            Animation::moveTo($this->WelcomeCreateProfilePanel, 300, 16.0, 168.0);   
            waitAsync(300, function () use ($event) {            
                Animation::moveTo($this->WelcomeCreateProfileLabel, 300, 16.0, 136.0);   
                Animation::fadeIn($this->WelcomeCreateProfileLabel, 300);
            });                  
                
        });
    }

    /**
     * @event WelcomeImportProfileButton.click-Left 
     **/
    function doWelcomeImportProfileButtonClickLeft(UXMouseEvent $event = null)
    {     
        $this->WelcomeCreateProfileMethodsPanel->enabled = false;
        Animation::fadeOut($this->WelcomeImportProfilePanel, 100, function () use ($event) {
            $this->WelcomeImportProfilePanel->x = 16;
            $this->WelcomeImportProfilePanel->y = 188;
        });    
        
        waitAsync(150, function () use ($event) {
            Animation::moveTo($this->WelcomeCreateProfileLabel, 300, 32.0, 136.0);
            Animation::fadeOut($this->WelcomeCreateProfileLabel, 150, function () use ($event) {
                $this->WelcomeCreateProfileLabel->text = "Import profile:";    
                $this->WelcomeCreateProfileLabel->x = 0;
            });
            Animation::fadeOut($this->WelcomeCreateProfileMethodsPanel, 300);
            Animation::moveTo($this->WelcomeCreateProfileMethodsPanel, 300, 16.0, 128.0, function () use ($event) {
                $this->WelcomeCreateProfileMethodsPanel->y = 416;
            });
            Animation::fadeIn($this->WelcomeImportProfilePanel, 300);
            Animation::moveTo($this->WelcomeImportProfilePanel, 300, 16.0, 168.0);  
             
            waitAsync(300, function () use ($event) {            
                Animation::moveTo($this->WelcomeCreateProfileLabel, 300, 16.0, 136.0);   
                Animation::fadeIn($this->WelcomeCreateProfileLabel, 300);
            });                  
                
        });        
    }

    /**
     * @event WelcomeCreateProfilePanelClose.click-Left 
     **/
    function doWelcomeCreateProfilePanelCloseClickLeft(UXMouseEvent $event = null)
    {          
        $this->WelcomeCreateProfilePanel->visible = true;      
        $this->WelcomeCreateProfilePanel->enabled = false;    
        $this->WelcomeCreateProfileMethodsPanel->enabled = true;  
        $this->WelcomeCreateProfileHUDDir->text = "";
        $this->WelcomeCreateProfileName->text = ""; 
        Animation::fadeOut($this->WelcomeCreateProfileMethodsPanel, 100, function () use ($event) {
            $this->WelcomeCreateProfileMethodsPanel->y = 168;
        });    
        
        waitAsync(150, function () use ($event) {
            Animation::moveTo($this->WelcomeCreateProfileLabel, 300, 0.0, 136.0);
            Animation::fadeOut($this->WelcomeCreateProfileLabel, 150, function () use ($event) {
                $this->WelcomeCreateProfileLabel->text = "Or create a new one:";    
                $this->WelcomeCreateProfileLabel->x = 32;
            });
            $this->WelcomeCreateProfileMethodsPanel->y = 138;      
            waitAsync(150, function () use ($event) {      
                Animation::fadeIn($this->WelcomeCreateProfileMethodsPanel, 300);
                Animation::moveTo($this->WelcomeCreateProfileMethodsPanel, 300, 16.0, 168.0);
            });
            Animation::fadeOut($this->WelcomeCreateProfilePanel, 300);
            Animation::moveTo($this->WelcomeCreateProfilePanel, 300, 16.0, 178.0);   
            waitAsync(300, function () use ($event) {            
                Animation::moveTo($this->WelcomeCreateProfileLabel, 300, 16.0, 136.0);   
                Animation::fadeIn($this->WelcomeCreateProfileLabel, 300);
                $this->WelcomeCreateProfilePanel->y = 416;
                $this->WelcomeCreateProfilePanel->enabled = true;  
            });                      
        });
    }   

    /**
     * @event ImportProfilePanelClose.click-Left 
     **/
    function doImportProfilePanelCloseClickLeft(UXMouseEvent $event = null)
    {           
        $this->WelcomeImportProfilePanel->enabled = false;      
        $this->WelcomeCreateProfileMethodsPanel->enabled = true;    
        Animation::fadeOut($this->WelcomeCreateProfileMethodsPanel, 100, function () use ($event) {
            $this->WelcomeCreateProfileMethodsPanel->y = 168;
        });    
        
        waitAsync(150, function () use ($event) {
            Animation::moveTo($this->WelcomeCreateProfileLabel, 300, 0.0, 136.0);
            Animation::fadeOut($this->WelcomeCreateProfileLabel, 150, function () use ($event) {
                $this->WelcomeCreateProfileLabel->text = "Or create a new one:";    
                $this->WelcomeCreateProfileLabel->x = 32;
            });
            $this->WelcomeCreateProfileMethodsPanel->y = 138;          
            waitAsync(150, function () use ($event) {      
                Animation::fadeIn($this->WelcomeCreateProfileMethodsPanel, 300);
                Animation::moveTo($this->WelcomeCreateProfileMethodsPanel, 300, 16.0, 168.0);
            });
            
            Animation::fadeOut($this->WelcomeImportProfilePanel, 300);
            Animation::moveTo($this->WelcomeImportProfilePanel, 300, 16.0, 178.0);   
            waitAsync(300, function () use ($event) {            
                Animation::moveTo($this->WelcomeCreateProfileLabel, 300, 16.0, 136.0);   
                Animation::fadeIn($this->WelcomeCreateProfileLabel, 300);
                $this->WelcomeImportProfilePanel->y = 416;
                $this->WelcomeImportProfilePanel->enabled = true;  
            });                  
                
        });         
    }
    /**
     * @event WelcomeCreateProfileBrowseButton.click-Left 
     **/
    function doWelcomeCreateProfileBrowseButtonClickLeft(UXMouseEvent $event = null)
    {        
        $this->huddirchooser->execute();        
    }

    /**
     * @event WelcomeCreateProfilePanelCreateButton.click-Left 
     **/
    function doWelcomeCreateProfilePanelCreateButtonClickLeft(UXMouseEvent $event = null)
    {    
        $text = $this->WelcomeCreateProfileName->text;
        $symbols = array("\\", "/", "*", "?", "|", '"', "<", ">", ":", "+", " ", ".", "%", "!", "@" );
        for ( $i = 0; $i < count($symbols); $i++ ) {
            $text = $this->WelcomeCreateProfileName->text;
            $replace = str::replace($text, $symbols[$i], '');
            $this->WelcomeCreateProfileName->text = $replace;
        }
        $profilename = $this->WelcomeCreateProfileName->text;
        if (! fs::isDir("profiles") ) { fs::makeDir("profiles"); }
         
        if ( str::length($profilename) > 3 and str::length($profilename) < 30 ) {
            $profiles = $this->profiles->toArray();
            if ( $profiles[$profilename] ) {
                $this->form('Main')->toast("This profile name already used!", 2000);
            } else {
                $hudpath = $this->WelcomeCreateProfileHUDDir->text;
                if( fs::isDir($hudpath) and fs::isFile("$hudpath\\scripts\\hudlayout.res") ) {
                    if ( is_dir("profiles\\$profilename") ) { fs::clean("profiles\\$profilename"); }
                    $this->profiles->set('ProfileName', $profilename, $profilename);
                    $this->profiles->set('HUDDirectory', $hudpath, $profilename);
                    $this->form('Main')->toast("Profile '$profilename' was created!", 2000);
                    
                    $this->profilesrefresh->call();
                    $this->doWelcomeCreateProfilePanelCloseClickLeft();
                } else {
                    $this->form('Main')->toast("Selected folder is incorrect! Missing core HUD files", 3000); 
                }
            }
        } else {
            $this->form('Main')->toast("Wrong profile name size. Name must be from 3 to 30 characters", 3000);  
        }
    }

    /**
     * @event WelcomeProfileRefreshButton.click-Left 
     **/
    function doWelcomeProfileRefreshButtonClickLeft(UXMouseEvent $event = null)
    {                                            
        $this->profilesrefresh->call();
        $this->form('Main')->toast("Profiles refreshed", 2000);     
    }

    /**
     * @event WelcomeNormalModeButton.click-Left 
     **/
    function doWelcomeNormalModeButtonClickLeft(UXMouseEvent $event = null)
    {    
        $this->showPreloader();      
        waitAsync(1000, function () use ($event) {
            global $github, $hudstf, $ahudmarray, $gamedir, $huddir, $checkerstatus;
            
            $this->form('Main')->MenuManagerButton->enabled = false;
            $this->form('Main')->MenuCustomizeButton->enabled = false;
            $this->form('Main')->MenuOtherButton->enabled = false;
            
            $selectedprofile = $this->WelcomeProfileList->selected;
            
            if ( $selectedprofile != "" ) {  // If selected profile isn't blank    
                $profileread = $this->profiles->toArray();  
                  
                print_r($profileread);   
                
                if ( $profileread[$selectedprofile] and $profileread[$selectedprofile]['ProfileName'] and $profileread[$selectedprofile]['HUDDirectory'] ) { // Если файла профиля не существует
                    $profilename = $profileread[$selectedprofile]["ProfileName"];
                    $huddir = $profileread[$selectedprofile]["HUDDirectory"]; 
                    $huddir = str::replace($huddir, "\/", "//"); // Slashes...
                    
                    print_r($huddir); 
                    
                    if ( fs::isDir("$huddir") ) {          
                        if ( fs::isFile("$huddir\\info.vdf") ) { // Check for info.vdf                       
                            $ahudmarray = VDF::decodeFile("$huddir\\info.vdf");
                            $ahudmarray = $ahudmarray['AHUDM'];
                        
                            // Scanning for supported services
                            if (str::trim($ahudmarray["Main"]["GitHubRepo"]) != "" ) {        
                                if ( $checkerstatus['github'] == true ) {   
                                    $githubrate = stream::getContents("https://api.github.com/rate_limit");
                                    $githubrate = Json::decode($githubrate);

                                    if ( $githubrate["resources"]["core"]["remaining"] > 4 ) {
                                        $githubrepo = str::split($ahudmarray["Main"]["GitHubRepo"], "/");
                                        $ahudmarray["Main"]["GitHubUser"] = $githubrepo[0];
                                        $ahudmarray["Main"]["GitHubRepo"] = $githubrepo[1];
                                        
                                        $github = Stream::getContents("https://api.github.com/repos/".$ahudmarray["Main"]["GitHubUser"]."/".$ahudmarray["Main"]["GitHubRepo"]);
                                        $github = Json::decode($github);
                                        
                                        if ( $github['message'] or $github['message'] != "Not Found" ) {
                                            $githubstatus = "true";
                                        } else {
                                           $githubstatus = "false";
                                        }
                                        
                                    } else {
                                        $githubstatus = "false";
                                        $this->form('Main')->toast("Warning! Your IP-adress has exhausted GitHub API limits. GitHub features disabled.", 3000);
                                    }
                                } else {
                                    $githubstatus = "false";
                                }                                       
                            }   
                            
                            if (str::trim($ahudmarray["Main"]["HudsTFID"]) != "" ) {
                                if ( $checkerstatus['hudstf'] == true ) {    
                                    $hudstf = stream::getContents('http://huds.tf/forum/showthread.php?tid='.$ahudmarray["Main"]["HudsTFID"]);
                                    $hudstfcheck = Regex::of('<h1 style="font-size:70px; margin-top: 0px; margin-bottom: 20px;">(.*?)</h1>')->with($hudstf);
                                    if ( $hudstfcheck->find() ) {
                                        $hudstfstatus = "true"; 
                                    } else {
                                        $hudstfstatus = "false";
                                    }
                                } else {
                                    $hudstfstatus = "false";
                                }
                            }  
                            
                            // Links
                            $this->form('Manager')->ManagerLinksList->items->clear();
                            if ( $ahudmarray["Links"] ) { 
                                $this->form('Manager')->ManagerLinksLabel->visible = true;
                                $this->form('Manager')->ManagerLinksList->visible = true;                        
                                $keys = arr::keys($ahudmarray["Links"]);                         
                                $this->form('Manager')->ManagerLinksList->items->addAll($keys);
                            } else {
                                $this->form('Manager')->ManagerLinksLabel->visible = false;
                                $this->form('Manager')->ManagerLinksList->visible = false;
                            }
                            
                            // Main source of information about HUD
                            if ( str::trim($ahudmarray["Main"]["Source"]) != "" ) {
                                if ( $ahudmarray["Main"]["Source"] == "GitHub" and $githubstatus == "true" ) {
                                    $hudsource = "GitHub";
                                    // If GitHub and GitHub works stable
                                } elseif ($ahudmarray["Main"]["Source"] == "HudsTF" and $hudstfstatus == "true" ) {
                                    $hudsource = "HudsTF";
                                    // If HudsTF and HudsTF works stable
                                } else {
                                    $hudsource = "Manual";
                                    // If no main source, then use info.vdf
                                }
                            }
                            
                            
                            // Parsing main info and statisctic 
                            if ( $githubstatus == "true" ) {
                                $githubstatistic = $github;
                                $this->form('Manager')->ManagerGitHubStatisticPanel->visible = true;
                                $this->form('Manager')->ManagerGitHubTotalDownloads->text = $githubstatistic['stargazers_count'];
                                $this->form('Manager')->ManagerGitHubTotalWatchers->text = $githubstatistic['watchers_count'];
                                $this->form('Manager')->ManagerGitHubTotalForks->text = $githubstatistic['forks_count'];
                                $this->form('Manager')->ManagerGitHubTotalStars->text = $githubstatistic['stargazers_count'];
                                $this->form('Manager')->ManagerGitHubTotalIssues->text = $githubstatistic['open_issues_count'];
                                    
                                $githubgetdownloadsinfo = function() {
                                    global $ahudmarray, $githubdownloads;
                                    $githubdownloads = Stream::getContents("https://api.github.com/repos/".$ahudmarray["Main"]["GitHubUser"]."/".$ahudmarray["Main"]["GitHubRepo"]."/"."downloads");
                                    $githubdownloads = Json::decode($githubdownloads);
                                };    
                                $githubusedownloadsinfo = function() {
                                    global $githubdownloads;
                                    foreach ($githubdownloads as $githubdownload){
                                        $githubdownloadscount += $githubdownload['download_count'];
                                    }
                                    $this->form('Manager')->ManagerGitHubTotalDownloads->text = $githubdownloadscount;
                                    $this->form('Manager')->ManagerGitHubIssuesLabel->visible = true;
                                    $this->form('Manager')->ManagerGitHubIssuesList->visible = true;
                                    $this->form('Manager')->ManagerGitHubAddIssue->visible = true;
                                };
                                $this->form('Main')->aSync($githubgetdownloadsinfo, $githubusedownloadsinfo);
                                               
                            } else {
                                $this->form('Manager')->ManagerGitHubStatisticPanel->visible = false;
                                $this->form('Manager')->ManagerGitHubIssuesLabel->visible = false;
                                $this->form('Manager')->ManagerGitHubIssuesList->visible = false;
                                $this->form('Manager')->ManagerGitHubAddIssue->visible = false;
                                $this->form('Manager')->ManagerGitHubUpdateIssue->visible = false;
                            }
                            
                            if ( $hudstfstatus == "true" ) {
                                $hudstferrors = 0;
                                $hudstfdownloads = Regex::of('<h2>Downloads: (.*?)</h2>')->with($hudstf);
                                $this->form('Manager')->ManagerHudsTFStatisticPanel->visible = true;
                                if ( $hudstfdownloads->find() ) {   
                                    $hudstfdownloads = str::replace($hudstfdownloads->group(1), ",", "");
                                    $this->form('Manager')->ManagerHudsTFTotalDownloads->text = $hudstfdownloads;
                                } else {
                                    $hudstferrors++;
                                    $this->form('Manager')->ManagerHudsTFTotalDownloads->text = "ERROR";
                                }
                                
                                $hudstfviews = Regex::of('<h2>Views: (.*?)</h2>')->with($hudstf);
                                if ( $hudstfviews->find() ) {
                                    $this->form('Manager')->ManagerHudsTFTotalViews->text = $hudstfviews->group(1);
                                } else {
                                    $hudstferrors++;
                                    $this->form('Manager')->ManagerHudsTFTotalViews->text = "ERROR";
                                }
                                
                                $hudstfrating = Regex::of('<li style=(.*?)class="current_rating"(.*?)>(.*?)</li>')->with($hudstf);
                                if ( $hudstfrating->find() ) {
                                    $hudstfrating = str::split($hudstfrating->group(3), "-");
                                    $hudstfrating[1] = str::replace($hudstfrating[1], "Average", "");
                                    $this->form('Manager')->ManagerHudsTFTotalRating->tooltipText = $hudstfrating[0];
                                    $this->form('Manager')->ManagerHudsTFTotalRating->text = $hudstfrating[1]."/ 5";
                                } else {
                                    $hudstferrors++;
                                    $this->form('Manager')->ManagerHudsTFTotalRating->text = "ERROR";
                                }
                                if ( $hudstferrors == "3" ) {
                                    $this->form('Manager')->ManagerHudsTFStatisticPanel->visible = false;
                                }
                            } else {
                                $this->form('Manager')->ManagerHudsTFStatisticPanel->visible = false;
                            }
                            
                            
                            // Set-up
                            if ( $hudsource == "GitHub" and $githubstatus == "true" and $hudsource == "GitHub") {
                                if ( str::trim($ahudmarray["Main"]["HUDName"]) != "" ) {
                                    $this->form('Manager')->ManagerHUDName->text = $ahudmarray["Main"]["HUDName"];   
                                } else {
                                    $this->form('Manager')->ManagerHUDName->text = $github['name'];
                                }
                                
                                if ( str::trim($ahudmarray["Main"]["HUDAuthor"]) != "" ) {
                                    $this->form('Manager')->ManagerHUDAuthors->text = $ahudmarray["Main"]["HUDAuthor"];    
                                } else {
                                    $this->form('Manager')->ManagerHUDAuthors->text = $github['owner']['login'];
                                }
                                
                                if ( str::trim($ahudmarray["Main"]["HUDDescription"]) != "" ) {
                                    $this->form('Manager')->ManagerAboutDescription->text = $ahudmarray["Main"]["HUDDescription"];    
                                } else {
                                    $this->form('Manager')->ManagerAboutDescription->text = $github['description'];
                                }
                                
                                $this->form('Manager')->ManagerHUDLogo->visible = true;
                                $this->form('Manager')->ManagerHUDLogoBG->visible = true;
                                $this->form('Manager')->ManagerHUDName->x = 96;
                                $this->form('Manager')->ManagerHUDAuthors->x = 98;
                                
                                if ( str::trim($ahudmarray["Main"]["HUDLogo"]) != "" and $checkerstatus['interner'] == true ) {
                                    $this->form('Manager')->ManagerHUDLogo->image = new UXImage('https://'.$ahudmarray["Main"]["HUDLogo"]);
                                } elseif ( str::trim($ahudmarray["Main"]["HUDLogoLocal"]) != "" and fs::isFile("$huddir\\".$ahudmarray["Main"]["HUDLogoLocal"])) {
                                    $this->form('Manager')->ManagerHUDLogo->image = new UXImage($ahudmarray["Main"]["HUDLogoLocal"]);
                                } elseif ( $checkerstatus['github'] == true and $checkerstatus['internet'] == true ) {
                                    $this->form('Manager')->ManagerHUDLogo->image = new UXImage($github["owner"]["avatar_url"]);
                                } else {
                                    $this->form('Manager')->ManagerHUDLogo->visible = false;
                                    $this->form('Manager')->ManagerHUDLogoBG->visible = false;
                                    $this->form('Manager')->ManagerHUDName->x = 18;
                                    $this->form('Manager')->ManagerHUDAuthors->x = 20;
                                }   
                            } elseif ( $hudsource == "HudsTF" and $hudstfstatus == "true" or $githuberrors != 0 ) {                          
                                if ( str::trim($ahudmarray["Main"]["HUDName"]) != "" ) {
                                    $this->form('Manager')->ManagerHUDName->text = $ahudmarray["Main"]["HUDName"];    
                                  } else {
                                    $hudstfhudname = Regex::of('<h1 style="font-size:70px; margin-top: 0px; margin-bottom: 20px;">(.*?)</h1>')->with($hudstf);    
                                    if ( $hudstfhudname->find() ) {
                                        $this->form('Manager')->ManagerHUDName->text = $hudstfhudname->group(1);
                                    } else {
                                        $this->form('Manager')->ManagerHUDName->text = "ERROR";   
                                    }
                                }
                                if ( str::trim($ahudmarray["Main"]["HUDName"]) != "" ) {
                                    $this->form('Manager')->ManagerHUDAuthors->text = $ahudmarray["Main"]["HUDName"];    
                                } else {
                                    $hudstfhudauthor = Regex::of('<span style=(.*?)<a href="http://huds.tf/forum/member.php?action=profile&amp;uid=(.*?)">(.*?)</a>(.*?)</span>')->with($hudstf);    
                                    if ( $hudstfhudauthor->find() ) {
                                        $this->form('Manager')->ManagerHUDAuthors->text = $hudstfhudauthor->group(3);
                                    } else {
                                        $this->form('Manager')->ManagerHUDAuthors->text = "ERROR";   
                                    }
                                }
                            } else {
                                $manualerrors = 0;                   
                                if ( str::trim($ahudmarray["Main"]["HUDName"]) != "" ) {
                                    $this->form('Manager')->ManagerHUDName->text = $ahudmarray["Main"]["HUDName"];   
                                } else {
                                    $manualerrors++;
                                    $this->form('Manager')->ManagerHUDName->text = "Unknown";
                                }
                                
                                if ( str::trim($ahudmarray["Main"]["HUDAuthor"]) != "" ) {
                                    $this->form('Manager')->ManagerHUDAuthors->text = $ahudmarray["Main"]["HUDAuthor"];    
                                } else {
                                    $manualerrors++;
                                    $this->form('Manager')->ManagerHUDAuthors->text = "Unknown";
                                }
                                
                                if ( str::trim($ahudmarray["Main"]["HUDDescription"]) != "" ) {
                                    $this->form('Manager')->ManagerAboutDescription->text = $ahudmarray["Main"]["HUDDescription"];    
                                } else {
                                    $manualerrors++;
                                    $this->form('Manager')->ManagerAboutDescription->text = "Unknown";
                                }
                                
                                if ( str::trim($ahudmarray["Main"]["HUDLogo"]) != "" and $checkerstatus['interner'] == true ) {
                                    $this->form('Manager')->ManagerHUDLogo->image = new UXImage('https://'.$ahudmarray["Main"]["HUDLogo"]);
                                } elseif ( str::trim($ahudmarray["Main"]["HUDLogoLocal"]) != "" and fs::isFile("$huddir\\".$ahudmarray["Main"]["HUDLogoLocal"])) {
                                    $this->form('Manager')->ManagerHUDLogo->image = new UXImage($ahudmarray["Main"]["HUDLogoLocal"]);
                                } else {
                                    $this->form('Manager')->ManagerHUDLogo->visible = false;
                                    $this->form('Manager')->ManagerHUDLogoBG->visible = false;
                                    $this->form('Manager')->ManagerHUDName->x = 18;
                                    $this->form('Manager')->ManagerHUDAuthors->x = 20;
                                } 
                                
                                if ( $ahudmarray["Main"]["GitHubRepo"] and $ahudmarray["Main"]["HudsTFTID"] ) {
                                    $this->form('Manager')->ManagerUpdateHUDButton->enabled = true; $this->form('Manager')->ManagerUpdateHUDButton->tooltipText = 'Downloade newest version of HUD';
                                    $this->form('Manager')->ManagerCheckforHUDUpdatesButton->enabled = true; $this->form('Manager')->ManagerUpdateHUDButton->tooltipText = 'Check for updates';
                                } else {
                                    $this->form('Manager')->ManagerUpdateHUDButton->enabled = false; $this->form('Manager')->ManagerUpdateHUDButton->tooltipText = 'No information about this HUD in info.vdf';
                                    $this->form('Manager')->ManagerCheckforHUDUpdatesButton->enabled = false; $this->form('Manager')->ManagerCheckforHUDUpdatesButton->tooltipText = 'No information about this HUD in info.vdf';
                                }
                                
                                if ( $manualerrors == 3 ) {
                                    $this->form('Manager')->ManagerHUDName->text = "Unknown HUD";
                                    $this->form('Manager')->ManagerHUDAuthors->text = "This HUD doesnt support AHUDM. Some functions are unavaliable!";
                                    $this->form('Manager')->ManagerYourVersion->visible = false; $this->form('Manager')->ManagerYourVersionLabel->visible = false;
                                    $this->form('Manager')->ManagerLastVersion->visible = false; $this->form('Manager')->ManagerLastVersionLabel->visible = false;
                                    $this->form('Manager')->ManagerAHUDMStatisticPanel->visible = false;
                                    
                                } else {
                                    $this->form('Manager')->ManagerYourVersion->visible = true;
                                    $this->form('Manager')->ManagerLastVersion->visible = true;
                                    $this->form('Manager')->ManagerAHUDMStatisticPanel->visible = true;
                                }
                            }
                            
                            // Hud Status (installed or not)           
                            if ( fs::isDir($gamedir.'\\tf\\custom\\'.fs::nameNoExt($huddir)) and strpos($huddir, $gamedir.'\\tf\\custom') !== false ) {
                                $this->form('Manager')->ManagerHUDStatus->text = "Installed";
                            } else {
                                $this->form('Manager')->ManagerHUDStatus->text = "Not installed";
                            }
                            
                            /*
                            if (! is_dir("profiles\\$selectedprofile") and is_file("profiles\\$selectedprofile\\profilestorage.dat") ) {
                                $this->profilestorage->path = "profiles\\$selectedprofile\\profilestorage.dat";
                                print_r($this->profilestorage->toArray());
                            } else {
                                fs::makeFile("profiles\\$selectedprofile\\profilestorage.dat");
                                $this->profilestorage->path = "profiles\\$selectedprofile\\profilestorage.dat";
                                if ( $ahudmarray['AHUDM'] ) {
                                    $this->profilestorage->put($ahudmarray['AHUDM'], 'InfoVDF');
                                }
                                $vdfkillfeed = VDF::decodeFile("$huddir\\scripts\\hudlayout.res");
                                if ( $vdfkillfeed['Resource/HudLayout.res']['HudDeathNotice'] ) {
                                    $this->profilestorage->put($vdfkillfeed['Resource/HudLayout.res']['HudDeathNotice'], 'DefaultKillfeed');
                                }
                                
                            }
                            */
                            

                            $preupdatecustomizes = function() {
                                $this->form('Customize')->doDamageIndicatorUpdateButtonClickLeft();
                                $this->form('Customize')->doKillfeedUpdateButtonClickLeft();
                                
                            }; 
                            $loadingfinished = function() { 
                                $this->form('Main')->toast("Profile was successfully loaded!", 2000);
                                $this->form('Main')->MenuManagerButton->enabled = true;
                                $this->form('Main')->MenuCustomizeButton->enabled = true;
                                $this->form('Main')->MenuOtherButton->enabled = true;
                            };
                            $this->form('Main')->aSync($preupdatecustomizes, $loadingfinished);
                            $this->form('Main')->doMenuManagerButtonClickLeft(); 
                            
                        } else {
                            $this->form('Main')->toast("Info.vdf file was missed and now restored. Please, wait.", 3000); 
                            VDF::encodeFile('$huddir\\info.vdf', '"hud" { "ui_version" "1" }');
                            // Restart
                            $this->doWelcomeNormalModeButtonClickLeft();
                        }                                   
                    } else {
                        $this->form('Main')->toast("HUD directory is missed! Please, re-create profile.", 2000);
                        $this->doWelcomeCreateProfileButtonClickLeft(); 
                        $this->WelcomeCreateProfileName->text = $profilename;                       
                        $this->WelcomeCreateProfileHUDDir->text = $huddir;
                        
                        fs::delete("profiles\\$profilename");
                        $this->profiles->removeSection($profilename);
                        $thos->profiles->save();
                        
                        $this->profilesrefresh->call();
                    }
                } else {
                    $this->form('Main')->toast("Selected profile is broken or removed!", 2000);
                    $this->profilesrefresh->call();
                }
            }  
            $this->hidePreloader();                     
        });    
    }

    /**
     * @event WelcomeNewsBrowser.running 
     */
    function doWelcomeNewsBrowserRunning(UXEvent $event = null)
    {    
        $this->WelcomeNewsProgress->visible = true;
    }

    /**
     * @event WelcomeNewsBrowser.load 
     */
    function doWelcomeNewsBrowserLoad(UXEvent $event = null)
    {    
        $this->WelcomeNewsProgress->visible = false;
    }

    /**
     * @event construct 
     */
    function doConstruct(UXEvent $event = null)
    {    
        $constructstart = function() { 
            global $greetingtext;
            $userenv = System::getEnv();
            if ( is_file('settings.ini') ) {
                $greetingtext = 'WELCOME BACK, '.$userenv['USERNAME'].' !';
            } else {
                $greetingtext = 'WELCOME, '.$userenv['USERNAME'].' !';
            } 
        };
        
        $constructfinish = function() { 
            global $greetingtext;
            $this->HeaderWelcomeLabel->text = $greetingtext;
            $this->WelcomeNewsBrowser->url = 'http://ahudm.disquse.ru/news';
            $this->profilesrefresh->call();
        };
        $this->form('Main')->aSync($constructstart, $constructfinish);
    }
}
