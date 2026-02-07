<?php

namespace cv6\Core\Option;

use XF\Option\AbstractOption;
use XF\Entity\Option;

class Node extends AbstractOption
{
    public static function renderSelect(Option $option, array $htmlParams)
    {
        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = \XF::repository('XF:Node');

        $nodeList = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());
        $nodes = $nodeList->getFlattened(0);

        $inputName = $htmlParams['name'] ?? $option->option_id;

        $params =  [
            'nodes' => $nodes,
            'option' => $option
        ];

        return static::getTemplate('admin:option_template_cv6_node_select', $option, $htmlParams , $params);

    }

    public static function verifyOption(&$value, \XF\Entity\Option $option, $optionId)
    {
        if (!is_array($value))
        {
            $value = [];
        }

        $value = array_unique(array_filter($value));

        return true;
    }

}
