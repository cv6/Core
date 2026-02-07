<?php

namespace cv6\Core\Option;

use XF\Option\AbstractOption;
use XF\Entity\Option;
use cv6\Core\XF\Repository\IconRepository;

class Check extends AbstractOption
{
    /**
     * @param string $value
     *
     * @return bool
     */
    public static function verifyDefault(&$value, Option $option)
    {

        if ($option->isInsert())
        {
            // always allow a new value to be submitted so we don't blow up installation
            return true;
        }

        $displayStyles = array_keys(\XF::app()->options()->cv6EnableSettingDisplay);

        if (!empty($displayStyles) && !in_array($value, $displayStyles))
        {
            $value=$displayStyles[0];
        }
        return true;
    }

    /**
     * @param array $values
     *
     * @return bool
     */
    public static function verifyActive(&$value, Option $option)
    {
        if ($option->isInsert())
        {
            // always allow a new value to be submitted so we don't blow up installation
            return true;
        }
        
        return !empty($value);
    }

    public static function defaultWhenEmpty(&$value, Option $option)
    {
        if ($option->isInsert())
        {
            // always allow a new value to be submitted so we don't blow up installation
            return true;
        }

        if (empty($value)) {
            $value = $option['default_value'];
        }
        return true;
    }

    public static function verifyValidIconMandatory(&$value, Option $option, $option_id)
    {
        return self::verifyValidIcon($value, $option, $option_id, true);
    }

    public static function verifyValidIcon(&$value, Option $option, $option_id, $mandatory = false)
    {

        if ($option->isInsert())
        {
            // always allow a new value to be submitted so we don't blow up installation
            return true;
        }

        /** @var IconRepository $iconRepo */
        $iconRepo = \XF::repository('XF:Icon');
        $icon = [];

        $check = $iconRepo->getIconsFromClasses($value);

        if (empty($check))
        {
            if ($mandatory)
            {
                $option->error(\XF::phrase('cv6_please_enter_valid_icon_classes'), $option->option_id);
                return false;
            }
            return true;
        }

        if (Count($check) > 1)
        {
            $option->error(\XF::phrase('cv6_please_enter_only_one_icon_class'), $option->option_id);
            return false;
        }

        if (method_exists($iconRepo, 'getUnknownIconData'))
        {
            $unknown = $iconRepo->getUnknownIconData($value);
            $unknownItems = array_diff($unknown, $check[0]);

            if (!empty($unknownItems))
            {
                $option->error(\XF::phrase('cv6_please_remove_unknown_icon_classes_x', ['items' => implode(', ', $unknownItems)]), $option->option_id);
                return false;
            }
        }

        return true;
    }
    

}