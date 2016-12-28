<?php
namespace app\modules;

use php\io\Stream;
use php\lib\str;
use facade\Json;
use php\io\Stream;
use action\Animation;
use bundle\http\HttpChecker;
use php\gui\framework\AbstractModule;
use php\gui\framework\ScriptEvent; 


class Checker extends AbstractModule
{

    /**
     * @event timerchecker.action 
     */
    function doTimercheckerAction(ScriptEvent $event = null)
    {    
        global $checkerstatus, $checkerrequired, $headerwarningreturn, $notifications, $updateinterval;
        
        $updatecount++;
        
        if ( $this->internetchecker->isOnline() ) {
            $checkerstatus['internet'] = true;
        } else {
            $checkerstatus['internet'] = false;
        }
        
        if ( $this->serverchecker->isOnline() ) {
            $checkerstatus['server'] = true;
        } else {
            $checkerstatus['server'] = false;
        }
        
        if ( $checkerrequired['github'] == true ) {
            if ( $this->githubchecker->isOnline() ) {
                $githubrate = json::decode(file_get_contents('https://api.github.com/rate_limit'));
                $checkerstatus['githubrate'] = $githubrate['rate']['remaining'];
                if ( $githubrate['rate']['remaining'] > 5 ) {
                    $checkerstatus['github'] = true;   
                } else {
                    $checkerstatus['github'] = false;   
                }
            } else {
                $checkerstatus['github'] = false;
            }
        }
        
        if ( $checkerrequired['hudstf'] == true ) {
            if ( $this->hudstfchecker->isOnline() ) {
                $checkerstatus['hudstf'] = true;
            } else {
                $checkerstatus['hudstf'] = false;
            }
        }
        
        if ( $checkerstatus['internet'] == true ) {
            if ( $checkerstatus['hudstf'] != true or $checkerstatus['github'] != true ) {
                if ( $headerwarningreturn != true ) {
                    Animation::moveTo($this->HeaderWarningButton, 300, 10.0, 10.0);
                }   
            } else {
                if ( $headerwarningreturn != true ) {
                    Animation::moveTo($this->HeaderWarningButton, 300, -110.0, 10.0);
                }
            }
        } else {
            if ( $headerwarningreturn != true ) {
                Animation::moveTo($this->HeaderWarningButton, 300, 10.0, 10.0);
            } 
        }
        $updateinterval = 15000;
        if ( $updatecount > 0 ) {
            $this->internetchecker->checkInterval = $updateinterval;
            $this->serverchecker->checkInterval = $updateinterval;
            $this->hudstfchecker->checkInterval = $updateinterval;
            $this->githubchecker->checkInterval = $updateinterval;
            $this->timerchecker->interval = $updateinterval;
        }
        
        print_r($checkerstatus);
    }

}
