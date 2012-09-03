<?php

class yiggWidgetFormImageSlider extends sfWidgetForm
{

    /**
     * @see sfWidgetForm
     */
    protected function configure($options = array(), $attributes = array())
    {
        $this->setLabel(false);
    }

    /**
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        ob_start();
        echo "<div id='carousel'></div>".use_javascript('yiggImageSlider.js');
        return ob_get_clean();
    }
}
