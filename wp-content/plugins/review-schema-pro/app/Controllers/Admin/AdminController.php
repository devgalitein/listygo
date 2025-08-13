<?php

namespace Rtrsp\Controllers\Admin; 

use Rtrsp\Controllers\Admin\Meta\MetaOptions;
use Rtrsp\Traits\SingletonTrait;

class AdminController {
    use SingletonTrait;
    public function init() {  
        ActionHook::getInstance(); 
        HookFilter::getInstance(); 
        MetaOptions::getInstance(); 
        RtrsLicensing::init();
    } 
}