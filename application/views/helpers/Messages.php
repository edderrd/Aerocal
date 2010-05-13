<?php
/**
 * Display messages passed from the controller into the view
 * @author Edder Rojas <edder.rojas@gmail.com>
 */
class My_View_Helper_Messages extends App_View_Helper_Abstract
{
    protected $ulTag = "<ul>%s</ul>";
    protected $liTag = "<li>%s</li>";

    /**
     * Creates a ul -> li message list
     * @param array $messages
     * @return string
     */
    protected function _createMessages($messages)
    {
        $rv = "";

        if (!empty($messages))
            {foreach($messages as $message)
            {
                $rv .= sprintf($this->liTag, $message);
            }

            return sprintf($this->ulTag, $rv);
        }
        return "";
    }

    /**
     * Constructor method
     * @return string
     */
    public function messages()
    {
        $notification = new Zend_Session_Namespace("notification");
        if (!empty($notification->messages))
        {
            $messages = $notification->messages;
            $notification->messages = array();

            return $this->_createMessages($messages);
        }
        
        return "";
    }
}
?>
