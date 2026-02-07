<?php

namespace cv6\Core;

class Listener
{
    public static function templaterSetup(\XF\Container $container, \XF\Template\Templater &$templater)
    {
        $templater->addFunction('cv6icon', function ($templater, &$escape, $str)
        {
            $iconParts = explode("-", trim($str, "@"));
            if (count($iconParts) > 2)
            {
                unset($iconParts[1]);
                $str = join("-",$iconParts);
            }
            return $str;
        });
    }
}