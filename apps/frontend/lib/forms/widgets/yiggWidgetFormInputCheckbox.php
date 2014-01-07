<?php
class yiggWidgetFormInputCheckbox extends sfWidgetFormInputCheckbox{

    protected function configure($options = array(), $attributes = array()) {
        parent::configure($options, $attributes);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $link = $attributes['link'];
        $attributes = $this->fixFormId($attributes);
        $output = '<input type="checkbox" name="'.$name.'" id="'.$attributes['id'].'" class="'.$attributes['class'].'" >';
        $output .= link_to($link['name'],$link['url']);

        return $output;
    }
}