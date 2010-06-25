<?php
/**
 * Calendar Jquery Plugin view helper
 *
 * @author Edder Rojas Douglas <edder.rojas@gmail.com>
 */
class My_View_Helper_JqCalendar
    extends App_View_Helper_Abstract
{
    protected $_container = '<div %s></div>';

    /**
     * Create a javascript function to show the calendar
     * @param string $name
     * @param string $options json formated
     * @param bool $canAdd
     * @return string
     */
    protected function createFunction($name, $jsonOptions, $options, $canAdd)
    {
        $this->view->headScript()->appendScript("var $name;");
        $rv = array();
        $rv[] = '<script type="text/javascript">';
        $rv[] = "var op = $jsonOptions;";
        $rv[] = "op.showday = new Date();";
        $rv[] = "op.height = document.documentElement.clientHeight - 50;";
        if (isset($options['quickAddCallback']) && $canAdd)
            $rv[] = "op.quickAddCallback = {$options['quickAddCallback']};";
        $rv[] = "function $name() {";
        $rv[] = "\t var $name = $('#$name')";
        $rv[] = "\t\t\t.bcalendar(op).BcalGetOp();";
        $rv[] = "}";
        $rv[] = '</script>';

        return implode("\n", $rv);
    }

    /**
     * Init view helper function
     * @param string $name
     * @param App_Fullcalendar $fc
     * @param array $attributes
     * @return string
     */
    public function jqCalendar($name, App_JqCalendar $calendar, $options, $attributes = array())
    {
        $functionName = $name;
        $jsonOptions = $calendar->options->getConfig();
        $canAdd = $calendar->canAdd();
        $function = $this->createFunction($functionName, $jsonOptions, $options, $canAdd);
        $this->view->JQuery()->addOnLoad("$functionName();");
        
        $attributes['id'] = $functionName;
        $attributes['style'] = "overflow-y: visible; border: 1px solid #ccc";

        $container = sprintf(
                $this->_container,
                $this->_parseAttributes($attributes));

        return $function . $container;
    }
}
?>
