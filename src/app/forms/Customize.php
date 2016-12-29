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
        global $huddir;
        global $hudlayout;
        if ( is_file("$huddir\\scripts\\hudlayout.res") and file_get_contents("$huddir\\scripts\\hudlayout.res") != NULL ) {
            $hudlayout = VDF::decodeFile("$huddir\\scripts\\hudlayout.res");
            
            if ( $hudlayout['Resource/HudLayout.res']['HudDeathNotice'] and ! empty($hudlayout['Resource/HudLayout.res']['HudDeathNotice']) ) {
                $this->KillfeedSettingsPanel->visible = true; $this->KillfeedPreviewPanel->visible = true;
                $this->KillfeedColorsPanel->visible = true; $this->KillfeedApplyButton->visible = true;
                $this->KillfeedPresetsButton->visible = true; $this->KillfeedNotFoundedAddButton->visible = false;
                $this->KillfeedNotFoundedDescription->visible = false; $this->KillfeedNotFoundedTitle->visible = false;
                $killfeed = $hudlayout['Resource/HudLayout.res']['HudDeathNotice'];
                
                if( $killfeed['fieldName'] == 'HudDeathNotice' ) {
                    if( $killfeed['CornerRadius'] != "" ) {
                        $this->KillfeedCornerRadiusAdd->visible = false;
                        $this->KillfeedCornerRadius->text = $killfeed['CornerRadius'];
                        if ( $killfeed['CornerRadius'] > 10 or $killfeed['CornerRadius'] < 0 ) {
                            $this->KillfeedCornerRadius->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedCornerRadius->style = "{-fx-color:red;}";
                        } else {
                            $this->KillfeedCornerRadius->tooltipText = "Acceptable value";
                            $this->KillfeedCornerRadius->style = "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedCornerRadiusAdd->visible = true;
                    } 
                    if( $killfeed['LineSpacing'] != "" ) {
                        $this->KillfeedLineSpacingAdd->visible = false;
                        $this->KillfeedLineSpacing->text = $killfeed['LineSpacing'];
                        if ( $killfeed['LineSpacing'] > 15 or $killfeed['LineSpacing'] < 0 ) {
                            $this->KillfeedLineSpacing->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedLineSpacing->style .= "{-fx-color:red;}";
                        } else {
                            $this->KillfeedLineSpacing->tooltipText = "Acceptable value";
                            $this->KillfeedLineSpacing->style .= "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedLineSpacingAdd->visible = true;
                    }
                    if( $killfeed['MaxDeathNotices'] != "" ) {
                        $this->KillfeedMaxDeathNoticesAdd->visible = false;
                        $this->KillfeedMaxDeathNotices->text = $killfeed['MaxDeathNotices'];
                        if ( $killfeed['MaxDeathNotices'] > 20 or $killfeed['MaxDeathNotices'] < 4 ) {
                            $this->KillfeedMaxDeathNotices->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedMaxDeathNotices->style .= "{-fx-color:red;}";
                        } else {
                            $this->KillfeedMaxDeathNotices->tooltipText = "Acceptable value";
                            $this->KillfeedMaxDeathNotices->style .= "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedMaxDeathNoticesAdd->visible = true;
                    }
                    if( $killfeed['LineHeight'] != "" ) {
                        $this->KillfeedLineHeightAdd->visible = false;
                        $this->KillfeedLineHeight->text = $killfeed['LineHeight'];
                        if ( $killfeed['LineHeight'] > 25 or $killfeed['LineHeight'] < 10 ) {
                            $this->KillfeedLineHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedLineHeight->style .= "{-fx-color:red;}";
                        } else {
                            $this->KillfeedLineHeight->tooltipText = "Acceptable value";
                            $this->KillfeedLineHeight->style .= "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedLineHeightAdd->visible = true;
                    }
                    if( $killfeed['IconScale'] != "" ) {
                        $this->KillfeedIconScaleAdd->visible = false;
                        $this->KillfeedIconScale->text = $killfeed['IconScale'];
                        if ( $killfeed['IconScale'] > 0.6 or $killfeed['IconScale'] < 0.1 ) {
                            $this->KillfeedIconScale->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedIconScale->style .= "{-fx-color:red;}";
                        } else {
                            $this->KillfeedIconScale->tooltipText = "Acceptable value";
                            $this->KillfeedIconScale->style .= "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedIconScaleAdd->visible = true;
                    }
                    if( $killfeed['xpos'] != "" ) {
                        $this->KillfeedXPositionAdd->visible = false;
                        $this->KillfeedXPosition->text = $killfeed['xpos'];
                        if ( $killfeed['xpos'] > 3000 or $killfeed['xpos'] < -3000 ) {
                            $this->KillfeedXPosition->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedXPosition->style .= "{-fx-color:red;}";
                        } else {
                            $this->KillfeedXPosition->tooltipText = "Acceptable value";
                            $this->KillfeedXPosition->style .= "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedXPositionAdd->visible = true;
                    }
                    if( $killfeed['ypos'] != "" ) {
                        $this->KillfeedYPositionAdd->visible = false;
                        $this->KillfeedYPosition->text = $killfeed['ypos'];
                        if ( $killfeed['ypos'] > 3000 or $killfeed['ypos'] < -3000 ) {
                            $this->KillfeedYPosition->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedYPosition->style .= "{-fx-color:red;}";
                        } else {
                            $this->KillfeedYPosition->tooltipText = "Acceptable value";
                            $this->KillfeedYPosition->style .= "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedYPositionAdd->visible = true;
                    }
                    if( $killfeed['wide'] != "" ) {
                        $this->KillfeedLayoutWidthAdd->visible = false;
                        $this->KillfeedLayoutWidth->text = $killfeed['wide'];
                        if ( $killfeed['wide'] > 3000 or $killfeed['wide'] < 0 ) {
                            $this->KillfeedLayoutWidth->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedLayoutWidth->style .= "{-fx-color:red;}";
                        } else {
                            $this->KillfeedLayoutWidth->tooltipText = "Acceptable value";
                            $this->KillfeedLayoutWidth->style .= "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedLayoutWidthAdd->visible = true;
                    }
                    if( $killfeed['tall'] != "" ) {
                        $this->KillfeedLayoutHeightAdd->visible = false;
                        $this->KillfeedLayoutHeight->text = $killfeed['tall'];
                        if ( $killfeed['tall'] > 3000 or $killfeed['tall'] < 0 ) {
                            $this->KillfeedLayoutHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedLayoutHeight->style .= "{-fx-color:red;}";
                        } else {
                            $this->KillfeedLayoutHeight->tooltipText = "Acceptable value";
                            $this->KillfeedLayoutHeight->style .= "{-fx-color:white;}";
                        }
                    } else {
                        $this->KillfeedLayoutHeightAdd->visible = true;
                    }
                    if( $killfeed['RightJustify'] != "" ) {
                        $this->KillfeedRightAlignAdd->visible = false;
                        if ( $killfeed['RightJustify'] == "1" ) {
                            $this->KillfeedRightAlign->selected = true;
                        } else {
                            $this->KillfeedRightAlign->selected = false;
                        }
                    } else {
                        $this->KillfeedRightAlignAdd->visible = true;
                    }
                    if( $killfeed['visible'] != "" ) {
                        $this->KillfeedVisibilityAdd->visible = false;
                        if ( $killfeed['RightJustify'] == "1" ) {
                            $this->KillfeedVisibility->selected = true;
                        } else {
                            $this->KillfeedVisibility->selected = false;
                        }
                    } else {
                        $this->KillfeedVisibilityAdd->visible = true;
                    }
                    
                    if( $killfeed['TeamBlue'] != "" ) {
                        $this->KillfeedBluColorAdd->visible = false;
                        $color = explode(" ", $killfeed['TeamBlue']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of TeamBlue. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedBluColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['Scheme']['Colors'][$killfeed['TeamBlue']] ) {
                                $color = explode(" ", $clientscheme['Scheme']['Colors'][$killfeed['TeamBlue']]);
                                $this->KillfeedBluColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of TeamBlue. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedRedColorAdd->visible = true;
                    }
                    if( $killfeed['TeamRed'] != "" ) {
                        $this->KillfeedRedColorAdd->visible = false;
                        $color = explode(" ", $killfeed['TeamRed']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of TeamRed. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedRedColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['Scheme']['Colors'][$killfeed['TeamRed']] ) {
                                $color = explode(" ", $clientscheme['Scheme']['Colors'][$killfeed['TeamRed']]);
                                $this->KillfeedRedColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of TeamRed. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedRedColorAdd->visible = true;
                    }
                    if( $killfeed['IconColor'] != "" ) {
                        $this->KillfeedIconColorAdd->visible = false;
                        $color = explode(" ", $killfeed['IconColor']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of IconColor. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedIconColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['Scheme']['Colors'][$killfeed['IconColor']] ) {
                                $color = explode(" ", $clientscheme['Scheme']['Colors'][$killfeed['IconColor']]);
                                $this->KillfeedIconColor->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of IconColor. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedIconColorAdd->visible = true;
                    }                   
                    if( $killfeed['BaseBackgroundColor'] != "" ) {
                        $this->KillfeedPanelBackgroundAdd->visible = false;
                        $color = explode(" ", $killfeed['BaseBackgroundColor']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of BaseBackgroundColor. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedPanelBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['Scheme']['Colors'][$killfeed['BaseBackgroundColor']] ) {
                                $color = explode(" ", $clientscheme['Scheme']['Colors'][$killfeed['BaseBackgroundColor']]);
                                $this->KillfeedPanelBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of BaseBackgroundColor. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedPanelBackgroundAdd->visible = true;
                    }  
                    if( $killfeed['LocalPlayerColor'] != "" ) {
                        $this->KillfeedPanelLocalPlayerAdd->visible = false;
                        $color = explode(" ", $killfeed['LocalPlayerColor']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of LocalPlayerColor. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedPanelLocalPlayer->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['Scheme']['Colors'][$killfeed['LocalPlayerColor']] ) {
                                $color = explode(" ", $clientscheme['Scheme']['Colors'][$killfeed['LocalPlayerColor']]);
                                $this->KillfeedPanelLocalPlayer->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            } else {
                                echo("Error! Invalid color of LocalPlayerColor. Can't read value!");       
                            }
                        }
                    } else {
                        $this->KillfeedPanelLocalPlayerAdd->visible = true;
                    } 
                    if( $killfeed['LocalBackgroundColor'] != "" ) {
                        $this->KillfeedLocalBackgroundAdd->visible = false;
                        $color = explode(" ", $killfeed['LocalBackgroundColor']);
                        if ( count($color) == 4 ) {
                            if ( $color[0] > 255 or $color[0] < 0 ) {
                                UXDialog::showAndWait("Error! Invalid color of LocalBackgroundColor. Can't read value. But you can replace it.");    
                            } else {
                                $this->KillfeedLocalBackground->value = UXColor::of(UXColor::rgb($color[0], $color[1], $color[2], round($color[3]/255, 1) )->getWebValue());
                            }
                        } else {
                            $clientscheme = VDF::decodeFile("$huddir\\resource\\clientscheme.res");
                            if( $clientscheme['Scheme']['Colors'][$killfeed['LocalBackgroundColor']] ) {
                                $color = explode(" ", $clientscheme['Scheme']['Colors'][$killfeed['LocalBackgroundColor']]);
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
        if ( $hudlayout['Resource/HudLayout.res']['HudDeathNotice'] ) {
            if ( $this->KillfeedCornerRadiusAdd->visible != true or trim($this->KillfeedCornerRadius->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['CornerRadius'] = $this->KillfeedCornerRadius->text;
            }
            if ( $this->KillfeedLineSpacingAdd->visible != true or trim($this->KillfeedLineSpacing->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['LineSpacing'] = $this->KillfeedLineSpacing->text;
            }
            if ( $this->KillfeedMaxDeathNoticesAdd->visible != true or trim($this->KillfeedMaxDeathNotices->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['MaxDeathNotices'] = $this->KillfeedMaxDeathNotices->text;
            }
            if ( $this->KillfeedLineHeightAdd->visible != true or trim($this->KillfeedLineHeight->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['LineHeight'] = $this->KillfeedLineHeight->text;
            }
            if ( $this->KillfeedIconScaleAdd->visible != true or trim($this->KillfeedIconScale->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['IconScale'] = $this->KillfeedIconScale->text;
            }
            if ( $this->KillfeedXPositionAdd->visible != true or trim($this->KillfeedXPosition->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['xpos'] = $this->KillfeedXPosition->text;
            }
            if ( $this->KillfeedYPositionAdd->visible != true or trim($this->KillfeedYPosition->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['ypos'] = $this->KillfeedYPosition->text;
            }
            if ( $this->KillfeedLayoutWidthAdd->visible != true or trim($this->KillfeedLayoutWidth->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['wide'] = $this->KillfeedLayoutWidth->text;
            }
            if ( $this->KillfeedLayoutHeightAdd->visible != true or trim($this->KillfeedLayoutHeight->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['tall'] = $this->KillfeedLayoutHeight->text;
            }
            if ( $this->KillfeedRightAlignAdd->visible != true ) {
                if ( $this->KillfeedRightAlign->selected == true ) {
                    $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['RightJustify'] = "1";
                } else {
                    $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['RightJustify'] = "0";    
                }
            }
            if ( $this->KillfeedVisibilityAdd->visible != true ) {
                if ( $this->KillfeedVisibility->selected == true ) {
                    $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['visible'] = "1";
                } else {
                    $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['visible'] = "0";    
                }
            }
            if ( $this->KillfeedBluColorAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedBluColor->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedBluColor->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedBluColor->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedBluColor->value)->opacity);
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['TeamBlue'] = "$r $g $b $o";
            }
            if ( $this->KillfeedRedColorAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedRedColor->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedRedColor->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedRedColor->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedRedColor->value)->opacity);
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['TeamRed'] = "$r $g $b $o";
            }
            if ( $this->KillfeedIconColorAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedIconColor->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedIconColor->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedIconColor->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedIconColor->value)->opacity);
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['IconColor'] = "$r $g $b $o";
            }
            if ( $this->KillfeedPanelBackgroundAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedPanelBackground->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedPanelBackground->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedPanelBackground->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedPanelBackground->value)->opacity);
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['BaseBackgroundColor'] = "$r $g $b $o";
            }
            if ( $this->KillfeedPanelLocalPlayerAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedPanelLocalPlayer->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedPanelLocalPlayer->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedPanelLocalPlayer->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedPanelLocalPlayer->value)->opacity);
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['LocalPlayerColor'] = "$r $g $b $o";
            }
            if ( $this->KillfeedLocalBackgroundAdd->visible != true ) {
                $r = round(255 * UXColor::of($this->KillfeedLocalBackground->value)->red);
                $g = round(255 * UXColor::of($this->KillfeedLocalBackground->value)->green);
                $b = round(255 * UXColor::of($this->KillfeedLocalBackground->value)->blue);
                $o = round(255 * UXColor::of($this->KillfeedLocalBackground->value)->opacity);
                $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['LocalBackgroundColor'] = "$r $g $b $o";
            }
            VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout);  
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
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['CornerRadius'] = "3";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout);
    }

    /**
     * @event KillfeedLineSpacingAdd.click-Left 
     */
    function doKillfeedLineSpacingAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['LineSpacing'] = "4";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout);
    }

    /**
     * @event KillfeedMaxDeathNoticesAdd.click-Left 
     */
    function doKillfeedMaxDeathNoticesAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['MaxDeathNotices'] = "4";  
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedLineHeightAdd.click-Left 
     */
    function doKillfeedLineHeightAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['LineHeight'] = "16";  
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedIconScaleAdd.click-Left 
     */
    function doKillfeedIconScaleAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['IconScale'] = "0.35";  
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedXPositionAdd.click-Left 
     */
    function doKillfeedXPositionAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['xpos'] = "r640";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedYPositionAdd.click-Left 
     */
    function doKillfeedYPositionAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['ypos'] = "18";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedLayoutWidthAdd.click-Left 
     */
    function doKillfeedLayoutWidthAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['wide'] = "580";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedLayoutHeightAdd.click-Left 
     */
    function doKillfeedLayoutHeightAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['tall'] = "468";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedRightAlignAdd.click-Left 
     */
    function doKillfeedRightAlignAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['RightJustify'] = "1";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedVisibilityAdd.mouseDown-Left 
     */
    function doKillfeedVisibilityAddMouseDownLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['Visible'] = "1";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedBluColorAdd.mouseDown-Left 
     */
    function doKillfeedBluColorAddMouseDownLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['TeamBlue'] = "0 135 255 255";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }


    /**
     * @event KillfeedIconColorAdd.click-Left 
     */
    function doKillfeedIconColorAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['IconColor'] = "119 119 119 90";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedPanelBackgroundAdd.click-Left 
     */
    function doKillfeedPanelBackgroundAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['BaseBackgroundColor'] = "46 43 42 220";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedPanelLocalPlayerAdd.click-Left 
     */
    function doKillfeedPanelLocalPlayerAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['LocalPlayerColor'] = "119 119 119 90";         
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedLocalBackgroundAdd.click-Left 
     */
    function doKillfeedLocalBackgroundAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDeathNotice']['LocalBackgroundColor'] = "245 229 196 200";
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event KillfeedNotFoundedAddButton.click-Left 
     */
    function doKillfeedNotFoundedAddButtonClickLeft(UXMouseEvent $event = null)
    {    
        $hudlayout = VDF::decodeFile("$huddir\\scripts\\hudlayout.res");
        $killfeed = [
            'fieldName' => "HudDeathNotice",
            'xpos' => "r640",
            'ypos' => "18",
            'wide' => "580",
            'tall' => "428",                                     
            'visible' => "1",
            'enabled' => "1",
            'CornerRadius' => "3",
            'LineSpacing' => "4",
            'MaxDeathNotices' => "4", 
            'LineHeight' => "16",
            'IconScale' => "0.35",
            'TeamBlue' => "0 135 255 255",
            'TeamRed' => "153 0 0 255",
            'IconColor' => "119 119 119 90",
            'BaseBackgroundColor' => "46 43 42 220",
            'LocalPlayerColor' => "119 119 119 90",  
            'LocalBackgroundColor' => "245 229 196 200",
        ];
        $hudlayout['Resource/HudLayout.res']['HudDeathNotice'] = $killfeed;
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
                    $this->KillfeedCornerRadius->style = "{-fx-color:red;}";
                } else {
                    $this->KillfeedCornerRadius->tooltipText = "Acceptable value";
                    $this->KillfeedCornerRadius->style = "{-fx-color:white;}";
                }

                $this->KillfeedLineSpacing->text = $presets[$selectedindex]['Preset']['LineSpacing'];
                if ( $presets[$selectedindex]['Preset']['LineSpacing'] > 15 or $presets[$selectedindex]['Preset']['LineSpacing'] < 0 ) {
                    $this->KillfeedLineSpacing->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedLineSpacing->style .= "{-fx-color:red;}";
                } else {
                    $this->KillfeedLineSpacing->tooltipText = "Acceptable value";
                    $this->KillfeedLineSpacing->style .= "{-fx-color:white;}";
                }

                $this->KillfeedMaxDeathNotices->text = $presets[$selectedindex]['Preset']['MaxDeathNotices'];
                if ( $presets[$selectedindex]['Preset']['MaxDeathNotices'] > 20 or $presets[$selectedindex]['Preset']['MaxDeathNotices'] < 4 ) {
                    $this->KillfeedMaxDeathNotices->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedMaxDeathNotices->style .= "{-fx-color:red;}";
                } else {
                    $this->KillfeedMaxDeathNotices->tooltipText = "Acceptable value";
                    $this->KillfeedMaxDeathNotices->style .= "{-fx-color:white;}";
                }
                
                $this->KillfeedLineHeight->text = $presets[$selectedindex]['Preset']['LineHeight'];
                if ( $presets[$selectedindex]['Preset']['LineHeight'] > 25 or $presets[$selectedindex]['Preset']['LineHeight'] < 10 ) {
                    $this->KillfeedLineHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedLineHeight->style .= "{-fx-color:red;}";
                } else {
                    $this->KillfeedLineHeight->tooltipText = "Acceptable value";
                    $this->KillfeedLineHeight->style .= "{-fx-color:white;}";
                }

                $this->KillfeedIconScale->text = $presets[$selectedindex]['Preset']['IconScale'];
                if ( $presets[$selectedindex]['Preset']['IconScale'] > 0.6 or $presets[$selectedindex]['Preset']['IconScale'] < 0.1 ) {
                    $this->KillfeedIconScale->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedIconScale->style .= "{-fx-color:red;}";
                } else {
                    $this->KillfeedIconScale->tooltipText = "Acceptable value";
                    $this->KillfeedIconScale->style .= "{-fx-color:white;}";
                }

                $this->KillfeedXPosition->text = $presets[$selectedindex]['Preset']['xpos'];
                if ( $presets[$selectedindex]['Preset']['xpos'] > 3000 or $presets[$selectedindex]['Preset']['xpos'] < -3000 ) {
                    $this->KillfeedXPosition->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedXPosition->style .= "{-fx-color:red;}";
                } else {
                    $this->KillfeedXPosition->tooltipText = "Acceptable value";
                    $this->KillfeedXPosition->style .= "{-fx-color:white;}";
                }

                $this->KillfeedYPosition->text = $presets[$selectedindex]['Preset']['ypos'];
                if ( $presets[$selectedindex]['Preset']['ypos'] > 3000 or $presets[$selectedindex]['Preset']['ypos'] < -3000 ) {
                    $this->KillfeedYPosition->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedYPosition->style .= "{-fx-color:red;}";
                } else {
                    $this->KillfeedYPosition->tooltipText = "Acceptable value";
                    $this->KillfeedYPosition->style .= "{-fx-color:white;}";
                }

                $this->KillfeedLayoutWidth->text = $presets[$selectedindex]['Preset']['wide'];
                if ( $presets[$selectedindex]['Preset']['wide'] > 3000 or $presets[$selectedindex]['Preset']['wide'] < 0 ) {
                    $this->KillfeedLayoutWidth->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedLayoutWidth->style .= "{-fx-color:red;}";
                } else {
                    $this->KillfeedLayoutWidth->tooltipText = "Acceptable value";
                    $this->KillfeedLayoutWidth->style .= "{-fx-color:white;}";
                }

                $this->KillfeedLayoutHeight->text = $presets[$selectedindex]['Preset']['tall'];
                if ( $presets[$selectedindex]['Preset']['tall'] > 3000 or $presets[$selectedindex]['Preset']['tall'] < 0 ) {
                    $this->KillfeedLayoutHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                    $this->KillfeedLayoutHeight->style .= "{-fx-color:red;}";
                } else {
                    $this->KillfeedLayoutHeight->tooltipText = "Acceptable value";
                    $this->KillfeedLayoutHeight->style .= "{-fx-color:white;}";
                }

                if ( $presets[$selectedindex]['Preset']['RightJustify'] == "1" ) {
                    $this->KillfeedRightAlign->selected = true;
                } else {
                    $this->KillfeedRightAlign->selected = false;
                }

                if ( $presets[$selectedindex]['Preset']['RightJustify'] == "1" ) {
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
        global $hudlayout;
        if ( is_file("$huddir\\scripts\\hudlayout.res") and file_get_contents("$huddir\\scripts\\hudlayout.res") != NULL ) {
            $hudlayout = VDF::decodeFile("$huddir\\scripts\\hudlayout.res");
            if ( $hudlayout['Resource/HudLayout.res']['HudDamageIndicator'] and ! empty($hudlayout['Resource/HudLayout.res']['HudDamageIndicator']) ) {
                $this->DamageIndicatorSettingsPanel->visible = true; $this->DamageIndicatorPreviewPanel->visible = true;
                $this->DamageIndicatorApplyButton->visible = true;
                $this->DamageIndicatorPresetsButton->visible = true; $this->DamageIndicatorNotFoundedAddButton->visible = false;
                $this->DamageIndicatorNotFoundedDescription->visible = false; $this->DamageIndicatorNotFoundedTitle->visible = false;
                $damageindicator = $hudlayout['Resource/HudLayout.res']['HudDamageIndicator'];
                
                if( $damageindicator['fieldName'] == 'HudDamageIndicator' ) {
                    if( $damageindicator['MinimumWidth'] != "" ) {
                        $this->DamageIndicatorMinWidthAdd->visible = false;
                        $this->DamageIndicatorMinWidth->text = $damageindicator['MinimumWidth'];
                        if ( $damageindicator['MinimumWidth'] > 35 or $damageindicator['MinimumWidth'] < 0 ) {
                            $this->DamageIndicatorMinWidth->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->KillfeedCornerRadius->style = "{-fx-color:red;}";
                        } else {
                            $this->DamageIndicatorMinWidth->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMinWidth->style = "{-fx-color:white;}";
                        }
                    } else {
                        $this->DamageIndicatorMinWidthAdd->visible = true;
                    }
                    if( $damageindicator['MaximumWidth'] != "" ) {
                        $this->DamageIndicatorMaxWidthAdd->visible = false;
                        $this->DamageIndicatorMaxWidth->text = $damageindicator['MaximumWidth'];
                        if ( $damageindicator['MaximumWidth'] > 35 or $damageindicator['MaximumWidth'] < 0 ) {
                            $this->DamageIndicatorMaxWidth->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMaxWidth->style = "{-fx-color:red;}";
                        } else {
                            $this->DamageIndicatorMaxWidth->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMaxWidth->style = "{-fx-color:white;}";
                        }
                    } else {
                        $this->DamageIndicatorMaxWidthAdd->visible = true;
                    }
                    if( $damageindicator['StartRadius'] != "" ) {
                        $this->DamageIndicatorStartRadiusAdd->visible = false;
                        $this->DamageIndicatorStartRadius->text = $damageindicator['StartRadius'];
                        if ( $damageindicator['StartRadius'] > 200 or $damageindicator['StartRadius'] < 50 ) {
                            $this->DamageIndicatorStartRadius->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorStartRadius->style = "{-fx-color:red;}";
                        } else {
                            $this->DamageIndicatorStartRadius->tooltipText = "Acceptable value";
                            $this->DamageIndicatorStartRadius->style = "{-fx-color:white;}";
                        }
                    } else {
                        $this->DamageIndicatorStartRadiusAdd->visible = true;
                    }
                    if( $damageindicator['EndRadius'] != "" ) {
                        $this->DamageIndicatorEndRadiusAdd->visible = false;
                        $this->DamageIndicatorEndRadius->text = $damageindicator['EndRadius'];
                        if ( $damageindicator['EndRadius'] > 35 or $damageindicator['EndRadius'] < 0 ) {
                            $this->DamageIndicatorEndRadius->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorEndRadius->style = "{-fx-color:red;}";
                        } else {
                            $this->DamageIndicatorEndRadius->tooltipText = "Acceptable value";
                            $this->DamageIndicatorEndRadius->style = "{-fx-color:white;}";
                        }
                    } else {
                        $this->DamageIndicatorEndRadiusAdd->visible = true;
                    }
                    if( $damageindicator['MinimumHeight'] != "" ) {
                        $this->DamageIndicatorMinHeightAdd->visible = false;
                        $this->DamageIndicatorMinHeight->text = $damageindicator['MinimumHeight'];
                        if ( $damageindicator['MinimumHeight'] > 10 or $damageindicator['MinimumHeight'] < 60 ) {
                            $this->DamageIndicatorMinHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMinHeight->style = "{-fx-color:red;}";
                        } else {
                            $this->DamageIndicatorMinHeight->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMinHeight->style = "{-fx-color:white;}";
                        }
                    } else {
                        $this->DamageIndicatorMinHeightAdd->visible = true;
                    }
                    if( $damageindicator['MaximumHeight'] != "" ) {
                        $this->DamageIndicatorMaxHeightAdd->visible = false;
                        $this->DamageIndicatorMaxHeight->text = $damageindicator['MaximumHeight'];
                        if ( $damageindicator['MaximumHeight'] > 35 or $damageindicator['MaximumHeight'] < 120 ) {
                            $this->DamageIndicatorMaxHeight->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMaxHeight->style = "{-fx-color:red;}";
                        } else {
                            $this->DamageIndicatorMaxHeight->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMaxHeight->style = "{-fx-color:white;}";
                        }
                    } else {
                        $this->DamageIndicatorMaxHeightAdd->visible = true;
                    }
                    if( $damageindicator['MinimumTime'] != "" ) {
                        $this->DamageIndicatorMinTimeAdd->visible = false;
                        $this->DamageIndicatorMinTime->text = $damageindicator['MinimumTime'];
                        if ( $damageindicator['MinimumTime'] > 35 or $damageindicator['MinimumTime'] < 120 ) {
                            $this->DamageIndicatorMinTime->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMinTime->style = "{-fx-color:red;}";
                        } else {
                            $this->DamageIndicatorMinTime->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMinTime->style = "{-fx-color:white;}";
                        }
                    } else {
                        $this->DamageIndicatorMinTimeAdd->visible = true;
                    }
                    if( $damageindicator['MaximumTime'] != "" ) {
                        $this->DamageIndicatorMaxTimeAdd->visible = false;
                        $this->DamageIndicatorMaxTime->text = $damageindicator['MaximumTime'];
                        if ( $damageindicator['MaximumTime'] > 35 or $damageindicator['MaximumTime'] < 120 ) {
                            $this->DamageIndicatorMaxTime->tooltipText = "Unrecommended value. Element might display not correct!";
                            $this->DamageIndicatorMaxTime->style = "{-fx-color:red;}";
                        } else {
                            $this->DamageIndicatorMaxTime->tooltipText = "Acceptable value";
                            $this->DamageIndicatorMaxTime->style = "{-fx-color:white;}";
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
            'fieldName' => "HudDamageIndicator",                                   
            'visible' => "1",
            'enabled' => "1",
            'MinimumWidth' => "10",
            'MaximumWidth' => "10",
            'StartRadius' => "80",
            'EndRadius' => "80",
            'MinimumHeight' => "30",
            'MaximumHeight' => "60",
            'MinimumTime' => "1",
            'MaximumTime' => "2",
        ];
        $hudlayout['Resource/HudLayout.res']['HudDamageIndicator'] = $damageindicator;
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();  
    }

    /**
     * @event DamageIndicatorMinWidthAdd.click-Left 
     */
    function doDamageIndicatorMinWidthAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MinimumWidth'] = "10";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorMaxWidthAdd.click-Left 
     */
    function doDamageIndicatorMaxWidthAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MaximumWidth'] = "10";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorStartRadiusAdd.click-Left 
     */
    function doDamageIndicatorStartRadiusAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['StartRadius'] = "80";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorEndRadiusAdd.click-Left 
     */
    function doDamageIndicatorEndRadiusAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['EndRadius'] = "80";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }


    /**
     * @event DamageIndicatorMaxHeightAdd.click-Left 
     */
    function doDamageIndicatorMaxHeightAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MaximumHeight'] = "60";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorMinTimeAdd.click-Left 
     */
    function doDamageIndicatorMinTimeAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MinimumTime'] = "1";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorMaxTimeAdd.click-Left 
     */
    function doDamageIndicatorMaxTimeAddClickLeft(UXMouseEvent $event = null)
    {    
        global $hudlayout; $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MaximumTime'] = "2";      
        global $huddir; VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout); $this->doKillfeedUpdateButtonClickLeft();   
    }

    /**
     * @event DamageIndicatorApplyButton.click-Left 
     */
    function doDamageIndicatorApplyButtonClickLeft(UXMouseEvent $event = null)
    {    
       $this->showPreloader();
        global $hudlayout; global $huddir;
        if ( $hudlayout['Resource/HudLayout.res']['HudDamageIndicator'] ) {
            if ( $this->DamageIndicatorMinWidthAdd->visible != true or trim($this->DamageIndicatorMinWidth->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MinimumWidth'] = $this->DamageIndicatorMinWidth->text;
            }
            if ( $this->DamageIndicatorMaxWidthAdd->visible != true or trim($this->DamageIndicatorMaxWidth->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MaximumWidth'] = $this->DamageIndicatorMaxWidth->text;
            }
            if ( $this->DamageIndicatorStartRadiusAdd->visible != true or trim($this->DamageIndicatorStartRadius->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['StartRadius'] = $this->DamageIndicatorStartRadius->text;
            }
            if ( $this->DamageIndicatorEndRadiusAdd->visible != true or trim($this->DamageIndicatorEndRadius->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['EndRadius'] = $this->DamageIndicatorEndRadius->text;
            }
            if ( $this->DamageIndicatorMinHeightAdd->visible != true or trim($this->DamageIndicatorMinHeight->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MinimumHeight'] = $this->DamageIndicatorMinHeight->text;
            }
            if ( $this->DamageIndicatorMaxHeightAdd->visible != true or trim($this->DamageIndicatorMaxHeight->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MaximumHeight'] = $this->DamageIndicatorMaxHeight->text;
            }
            if ( $this->DamageIndicatorMaxTimeAdd->visible != true or trim($this->DamageIndicatorMaxTime->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MaximumTime'] = $this->DamageIndicatorMaxTime->text;
            }
            if ( $this->DamageIndicatorMinTimeAdd->visible != true or trim($this->DamageIndicatorMinTime->text) != "" ) {
                $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['MinimumTime'] = $this->DamageIndicatorMinTime->text;
            }

            if ( $this->DamageIndicatorVisibilityAdd->visible != true ) {
                if ( $this->DamageIndicatorVisibility->selected == true ) {
                    $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['visible'] = "1";
                } else {
                    $hudlayout['Resource/HudLayout.res']['HudDamageIndicator']['visible'] = "0";    
                }
            }
            VDF::encodeFile("$huddir\\scripts\\hudlayout.res", $hudlayout);  
        } else {
            pre("Error! Can't read hudlayouts. It's can be broken.");  
        }
        $this->hidePreloader();
    }


    
    

}
