<?php

namespace Derralf\GridFieldToggleVisibility;

use SilverStripe\View\Requirements;

class GridFieldToggleVisibility
{
    public static function include_requirements()
    {
        Requirements::css('derralf/silverstripe-gridfieldtogglevisibility:css/GridFieldToggleVisibility.css');
    }
}