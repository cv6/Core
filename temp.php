<?php
class test
{
    /**
     * @deprecated
     */
    protected function createFields()
    {
        $fields = $this->getFieldStructure();
        $ForumFieldRepo = $this->repository('XF:ForumField');
        foreach ($fields as $field_id => $build) {
            $field = $this->em()->create('XF:ThreadField');
            $field->field_id = $field_id;
            $field->display_group = 'before';

            if ($build['title']) {
                $newTitle = $build['title'];
                unset($build['title']);
            }
            if ($build['description']) {
                $newDesc = $build['description'];
                unset($build['description']);
            }

            $field->bulkSet($build);
            $field->save();
            if ($newTitle) {
                $title = $field->getMasterPhrase(true);
                $title->phrase_text = $newTitle;
                $title->save();
            }
            if ($newDesc) {
                $description = $field->getMasterPhrase(false);
                $description->phrase_text = $newDesc;
                $description->save();
            }
            // $ForumFieldRepo->updateFieldAssociations($field, [\XF::options()->cv6SuggestForumId]);
        }
    }

    protected function removeFields()
    {
        $fields = $this->getFieldStructure();
        $ForumFieldRepo = $this->repository('XF:ForumField');
        foreach ($fields as $field_id => $build) {
            $field = $this->finder('XF:ThreadField')->where('field_id', $field_id)->fetchOne();
            if ($field) {
                // $ForumFieldRepo->removeFieldAssociations($field);
                $field->delete();
            }
        }
    }


    private function getFieldStructure()
    {
        return [
            'cv6CharEdit_setting_title' => [
                'title' => \XF::phrase('cv6_setting_title'),
                'description' => \XF::phrase('cv6_setting_title_explain'),
                'display_order' => '1100',
                'field_type' => 'textbox',
                'required' => true,
                'match_type' => 'none',
            ],
            'cv6CharEdit_description' =>
            [
                'title' => \XF::phrase('cv6_setting_description'),
                'description' => \XF::phrase('cv6_setting_description_explain'),
                'display_order' => '1200',
                'field_type' => 'bbcode',
                'required' => true,
                'match_type' => 'none',
            ],
            'cv6CharEdit_publisher' =>
            [
                'title' => \XF::phrase('cv6_publisher_title'),
                'description' => \XF::phrase('cv6_publisher_title_description'),
                'display_order' => '1300',
                'field_type' => 'textbox',
                'required' => true,
                'match_type' => 'none',
            ],
            'cv6CharEdit_url' =>
            [
                'title' => \XF::phrase('cv6_publisher_url'),
                'description' => \XF::phrase('cv6_url_description'),
                'display_order' => '1400',
                'field_type' => 'textbox',
                'required' => false,
                'match_type' => 'url',
            ],
        ];
    }
}
