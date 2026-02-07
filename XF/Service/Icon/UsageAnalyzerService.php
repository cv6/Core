<?php
namespace cv6\Core\XF\Service\Icon;

use XF\Repository\ForumTypeRepository;

class UsageAnalyzerService extends XFCP_UsageAnalyzerService
{

    protected function getContentTypeSteps(): array
    {

        $steps = parent::getContentTypeSteps();

        $steps['style_property'][] = 'stepCore6StyleProperty';
        $steps['extra'][] = 'stepCore6Extra';

        $this->app->fire('icon_usage_analyzer_steps', [&$steps, $this]);
        return $steps;
    }

    protected function stepCore6Extra(?int $lastOffset, float $maxRunTime): ?int
    {

        $optionClassPairs = preg_split('/\r?\n/', \XF::options()->cv6ExtraFaIcons ?? '');
        $this->recordIconsFromClassPairs('extra', $optionClassPairs);
        return null;
    }

    protected function stepCore6StyleProperty(?int $lastOffset, float $maxRunTime): ?int
    {
        $batchSize = 500;

        $dataPairs = $this->fetchDataPairs(
            'xf_style_property',
            'property_id',
            'property_value',
            "value_type = 'template' AND value_parameters LIKE '%template=cv6_style_template_%'",
            $batchSize,
            $lastOffset
        );
        $this->recordIconsFromLessPairs('style_property', $dataPairs);

        return $this->getLastOffset($dataPairs, $batchSize);
    }

}
