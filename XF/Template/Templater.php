<?php
namespace cv6\Core\XF\Template;

class Templater extends XFCP_Templater
{

    public function addDefaultHandlers()
    {
        parent::addDefaultHandlers();
        $this->addFunction('iconize', 'fnIconize');
        $this->addFunction('ddcopyright', 'fnDiceDragonsCopyright');
    }

    /** @return string for less Icons @fa-var-regular-circle */
    public function fnIconize($templater, &$escape, $icon)
    {
        return str_replace('fa-', '@fa-var-', $icon);
    }
    
    protected function cv6DependentJs($addOn) 
    {
        $dependencies = [
            'cv6/Core' => [
                'cv6/core/asset.js' => [
                    ['dev' => 'xf/form.js', 'prod' => 'xf/form.min.js']
                ]
            ]
        ];

        \XF::app()->fire('cv6_add_js_dependencies', [&$dependencies]);

        if (array_key_exists($addOn, $dependencies))
            return $dependencies[$addOn];
        
        return [];
    } 

    public function includeJs(array $options)
    {
        $tmpOptions = array_replace([
            'src'   => null,
            'defer' => true,
            'addon' => null,
            'min'   => null,
            'dev'   => null,
            'prod'  => null,
            'root'  => false,
        ], $options);

        $dependentJS = $this->cv6DependentJs($tmpOptions['addon']);

        $developmentConfig = $this->app->config('development');
        $productionMode = empty($developmentConfig['fullJs']);

        if (!empty($dependentJS))
        {
            if ($productionMode) {
                $jsArr = $dependentJS[$tmpOptions['prod']];
            }
            else {
                $jsArr = $dependentJS[$tmpOptions['dev']];
            }
            if (is_array($jsArr))
            {
                foreach ($jsArr as $JS)
                {
                    parent::includeJs($JS);
                }
            }

        }

        parent::includeJs($options);
    }

    public function fnDiceDragonsCopyright($templater, &$escape)
    {
        $escape = false;
        $copyrightList = [  ];
        $phrasedList = [];

        \XF::app()->fire('cv6_add_copyright', [&$copyrightList]);

        $html = '';
        if (!empty($copyrightList))
        {
            foreach($copyrightList as $addonId => $copyright)
            {
                $phrasedList[$addonId] = \XF::phrase('cv6_copyright_'.$copyright);
            }
            $html = \XF::phrase('cv6_copyright_base') . ' ' . implode(" | ", $phrasedList);
        }
        return $html;
    }
} 