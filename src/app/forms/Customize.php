<?php
namespace app\forms;

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


class Customize extends AbstractForm
{

    /**
     * @event CustomizeTabs.change 
     */
    function doCustomizeTabsChange(UXEvent $event = null)
    {    
        $this->CustomizeSubLabel->text = "| ".strtoupper($this->CustomizeTabs->tabs[$this->CustomizeTabs->selectedIndex]->text);
        switch ($this->CustomizeTabs->selectedIndex) {
            case 0:
                $this->CustomizeAdvices->text = 'Overrides';
                break;
            case 1:
                $this->CustomizeAdvices->text = 'Health crosses';
                break;
            case 2:
                $this->CustomizeAdvices->text = 'Colors';
                break;
            case 3:
                $this->CustomizeAdvices->text = 'Fonts';
                break;
            case 4:
                $this->CustomizeAdvices->text = 'This helps aims';
                break;
            case 5:
                $this->CustomizeAdvices->text = 'It helps to understand when you hit';
                break;
            case 6:
                $this->CustomizeAdvices->text = 'Your class icon settings';
                break;
            case 7:
                $this->CustomizeAdvices->text = 'Who attacked me!?';
                break;
            case 8:
                $this->CustomizeAdvices->text = 'hud_saytext 0 - <3';
                break;
            case 9:
                $this->CustomizeAdvices->text = 'You can change fonts in FONTS (!?) tab';
                break;
        }    
    }
    
    /** 
     * K I L L F E E D
     */

    /**
     * @event KillfeedUpdateButton.click-Left 
     */
    function doKillfeedUpdateButtonClickLeft(UXMouseEvent $event = null)
    {    
        $this->showPreloader();
        global $huddir, $hudlayout;
        if ( is_file("$huddir\\scripts\\hudlayout.res") and file_get_contents("$huddir\\scripts\\hudlayout.res") != NULL ) {
            $hudlayout = VDF::decodeFile("$huddir\\scripts\\hudlayout.res");
            
            if ( $hudlayout['resource/hudlayout.res']['huddeathnotice'] and ! empty($hudlayout['resource/hudlayout.res']['huddeathnotice']) ) {
                $this->KillfeedSettingsPanel->visible = true; $this->KillfeedPreviewPanel->visible = true;
                $this->KillfeedColorsPanel->visible = true; $this->KillfeedApplyButton->visible = true;
                $this->KillfeedPresetsButton->visible = true; $this->KillfeedNotFoundedAddButton->visible = false;
                $this->KillfeedNotFoundedDescription->visible = false; $this->KillfeedNotFoundedTitle->visible = false;
                $killfeed = $hudlayout['resource/hudlayout.res']['huddeathnotice'];
                
                if( strtolower($killfeed['fieldname']) == 'huddeathnotice' ) {
                    if( $killfeed['cornerradius'] != "" ) {
                        $this->KillfeedCornerRadiusAdd->visible = false;
                        $this->KillfeedCornerRadius->text = $killfeed['cornerradius'];
                        if ( $killfeed['cornerradius'] > 10 or $killfeed['cornerradius'] < 0 ) {
                            $this->KillfeedCornerRadius->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedCornerRadius->classes->add('valuewarning');
                        } else {
                            $this->KillfeedCornerRadius->classes->remove('valuewarning');
                            $this->KillfeedCornerRadius->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedCornerRadiusAdd->visible = true;
                    } 
                    if( $killfeed['linespacing'] != "" ) {
                        $this->KillfeedLineSpacingAdd->visible = false;
                        $this->KillfeedLineSpacing->text = $killfeed['linespacing'];
                        if ( $killfeed['linespacing'] > 15 or $killfeed['linespacing'] < 0 ) {
                            $this->KillfeedLineSpacing->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedLineSpacing->classes->add('valuewarning');
                        } else {
                            $this->KillfeedLineSpacing->tooltipText = "Acceptable value";
                            $this->KillfeedLineSpacing->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedLineSpacingAdd->visible = true;
                    }
                    if( $killfeed['maxdeathnotices'] != "" ) {
                        $this->KillfeedMaxDeathNoticesAdd->visible = false;
                        $this->KillfeedMaxDeathNotices->text = $killfeed['maxdeathnotices'];
                        if ( $killfeed['maxdeathnotices'] > 20 or $killfeed['maxdeathnotices'] < 4 ) {
                            $this->KillfeedMaxDeathNotices->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedMaxDeathNotices->classes->add('valuewarning');
                        } else {
                            $this->KillfeedMaxDeathNotices->tooltipText = "Acceptable value";
                            $this->KillfeedMaxDeathNotices->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedMaxDeathNoticesAdd->visible = true;
                    }
                    if( $killfeed['lineheight'] != "" ) {
                        $this->KillfeedLineHeightAdd->visible = false;
                        $this->KillfeedLineHeight->text = $killfeed['lineheight'];
                        if ( $killfeed['lineheight'] > 25 or $killfeed['lineheight'] < 10 ) {
                            $this->KillfeedLineHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedLineHeight->classes->add('valuewarning');
                        } else {
                            $this->KillfeedLineHeight->tooltipText = "Acceptable value";
                            $this->KillfeedLineHeight->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedLineHeightAdd->visible = true;
                    }
                    if( $killfeed['iconscale'] != "" ) {
                        $this->KillfeedIconScaleAdd->visible = false;
                        $this->KillfeedIconScale->text = $killfeed['iconscale'];
                        if ( $killfeed['iconscale'] > 0.6 or $killfeed['iconscale'] < 0.1 ) {
                            $this->KillfeedIconScale->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedIconScale->classes->add('valuewarning');
                        } else {
                            $this->KillfeedIconScale->tooltipText = "Acceptable value";
                            $this->KillfeedIconScale->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedIconScaleAdd->visible = true;
                    }
                    if( $killfeed['xpos'] != "" ) {
                        $this->KillfeedXPositionAdd->visible = false;
                        $this->KillfeedXPosition->text = $killfeed['xpos'];
                        if ( $killfeed['xpos'] > 3000 or $killfeed['xpos'] < -3000 ) {
                            $this->KillfeedXPosition->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedXPosition->classes->add('valuewarning');
                        } else {
                            $this->KillfeedXPosition->tooltipText = "Acceptable value";
                            $this->KillfeedXPosition->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedXPositionAdd->visible = true;
                    }
                    if( $killfeed['ypos'] != "" ) {
                        $this->KillfeedYPositionAdd->visible = false;
                        $this->KillfeedYPosition->text = $killfeed['ypos'];
                        if ( $killfeed['ypos'] > 3000 or $killfeed['ypos'] < -3000 ) {
                            $this->KillfeedYPosition->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedYPosition->classes->add('valuewarning');
                        } else {
                            $this->KillfeedYPosition->tooltipText = "Acceptable value";
                            $this->KillfeedYPosition->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedYPositionAdd->visible = true;
                    }
                    if( $killfeed['wide'] != "" ) {
                        $this->KillfeedLayoutWidthAdd->visible = false;
                        $this->KillfeedLayoutWidth->text = $killfeed['wide'];
                        if ( $killfeed['wide'] > 3000 or $killfeed['wide'] < 0 ) {
                            $this->KillfeedLayoutWidth->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedLayoutWidth->classes->add('valuewarning');
                        } else {
                            $this->KillfeedLayoutWidth->tooltipText = "Acceptable value";
                            $this->KillfeedLayoutWidth->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedLayoutWidthAdd->visible = true;
                    }
                    if( $killfeed['tall'] != "" ) {
                        $this->KillfeedLayoutHeightAdd->visible = false;
                        $this->KillfeedLayoutHeight->text = $killfeed['tall'];
                        if ( $killfeed['tall'] > 3000 or $killfeed['tall'] < 0 ) {
                            $this->KillfeedLayoutHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedLayoutHeight->classes->add('valuewarning');
                        } else {
                            $this->KillfeedLayoutHeight->tooltipText = "Acceptable value";
                            $this->KillfeedLayoutHeight->classes->remove('valuewarning');
                        }
                    } else {
                        $this->KillfeedLayoutHeightAdd->visible = true;
                    }
                    if( $killfeed['rightjustify'] != "" ) {
                        $this->KillfeedRightAlignAdd->visible = false;
                        if ( $killfeed['rightjustify'] == "1" ) {
                            $this->KillfeedRightAlign->selected = true;
                        } else {
                            $this->KillfeedRightAlign->selected = false;
                        }
                    } else {
                        $this->KillfeedRightAlignAdd->visible = true;
                    }
                    if( $killfeed['visible'] != "" ) {
                        $this->KillfeedVisibilityAdd->visible = false;
                        if ( $killfeed['rightjustify'] == "1" ) {
                            $this->KillfeedVisibility->selected = true;
                        } else {
                            $this->KillfeedVisibility->selected = false;
                        }
                    } else {
                        $this->KillfeedVisibilityAdd->visible = true;
                    }
                    
                    if( $killfeed['teamblue'] != "" ) {
                        $this->KillfeedBluColorAdd->visible = false;
                        $color = explode(" ", $killfeed['teamblue']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of TeamBlue. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedBluColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['scheme']['colors'][$killfeed['teamblue']] ) {
                                $color = explode(" ", $clientscheme['scheme']['colors'][$killfeed['teamblue']]);
                                $this->KillfeedBluColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of TeamBlue. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedRedColorAdd->visible = true;
                    }
                    if( $killfeed['teamred'] != "" ) {
                        $this->KillfeedRedColorAdd->visible = false;
                        $color = explode(" ", $killfeed['teamred']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of TeamRed. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedRedColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['scheme']['colors'][$killfeed['teamred']] ) {
                                $color = explode(" ", $clientscheme['scheme']['colors'][$killfeed['teamred']]);
                                $this->KillfeedRedColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of TeamRed. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedRedColorAdd->visible = true;
                    }
                    if( $killfeed['iconcolor'] != "" ) {
                        $this->KillfeedIconColorAdd->visible = false;
                        $color = explode(" ", $killfeed['iconcolor']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of IconColor. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedIconColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['scheme']['colors'][$killfeed['iconcolor']] ) {
                                $color = explode(" ", $clientscheme['scheme']['colors'][$killfeed['iconcolor']]);
                                $this->KillfeedIconColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of IconColor. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedIconColorAdd->visible = true;
                    }                   
                    if( $killfeed['basebackgroundcolor'] != "" ) {
                        $this->KillfeedPanelBackgroundAdd->visible = false;
                        $color = explode(" ", $killfeed['basebackgroundcolor']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of BaseBackgroundColor. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedPanelBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['scheme']['colors'][$killfeed['basebackgroundcolor']] ) {
                                $color = explode(" ", $clientscheme['scheme']['colors'][$killfeed['basebackgroundcolor']]);
                                $this->KillfeedPanelBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of BaseBackgroundColor. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedPanelBackgroundAdd->visible = true;
                    }  
                    if( $killfeed['localplayercolor'] != "" ) {
                        $this->KillfeedPanelLocalPlayerAdd->visible = false;
                        $color = explode(" ", $killfeed['localplayercolor']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of LocalPlayerColor. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedPanelLocalPlayer->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['scheme']['colors'][$killfeed['localplayercolor']] ) {
                                $color = explode(" ", $clientscheme['scheme']['colors'][$killfeed['localplayercolor']]);
                                $this->KillfeedPanelLocalPlayer->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of LocalPlayerColor. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedPanelLocalPlayerAdd->visible = true;
                    } 
                    if( $killfeed['localbackgroundcolor'] != "" ) {
                        $this->KillfeedLocalBackgroundAdd->visible = false;
                        $color = explode(" ", $killfeed['localbackgroundcolor']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of LocalBackgroundColor. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedLocalBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['scheme']['colors'][$killfeed['localbackgroundcolor']] ) {
                                $color = explode(" ", $clientscheme['scheme']['colors'][$killfeed['localbackgroundcolor']]);
                                $this->KillfeedLocalBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of LocalBackground. Can't read value!");    
                            }
                        }
                    } else {
                        $this->KillfeedLocalBackgroundAdd->visible = true;
                    }
                    
                } else {
                    UXDialog::showAndWait("Error! Fieldname of killfeed is broken!");
                    // replace it and fix
                }
            } else {
                $this->KillfeedSettingsPanel->visible = false; $this->KillfeedPreviewPanel->visible = false;
                $this->KillfeedColorsPanel->visible = false; $this->KillfeedApplyButton->visible = false;
                $this->KillfeedPresetsButton->visible = false; $this->KillfeedNotFoundedAddButton->visible = true;
                $this->KillfeedNotFoundedDescription->visible = true; $this->KillfeedNotFoundedTitle->visible = true;
            }
        } else {
            pre("Error! Can't read hudlayouts. It's possible can be broken.");
        }
        $this->hidePreloader();
    }

    /**
     * @event KillfeedApplyButton.click-Left 
     */
    function doKillfeedApplyButtonClickLeft(UXMouseEvent $event = null)
    {    
        $this->showPreloader();
        global $hudlayout; global $huddir;
        if ( $hudlayout['resource/hudlayout.res']['huddeathnotice'] ) {
            if ( $this->KillfeedCornerRadiusAdd->visible != true or trim($this->KillfeedCornerRadius->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['cornerradius'] = $this->KillfeedCornerRadius->text;
            }
            if ( $this->KillfeedLineSpacingAdd->visible != true or trim($this->KillfeedLineSpacing->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['linespacing'] = $this->KillfeedLineSpacing->text;
            }
            if ( $this->KillfeedMaxDeathNoticesAdd->visible != true or trim($this->KillfeedMaxDeathNotices->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['maxdeathnotices'] = $this->KillfeedMaxDeathNotices->text;
            }
            if ( $this->KillfeedLineHeightAdd->visible != true or trim($this->KillfeedLineHeight->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['lineheight'] = $this->KillfeedLineHeight->text;
            }
            if ( $this->KillfeedIconScaleAdd->visible != true or trim($this->KillfeedIconScale->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['iconscale'] = $this->KillfeedIconScale->text;
            }
            if ( $this->KillfeedXPositionAdd->visible != true or trim($this->KillfeedXPosition->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['xpos'] = $this->KillfeedXPosition->text;
            }
            if ( $this->KillfeedYPositionAdd->visible != true or trim($this->KillfeedYPosition->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['ypos'] = $this->KillfeedYPosition->text;
            }
            if ( $this->KillfeedLayoutWidthAdd->visible != true or trim($this->KillfeedLayoutWidth->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['wide'] = $this->KillfeedLayoutWidth->text;
            }
            if ( $this->KillfeedLayoutHeightAdd->visible != true or trim($this->KillfeedLayoutHeight->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['tall'] = $this->KillfeedLayoutHeight->text;
            }
            if ( $this->KillfeedRightAlignAdd->visible != true ) {
                if ( $this->KillfeedRightAlign->selected == true ) {
                    $hudlayout['resource/hudlayout.res']['huddeathnotice']['rightjustify'] = "1";
                } else {
                    $hudlayout['resource/hudlayout.res']['huddeathnotice']['rightjustify'] = "0";    
                }
            }
            if ( $this->KillfeedVisibilityAdd->visible != true ) {
                if ( $this->KillfeedVisibility->selected == true ) {
                    $hudlayout['resource/hudlayout.res']['huddeathnotice']['visible'] = "1";
                } else {
                    $hudlayout['resource/hudlayout.res']['huddeathnotice']['visible'] = "0";    
                }
            }
            if ( $this->KillfeedBluColorAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedBluColor->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedBluColor->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedBluColor->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedBluColor->value)->opacity);
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['teamblue'] = "$r $g $b $o";
            }
            if ( $this->KillfeedRedColorAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedRedColor->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedRedColor->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedRedColor->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedRedColor->value)->opacity);
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['teamred'] = "$r $g $b $o";
            }
            if ( $this->KillfeedIconColorAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedIconColor->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedIconColor->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedIconColor->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedIconColor->value)->opacity);
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['iconcolor'] = "$r $g $b $o";
            }
            if ( $this->KillfeedPanelBackgroundAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedPanelBackground->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedPanelBackground->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedPanelBackground->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedPanelBackground->value)->opacity);
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['basebackgroundcolor'] = "$r $g $b $o";
            }
            if ( $this->KillfeedPanelLocalPlayerAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedPanelLocalPlayer->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedPanelLocalPlayer->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedPanelLocalPlayer->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedPanelLocalPlayer->value)->opacity);
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['localplayercolor'] = "$r $g $b $o";
            }
            if ( $this->KillfeedLocalBackgroundAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedLocalBackground->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedLocalBackground->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedLocalBackground->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedLocalBackground->value)->opacity);
                $hudlayout['resource/hudlayout.res']['huddeathnotice']['localbackgroundcolor'] = "$r $g $b $o";
            }
            VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout);  
            $this->doKillfeedUpdateButtonClickLeft();
        } else {
            pre("Error! Can't read hudlayouts. It's can be broken.");  
        }
        $this->hidePreloader();
    }

    /**
     * @event KillfeedCornerRadiusAdd.click-Left 
     */
    function doKillfeedCornerRadiusAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['cornerradius'] = "3";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout);
    }

    /**
     * @event KillfeedLineSpacingAdd.click-Left 
     */
    function doKillfeedLineSpacingAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['linespacing'] = "4";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout);
    }

    /**
     * @event KillfeedMaxDeathNoticesAdd.click-Left 
     */
    function doKillfeedMaxDeathNoticesAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['maxdeathnotices'] = "4";  
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedLineHeightAdd.click-Left 
     */
    function doKillfeedLineHeightAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['lineheight'] = "16";  
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedIconScaleAdd.click-Left 
     */
    function doKillfeedIconScaleAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['iconscale'] = "0.35";  
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedXPositionAdd.click-Left 
     */
    function doKillfeedXPositionAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['xpos'] = "r640";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedYPositionAdd.click-Left 
     */
    function doKillfeedYPositionAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['ypos'] = "18";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedLayoutWidthAdd.click-Left 
     */
    function doKillfeedLayoutWidthAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['wide'] = "580";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedLayoutHeightAdd.click-Left 
     */
    function doKillfeedLayoutHeightAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['tall'] = "468";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedRightAlignAdd.click-Left 
     */
    function doKillfeedRightAlignAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['rightjustify'] = "1";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedVisibilityAdd.mouseDown-Left 
     */
    function doKillfeedVisibilityAddMouseDownLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['visible'] = "1";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedBluColorAdd.mouseDown-Left 
     */
    function doKillfeedBluColorAddMouseDownLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['teamblue'] = "0 135 255 255";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }


    /**
     * @event KillfeedIconColorAdd.click-Left 
     */
    function doKillfeedIconColorAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['iconcolor'] = "119 119 119 90";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedPanelBackgroundAdd.click-Left 
     */
    function doKillfeedPanelBackgroundAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['basebackgroundcolor'] = "46 43 42 220";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedPanelLocalPlayerAdd.click-Left 
     */
    function doKillfeedPanelLocalPlayerAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['localplayercolor'] = "119 119 119 90";         
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedLocalBackgroundAdd.click-Left 
     */
    function doKillfeedLocalBackgroundAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddeathnotice']['localbackgroundcolor'] = "245 229 196 200";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedNotFoundedAddButton.click-Left 
     */
    function doKillfeedNotFoundedAddButtonClickLeft(UXMouseEvent $event = null)
    {    
        $hudlayout = VDF::decodeFile("$huddir\\scripts\\hudlayout.res");
        $killfeed = [
            'fieldname' => "huddeathnotice",
            'xpos' => "r640",
            'ypos' => "18",
            'wide' => "580",
            'tall' => "428",                                     
            'visible' => "1",
            'enabled' => "1",
            'cornerradius' => "3",
            'linespacing' => "4",
            'maxdeathnotices' => "4", 
            'lineheight' => "16",
            'iconscale' => "0.35",
            'teamblue' => "0 135 255 255",
            'TeamRed' => "153 0 0 255",
            'IconColor' => "119 119 119 90",
            'basebackgroundcolor' => "46 43 42 220",
            'localplayercolor' => "119 119 119 90",  
            'localbackgroundcolor' => "245 229 196 200",
        ];
        $hudlayout['resource/hudlayout.res']['huddeathnotice'] = $killfeed;
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedPresetsButton.click-Left 
     */
    function doKillfeedPresetsButtonClickLeft(UXMouseEvent $event = null)
    {    
        $this->KillfeedSettingsPanel->enabled = false;   $this->KillfeedPresetsPanel->enabled = true;
        $this->KillfeedColorsPanel->enabled = false;     $this->KillfeedPresetsInfoPanel->enabled = true;
        $this->KillfeedPresetsButton->enabled = false;   $this->KillfeedPresetsBackButton->enabled = true;
        $this->KillfeedUpdateButton->enabled = false;    $this->KillfeedPresetsUpdateListButton->enabled = true;
        $this->KillfeedApplyButton->enabled = false;     $this->KillfeedPresetsUsePresetButton->enabled = true;
        
        $this->KillfeedPresetsPanel->x = -256;           $this->KillfeedPresetsPanel->y = 0;
        $this->KillfeedPresetsInfoPanel->x = 872;        $this->KillfeedPresetsInfoPanel->y = 0;
        $this->KillfeedPresetsBackButton->x = 872;       $this->KillfeedPresetsBackButton->y = 200;
        $this->KillfeedPresetsUsePresetButton->x = 872;  $this->KillfeedPresetsUsePresetButton->y = 240;
        $this->KillfeedPresetsUpdateListButton->x = 872;     $this->KillfeedPresetsUpdateListButton->y = 280;
        
        $this->KillfeedPresetsPanel->opacity = 0;        $this->KillfeedPresetsInfoPanel->opacity = 0;
        $this->KillfeedPresetsBackButton->opacity = 0;   $this->KillfeedPresetsUpdateListButton->opacity = 0;
        $this->KillfeedPresetsUsePresetButton->opacity = 0;
        
        Animation::moveTo($this->KillfeedSettingsPanel, 300, -256.0, 0.0);
        Animation::fadeOut($this->KillfeedSettingsPanel, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedPresetsPanel, 150);        
            Animation::moveTo($this->KillfeedPresetsPanel, 300, 0.0, 0.0);  
        });
        
        Animation::moveTo($this->KillfeedColorsPanel, 300, 872.0, 0.0);
        Animation::fadeOut($this->KillfeedColorsPanel, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedPresetsInfoPanel, 150);
            Animation::moveTo($this->KillfeedPresetsInfoPanel, 300, 624.0, 0.0);  
        });   
        
        Animation::moveTo($this->KillfeedPresetsButton, 300, 872.0, 200.0);
        Animation::fadeOut($this->KillfeedPresetsButton, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedPresetsBackButton, 150);
            Animation::moveTo($this->KillfeedPresetsBackButton, 300, 624.0, 200.0);  
        }); 
        
        Animation::moveTo($this->KillfeedApplyButton, 300, 872.0, 240.0);
        Animation::fadeOut($this->KillfeedApplyButton, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedPresetsUsePresetButton, 150);
            Animation::moveTo($this->KillfeedPresetsUsePresetButton, 300, 624.0, 240.0);  
        });
        
        Animation::moveTo($this->KillfeedUpdateButton, 300, 872.0, 280.0);
        Animation::fadeOut($this->KillfeedUpdateButton, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedPresetsUpdateListButton, 150);
            Animation::moveTo($this->KillfeedPresetsUpdateListButton, 300, 624.0, 280.0);
            
        }); 
        
        $this->doKillfeedPresetsUpdateListButtonClickLeft();
                                           
    }

    /**
     * @event KillfeedPresetsBackButton.click-Left 
     */
    function doKillfeedPresetsBackButtonClickLeft(UXMouseEvent $event = null)
    {    
        $this->KillfeedSettingsPanel->enabled = true;   $this->KillfeedPresetsPanel->enabled = false;
        $this->KillfeedColorsPanel->enabled = true;     $this->KillfeedPresetsInfoPanel->enabled = false;
        $this->KillfeedPresetsButton->enabled = true;   $this->KillfeedPresetsBackButton->enabled = false;
        $this->KillfeedUpdateButton->enabled = true;    $this->KillfeedPresetsUpdateListButton->enabled = false;
        $this->KillfeedApplyButton->enabled = true;     $this->KillfeedPresetsUsePresetButton->enabled = false;
        
        $this->KillfeedSettingsPanel->opacity = 0;     $this->KillfeedUpdateButton->opacity = 0;
        $this->KillfeedColorsPanel->opacity = 0;       $this->KillfeedApplyButton->opacity = 0;
        $this->KillfeedPresetsButton->opacity = 0;
        
        Animation::moveTo($this->KillfeedPresetsPanel, 300, -256.0, 0.0);
        Animation::fadeOut($this->KillfeedPresetsPanel, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedSettingsPanel, 150);        
            Animation::moveTo($this->KillfeedSettingsPanel, 300, 0.0, 0.0);  
        });
        
        Animation::moveTo($this->KillfeedPresetsInfoPanel, 300, 872.0, 0.0);
        Animation::fadeOut($this->KillfeedPresetsInfoPanel, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedColorsPanel, 150);
            Animation::moveTo($this->KillfeedColorsPanel, 300, 624.0, 0.0);  
        });   
        
        Animation::moveTo($this->KillfeedPresetsBackButton, 300, 872.0, 200.0);
        Animation::fadeOut($this->KillfeedPresetsBackButton, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedPresetsButton, 150);
            Animation::moveTo($this->KillfeedPresetsButton, 300, 624.0, 200.0);  
        }); 
        
        Animation::moveTo($this->KillfeedPresetsUsePresetButton, 300, 872.0, 240.0);
        Animation::fadeOut($this->KillfeedPresetsUsePresetButton, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedApplyButton, 150);
            Animation::moveTo($this->KillfeedApplyButton, 300, 624.0, 240.0);  
        });
        
        Animation::moveTo($this->KillfeedPresetsUpdateListButton, 300, 872.0, 280.0);
        Animation::fadeOut($this->KillfeedPresetsUpdateListButton, 150, function () use ($event) {
            Animation::fadeIn($this->KillfeedUpdateButton, 150);
            Animation::moveTo($this->KillfeedUpdateButton, 300, 624.0, 280.0);
            
        }); 
    }

    /**
     * @event KillfeedPresetsUpdateListButton.click-Left 
     */
    function doKillfeedPresetsUpdateListButtonClickLeft(UXMouseEvent $event = null)
    {    
        global $presets, $selectedprofile;
        $this->KillfeedPresetsList->items->clear();
        $presets = Json::decode(file_get_contents('res://.data/presets/killfeedpresets.json'), true);
        
        $this->profilestorage->path = "profiles\\$selectedprofile\\profilestorage.dat";
        $profilekillfeed = $this->profilestorage->toArray();
        if ( $profilekillfeed['DefaultKillfeed'] ) {
            $presetnumber = (count($presets) + 1);
            $presets[$presetnumber]['Info']['Source'] = 'This HUD';
            $presets[$presetnumber]['Info']['Author'] = 'original author';
            $presets[$presetnumber]['Preset'] = $profilekillfeed['DefaultKillfeed'];
        }
        foreach( $presets as $preset ) {
            $this->KillfeedPresetsList->items->add($preset['Info']['Source']);
        }
    }

    /**
     * @event DamageIndicatorPresetsList.action 
     */
    function doDamageIndicatorPresetsListAction(UXEvent $event = null)
    {    
        global $presets;
        $this->KillfeedPresetsInfoDescription->text = "Killfeed from ".$presets[$this->KillfeedPresetsList->selectedIndex]['Info']['Source']." by ".$presets[$this->KillfeedPresetsList->selectedIndex]['Info']['Author']; 
    }
    
    /**
     * @event KillfeedPresetsUsePresetButton.click-Left 
     */
    function doKillfeedPresetsUsePresetButtonClickLeft(UXMouseEvent $event = null)
    {    
        global $presets;
        
        if ( count($presets) != 0 ) {
            if ( $this->KillfeedPresetsList->selectedIndex != -1 ) {
                $selectedindex = $this->KillfeedPresetsList->selectedIndex;

                $this->KillfeedCornerRadius->text = $presets[$selectedindex]['Preset']['CornerRadius'];
                if ( $presets[$selectedindex]['Preset']['CornerRadius'] > 10 or $presets[$selectedindex]['Preset']['CornerRadius'] < 0 ) {
                    $this->KillfeedCornerRadius->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedCornerRadius->classes->add('valuewarning');
                } else {
                    $this->KillfeedCornerRadius->tooltipText = "Acceptable value";
                    $this->KillfeedCornerRadius->classes->remove('value-warning');
                }

                $this->KillfeedLineSpacing->text = $presets[$selectedindex]['Preset']['LineSpacing'];
                if ( $presets[$selectedindex]['Preset']['LineSpacing'] > 15 or $presets[$selectedindex]['Preset']['LineSpacing'] < 0 ) {
                    $this->KillfeedLineSpacing->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedLineSpacing->classes->add('valuewarning');
                } else {
                    $this->KillfeedLineSpacing->tooltipText = "Acceptable value";
                    $this->KillfeedLineSpacing->classes->remove('valuewarning');
                }

                $this->KillfeedMaxDeathNotices->text = $presets[$selectedindex]['Preset']['MaxDeathNotices'];
                if ( $presets[$selectedindex]['Preset']['MaxDeathNotices'] > 20 or $presets[$selectedindex]['Preset']['MaxDeathNotices'] < 4 ) {
                    $this->KillfeedMaxDeathNotices->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedMaxDeathNotices->classes->add('valuewarning');
                } else {
                    $this->KillfeedMaxDeathNotices->tooltipText = "Acceptable value";
                    $this->KillfeedMaxDeathNotices->classes->remove('valuewarning');
                }
                
                $this->KillfeedLineHeight->text = $presets[$selectedindex]['Preset']['LineHeight'];
                if ( $presets[$selectedindex]['Preset']['LineHeight'] > 25 or $presets[$selectedindex]['Preset']['LineHeight'] < 10 ) {
                    $this->KillfeedLineHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedLineHeight->classes->add('valuewarning');
                } else {
                    $this->KillfeedLineHeight->tooltipText = "Acceptable value";
                    $this->KillfeedLineHeight->classes->remove('valuewarning');
                }

                $this->KillfeedIconScale->text = $presets[$selectedindex]['Preset']['IconScale'];
                if ( $presets[$selectedindex]['Preset']['IconScale'] > 0.6 or $presets[$selectedindex]['Preset']['IconScale'] < 0.1 ) {
                    $this->KillfeedIconScale->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedIconScale->classes->add('valuewarning');
                } else {
                    $this->KillfeedIconScale->tooltipText = "Acceptable value";
                    $this->KillfeedIconScale->classes->remove('valuewarning');
                }

                $this->KillfeedXPosition->text = $presets[$selectedindex]['Preset']['xpos'];
                if ( $presets[$selectedindex]['Preset']['xpos'] > 3000 or $presets[$selectedindex]['Preset']['xpos'] < -3000 ) {
                    $this->KillfeedXPosition->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedXPosition->classes->add('valuewarning');
                } else {
                    $this->KillfeedXPosition->tooltipText = "Acceptable value";
                    $this->KillfeedXPosition->classes->remove('valuewarning');
                }

                $this->KillfeedYPosition->text = $presets[$selectedindex]['Preset']['ypos'];
                if ( $presets[$selectedindex]['Preset']['ypos'] > 3000 or $presets[$selectedindex]['Preset']['ypos'] < -3000 ) {
                    $this->KillfeedYPosition->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedYPosition->classes->add('valuewarning');
                } else {
                    $this->KillfeedYPosition->tooltipText = "Acceptable value";
                    $this->KillfeedYPosition->classes->remove('valuewarning');
                }

                $this->KillfeedLayoutWidth->text = $presets[$selectedindex]['Preset']['wide'];
                if ( $presets[$selectedindex]['Preset']['wide'] > 3000 or $presets[$selectedindex]['Preset']['wide'] < 0 ) {
                    $this->KillfeedLayoutWidth->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedLayoutWidth->classes->add('valuewarning');
                } else {
                    $this->KillfeedLayoutWidth->tooltipText = "Acceptable value";
                    $this->KillfeedLayoutWidth->classes->remove('valuewarning');
                }

                $this->KillfeedLayoutHeight->text = $presets[$selectedindex]['Preset']['tall'];
                if ( $presets[$selectedindex]['Preset']['tall'] > 3000 or $presets[$selectedindex]['Preset']['tall'] < 0 ) {
                    $this->KillfeedLayoutHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedLayoutHeight->classes->add('valuewarning');
                } else {
                    $this->KillfeedLayoutHeight->tooltipText = "Acceptable value";
                    $this->KillfeedLayoutHeight->classes->remove('valuewarning');
                }

                if ( $presets[$selectedindex]['Preset']['rightjustify'] == "1" ) {
                    $this->KillfeedRightAlign->selected = true;
                } else {
                    $this->KillfeedRightAlign->selected = false;
                }

                if ( $presets[$selectedindex]['Preset']['rightjustify'] == "1" ) {
                    $this->KillfeedVisibility->selected = true;
                } else {
                    $this->KillfeedVisibility->selected = false;
                }

                $color = explode(" ", $presets[$selectedindex]['Preset']['TeamBlue']);
                if ( count($color) == 4 ) {
                    if ( $color[0] > 255 or $color[0] < 0 ) {
                        UXDialog::showAndWait("Error! Invalid color of TeamBlue. Can't read value. But you can replace it.");    
                    } else {
                        $this->KillfeedBluColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                    }
                }
                $color = explode(" ", $presets[$selectedindex]['Preset']['TeamRed']);
                if ( count($color) == 4 ) {
                    if ( $color[0] > 255 or $color[0] < 0 ) {
                        UXDialog::showAndWait("Error! Invalid color of TeamRed. Can't read value. But you can replace it.");    
                    } else {
                        $this->KillfeedRedColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                    }
                }
                $color = explode(" ", $presets[$selectedindex]['Preset']['IconColor']);
                if ( count($color) == 4 ) {
                    if ( $color[0] > 255 or $color[0] < 0 ) {
                        UXDialog::showAndWait("Error! Invalid color of IconColor. Can't read value. But you can replace it.");    
                    } else {
                        $this->KillfeedIconColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());                            }
                }
                $color = explode(" ", $presets[$selectedindex]['Preset']['BaseBackgroundColor']);
                if ( count($color) == 4 ) {
                    if ( $color[0] > 255 or $color[0] < 0 ) {
                        UXDialog::showAndWait("Error! Invalid color of BaseBackgroundColor. Can't read value. But you can replace it.");    
                    } else {
                        $this->KillfeedPanelBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                    }
                }

                $color = explode(" ", $presets[$selectedindex]['Preset']['LocalPlayerColor']);
                if ( count($color) == 4 ) {
                    if ( $color[0] > 255 or $color[0] < 0 ) {
                        UXDialog::showAndWait("Error! Invalid color of LocalPlayerColor. Can't read value. But you can replace it.");    
                    } else {
                        $this->KillfeedPanelLocalPlayer->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                    }
                }

                $color = explode(" ", $presets[$selectedindex]['Preset']['LocalBackgroundColor']);
                if ( count($color) == 4 ) {
                    if ( $color[0] > 255 or $color[0] < 0 ) {
                        UXDialog::showAndWait("Error! Invalid color of LocalBackgroundColor. Can't read value. But you can replace it.");    
                    } else {
                        $this->KillfeedLocalBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                    }
                }
            } else {
                UXDialog::showAndWait("Select preset which you want to apply!");
            }
        } else {
            UXDialog::showAndWait("Presets list is empty! Check your internet connection and press 'Update list' button.");
        }
    }
    
    /** 
     * O V E R R I D E S
     */

    /**
     * @event OverridesUpdateButton.click-Left 
     */
    function doOverridesUpdateButtonClickLeft(UXMouseEvent $event = null)
    {    
        global $huddir, $ahudmarray;
        
        $this->OverridesAboutPanel->visible = false; $this->OverridesListPanel->visible = false;
        $this->OverridesPreviewPanel->visible = false; $this->OverridesToggleButton->visible = false;
        $this->OverridesNotFoundedTitle->visible = true; $this->OverridesNotFoundedDescription->visible = true;
        
        if ( $ahudmarray["Custom"]["OverridesFolder"] ) {
            $overridesfolder = str::trim($ahudmarray["Custom"]["OverridesFolder"]);
            if ( $overridesfolder == "Overrides" and is_dir("$huddir\\Overrides") ) {
                $overridessource = "Overrides";
            } elseif ( $overridesfolder != "" and is_dir("$huddir\\".$overridesfolder) ) {
                $overridessource = $overridesfolder;     
            } else {
                $overridessource = false;
            }
        } else {
            $overridessource = false;
        }
        
        if ( $overridessource != false ) {
            $this->OverridesAboutPanel->visible = true; $this->OverridesListPanel->visible = true;
            $this->OverridesPreviewPanel->visible = true; $this->OverridesToggleButton->visible = true;
            $this->OverridesNotFoundedTitle->visible = false; $this->OverridesNotFoundedDescription->visible = false;
        
            fs::scan("$huddir\\$overridessource", function ($filename, $depth) {
                if ( file::of($filename)->isDirectory() ) {
                    $overrideswhitelist = ['materials', 'resource', 'cfg', 'scripts']; 
                    if ( $ahudmarray["Custom"]["OverridesWhiteList"] ) {
                        $overrideswhitelist[] = str::trim(str::split($ahudmarray["Custom"]["OverridesWhiteList"], ','));
                    }
                    $overrideswhitelistgood = 0;
                    foreach ($overrideswhitelist as $overrideswhite) {
                        if ( is_dir(file::of($filename)->getAbsolutePath()."\\$overrideswhite") ) {
                            $this->overrides[] .= file::of($filename)->getName();
                        }
                    }
                }
            }, 1);
            
            $overrides = $this->overrides;
            /*if ( $overrideswhitelistgood > 0 ) {
            if ( $ahudmarray['Overrides'][file::of($filename)->getName()] ) {
            $overrideinfo = explode(' || ', $ahudmarray['Overrides'][file::of($filename)->getName()]);
            if ( $overrideinfo[0] ) {
            $this->OverridesList->items->add($overrideinfo);
            } else {
            $this->OverridesList->items->add(file::of($filename)->getName());
            }
            } else {
            $this->OverridesList->items->add(file::of($filename)->getName());
            }
            }*/
            pre($overrides);            
        }
    }

    /** 
     * D A M A G E I N D I C A T O R
     */

    /**
     * @event DamageIndicatorUpdateButton.click-Left 
     */
    function doDamageIndicatorUpdateButtonClickLeft(UXMouseEvent $event = null)
    {    
        $this->showPreloader();
        global $huddir; 
        
        if ( is_file("$huddir\\scripts\\hudlayout.res") and file_get_contents("$huddir\\scripts\\hudlayout.res") != NULL ) {
            $hudlayout = VDF::decodeFile("$huddir\\scripts\\hudlayout.res");
            
            if ( $hudlayout['resource/hudlayout.res']['huddamageindicator'] and ! empty($hudlayout['resource/hudlayout.res']['huddamageindicator']) ) {
                $this->DamageIndicatorSettingsPanel->visible = true; $this->DamageIndicatorPreviewPanel->visible = true;
                $this->DamageIndicatorApplyButton->visible = true;
                $this->DamageIndicatorPresetsButton->visible = true; $this->DamageIndicatorNotFoundedAddButton->visible = false;
                $this->DamageIndicatorNotFoundedDescription->visible = false; $this->DamageIndicatorNotFoundedTitle->visible = false;
                $damageindicator = $hudlayout['resource/hudlayout.res']['huddamageindicator'];
                
                if( strtolower($damageindicator['fieldname']) == 'huddamageindicator' ) {
                    if( $damageindicator['minimumwidth'] != "" ) {
                        $this->DamageIndicatorMinWidthAdd->visible = false;
                        $this->DamageIndicatorMinWidth->text = $damageindicator['minimumwidth'];
                        if ( $damageindicator['minimumwidth'] > 35 or $damageindicator['minimumwidth'] < 0 ) {
                            $this->DamageIndicatorMinWidth->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->Killfeedcornerradius->classes->add('valuewarning');
                        } else {
                            $this->DamageIndicatorMinWidth->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMinWidth->classes->remove('valuewarning');
                        }
                    } else {
                        $this->DamageIndicatorMinWidthAdd->visible = true;
                    }
                    if( $damageindicator['maximumwidth'] != "" ) {
                        $this->DamageIndicatorMaxWidthAdd->visible = false;
                        $this->DamageIndicatorMaxWidth->text = $damageindicator['maximumwidth'];
                        if ( $damageindicator['maximumwidth'] > 35 or $damageindicator['maximumwidth'] < 0 ) {
                            $this->DamageIndicatorMaxWidth->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMaxWidth->classes->add('valuewarning');
                        } else {
                            $this->DamageIndicatorMaxWidth->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMaxWidth->classes->remove('valuewarning');
                        }
                    } else {
                        $this->DamageIndicatorMaxWidthAdd->visible = true;
                    }
                    if( $damageindicator['startradius'] != "" ) {
                        $this->DamageIndicatorStartRadiusAdd->visible = false;
                        $this->DamageIndicatorStartRadius->text = $damageindicator['startradius'];
                        if ( $damageindicator['startradius'] > 200 or $damageindicator['startradius'] < 50 ) {
                            $this->DamageIndicatorStartRadius->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorStartRadius->classes->add('valuewarning');
                        } else {
                            $this->DamageIndicatorStartRadius->tooltipText = "Acceptable value";
                            $this->DamageIndicatorStartRadius->classes->remove('valuewarning');
                        }
                    } else {
                        $this->DamageIndicatorStartRadiusAdd->visible = true;
                    }
                    if( $damageindicator['endradius'] != "" ) {
                        $this->DamageIndicatorEndRadiusAdd->visible = false;
                        $this->DamageIndicatorEndRadius->text = $damageindicator['endradius'];
                        if ( $damageindicator['endradius'] > 35 or $damageindicator['endradius'] < 0 ) {
                            $this->DamageIndicatorEndRadius->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorEndRadius->classes->add('valuewarning');
                        } else {
                            $this->DamageIndicatorEndRadius->tooltipText = "Acceptable value";
                            $this->DamageIndicatorEndRadius->classes->remove('valuewarning');
                        }
                    } else {
                        $this->DamageIndicatorEndRadiusAdd->visible = true;
                    }
                    if( $damageindicator['minimumheight'] != "" ) {
                        $this->DamageIndicatorMinHeightAdd->visible = false;
                        $this->DamageIndicatorMinHeight->text = $damageindicator['minimumheight'];
                        if ( $damageindicator['minimumheight'] > 10 or $damageindicator['minimumheight'] < 60 ) {
                            $this->DamageIndicatorMinHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMinHeight->classes->add('valuewarning');
                        } else {
                            $this->DamageIndicatorMinHeight->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMinHeight->classes->remove('valuewarning');
                        }
                    } else {
                        $this->DamageIndicatorMinHeightAdd->visible = true;
                    }
                    if( $damageindicator['maximumheight'] != "" ) {
                        $this->DamageIndicatorMaxHeightAdd->visible = false;
                        $this->DamageIndicatorMaxHeight->text = $damageindicator['maximumheight'];
                        if ( $damageindicator['maximumheight'] > 35 or $damageindicator['maximumheight'] < 120 ) {
                            $this->DamageIndicatorMaxHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMaxHeight->classes->add('valuewarning');
                        } else {
                            $this->DamageIndicatorMaxHeight->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMaxHeight->classes->remove('valuewarning');
                        }
                    } else {
                        $this->DamageIndicatorMaxHeightAdd->visible = true;
                    }
                    if( $damageindicator['minimumtime'] != "" ) {
                        $this->DamageIndicatorMinTimeAdd->visible = false;
                        $this->DamageIndicatorMinTime->text = $damageindicator['minimumtime'];
                        if ( $damageindicator['minimumtime'] > 35 or $damageindicator['minimumtime'] < 120 ) {
                            $this->DamageIndicatorMinTime->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMinTime->classes->add('valuewarning');
                        } else {
                            $this->DamageIndicatorMinTime->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMinTime->classes->remove('valuewarning');
                        }
                    } else {
                        $this->DamageIndicatorMinTimeAdd->visible = true;
                    }
                    if( $damageindicator['maximumtime'] != "" ) {
                        $this->DamageIndicatorMaxTimeAdd->visible = false;
                        $this->DamageIndicatorMaxTime->text = $damageindicator['maximumtime'];
                        if ( $damageindicator['maximumtime'] > 35 or $damageindicator['maximumtime'] < 120 ) {
                            $this->DamageIndicatorMaxTime->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMaxTime->classes->add('valuewarning');
                        } else {
                            $this->DamageIndicatorMaxTime->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMaxTime->classes->remove('valuewarning');
                        }
                    } else {
                        $this->DamageIndicatorMaxTimeAdd->visible = true;
                    }
                    if( $damageindicator['visible'] != "" ) {
                        $this->DamageIndicatorVisibilityAdd->visible = false;
                        if ( $damageindicator['visible'] == "0") {
                            $this->DamageIndicatorVisibility->selected = false;
                        } else {
                            $this->DamageIndicatorVisibility->selected = true;
                        }
                    } else {
                        $this->DamageIndicatorVisibilityAdd->visible = true;
                    }
                    
                } else {
                    UXDialog::showAndWait("Error! Fieldname was broken. But we're already fixed this.");
                }
            } else {
                $this->KillfeedSettingsPanel->visible = false; $this->KillfeedPreviewPanel->visible = false;
                $this->KillfeedColorsPanel->visible = false; $this->KillfeedApplyButton->visible = false;
                $this->KillfeedPresetsButton->visible = false; $this->KillfeedNotFoundedAddButton->visible = true;
                $this->KillfeedNotFoundedDescription->visible = true; $this->KillfeedNotFoundedTitle->visible = true;
            }
        } else {
            pre("Error! Can't read hudlayouts. It's can be broken.");
        }
        $this->hidePreloader();
                                        
    }

    /**
     * @event DamageIndicatorNotFoundedAddButton.click-Left 
     */
    function doDamageIndicatorNotFoundedAddButtonClickLeft(UXMouseEvent $event = null)
    {    
        $hudlayout = VDF::decodeFile("$huddir\\scripts\\hudlayout.res");
        $killfeed = [
            'fieldname' => "huddamageindicator",                                   
            'visible' => "1",
            'enabled' => "1",
            'minimumwidth' => "10",
            'maximumwidth' => "10",
            'startradius' => "80",
            'endradius' => "80",
            'minimumheight' => "30",
            'maximumheight' => "60",
            'minimumtime' => "1",
            'maximumtime' => "2",
        ];
        $hudlayout['resource/hudlayout.res']['huddamageindicator'] = $damageindicator;
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();  
    }

    /**
     * @event DamageIndicatorMinWidthAdd.click-Left 
     */
    function doDamageIndicatorMinWidthAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddamageindicator']['minimumwidth'] = "10";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorMaxWidthAdd.click-Left 
     */
    function doDamageIndicatorMaxWidthAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddamageindicator']['maximumwidth'] = "10";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorStartRadiusAdd.click-Left 
     */
    function doDamageIndicatorStartRadiusAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddamageindicator']['startradius'] = "80";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorEndRadiusAdd.click-Left 
     */
    function doDamageIndicatorEndRadiusAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddamageindicator']['endradius'] = "80";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorMinHeightAdd.click-Left 
     */
    function doDamageIndicatorMinHeightAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddamageindicator']['minimumHhight'] = "60";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }


    /**
     * @event DamageIndicatorMaxHeightAdd.click-Left 
     */
    function doDamageIndicatorMaxHeightAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddamageindicator']['maximumheight'] = "60";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorMinTimeAdd.click-Left 
     */
    function doDamageIndicatorMinTimeAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddamageindicator']['minimumtime'] = "1";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorMaxTimeAdd.click-Left 
     */
    function doDamageIndicatorMaxTimeAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['resource/hudlayout.res']['huddamageindicator']['maximumtime'] = "2";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorApplyButton.click-Left 
     */
    function doDamageIndicatorApplyButtonClickLeft(UXMouseEvent $event = null)
    {    
       $this->showPreloader();
        global $hudlayout; global $huddir;
        if ( $hudlayout['resource/hudlayout.res']['huddamageindicator'] ) {
            if ( $this->DamageIndicatorMinWidthAdd->visible != true or trim($this->DamageIndicatorMinWidth->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddamageindicator']['minimumwidth'] = $this->DamageIndicatorMinWidth->text;
            }
            if ( $this->DamageIndicatorMaxWidthAdd->visible != true or trim($this->DamageIndicatorMaxWidth->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddamageindicator']['maximumwidth'] = $this->DamageIndicatorMaxWidth->text;
            }
            if ( $this->DamageIndicatorstartradiusAdd->visible != true or trim($this->DamageIndicatorstartradius->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddamageindicator']['startradius'] = $this->DamageIndicatorstartradius->text;
            }
            if ( $this->DamageIndicatorendradiusAdd->visible != true or trim($this->DamageIndicatorendradius->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddamageindicator']['endradius'] = $this->DamageIndicatorendradius->text;
            }
            if ( $this->DamageIndicatorMinHeightAdd->visible != true or trim($this->DamageIndicatorMinHeight->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddamageindicator']['minimumheight'] = $this->DamageIndicatorMinHeight->text;
            }
            if ( $this->DamageIndicatorMaxHeightAdd->visible != true or trim($this->DamageIndicatorMaxHeight->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddamageindicator']['maximumheight'] = $this->DamageIndicatorMaxHeight->text;
            }
            if ( $this->DamageIndicatorMaxTimeAdd->visible != true or trim($this->DamageIndicatorMaxTime->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddamageindicator']['maximumtime'] = $this->DamageIndicatorMaxTime->text;
            }
            if ( $this->DamageIndicatorMinTimeAdd->visible != true or trim($this->DamageIndicatorMinTime->text) != "" ) {
                $hudlayout['resource/hudlayout.res']['huddamageindicator']['minimumtime'] = $this->DamageIndicatorMinTime->text;
            }

            if ( $this->DamageIndicatorVisibilityAdd->visible != true ) {
                if ( $this->DamageIndicatorVisibility->selected == true ) {
                    $hudlayout['resource/hudlayout.res']['huddamageindicator']['visible'] = "1";
                } else {
                    $hudlayout['resource/hudlayout.res']['huddamageindicator']['visible'] = "0";    
                }
            }
            VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout);  
        } else {
            pre("Error! Can't read hudlayouts. It's can be broken.");  
        }
        $this->hidePreloader();
    }
    
    

}