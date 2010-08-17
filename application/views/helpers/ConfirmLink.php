<?php
class My_View_Helper_ConfirmLink extends App_View_Helper_Abstract
{

    public function confirmLink($url, $content, $message, $attributes = array())
    {
        //TODO: Add translation
        $attributes['onclick'] = "return confirm('$message');";
        $attributes = $this->_parseAttributes($attributes);
        return "<a href=\"$url\" $attributes>$content</a>";
    }
}
?>
