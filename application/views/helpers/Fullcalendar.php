<?php
/**
 * FullCalendar Jquery Plugin view helper
 *
 * @author Edder Rojas Douglas <edder.rojas@gmail.com>
 */
class My_View_Helper_Fullcalendar
    extends App_View_Helper_Abstract
{
    protected $divContainer = '<div id="%s" %s></div>';
    protected $fcFunction = '<script type="text/javascript">
                                    function %s(){
                                        var date = new Date();
                                        var d = date.getDate();
                                        var m = date.getMonth();
                                        var y = date.getFullYear();
                                        calendarOptions = %s;
                                        console.debug(calendarOptions);
                                        calendarOptions.select = function(start, end, allDay, view) {
                                            if (confirm(
                                                    "clear the selection?\n" +
                                                    "---- selection ----\n" +
                                                    "start: " + start + "\n" +
                                                    "end: " + end + "\n" +
                                                    "allDay: " + allDay))
                                            {
                                                $("#%s").fullCalendar("unselect");
                                            }
                                        }
                                        $("#%s").fullCalendar(calendarOptions);
                                    }
                            </script>';

    /**
     * Init view helper function
     * @param string $name
     * @param App_Fullcalendar $fc
     * @param array $attributes
     * @return string
     */
    public function fullcalendar($name, App_Fullcalendar $fc, $attributes = array())
    {
        $attributes = array("style" => "width: 90%; margin: 0 auto;");
        $functionName = "fullCalendar". ucfirst($name);
        $fcFunction = sprintf(
                $this->fcFunction,
                $functionName,
                $fc->options->getConfig(),
                $functionName,
                $functionName
        );
        $container = sprintf($this->divContainer, $functionName, $this->_parseAttributes($attributes));
        $this->view->JQuery()->addOnLoad("$functionName();");

        return $fcFunction . $container;
    }
}
?>
