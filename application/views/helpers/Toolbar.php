<?php
/**
 * Button toolbar
 *
 * @author Edder
 */
class My_View_Helper_Toolbar
    extends App_View_Helper_Abstract
{
    /**
     * Toolbar object to create buttons
     * @var App_Toolbar
     */
    protected $_toolbar = null;
    /**
     * Initialize App_Toolbar object instance
     */
    public function __construct()
    {
        $registry = Zend_Registry::getInstance();

        if (!isset($registry["App_Toolbar"]))
        {
            $this->_toolbar = App_Toolbar::getInstance();
            
        }
        $this->_toolbar = $registry["App_Toolbar"];
    }

    public function toolbar($toolbarId = "toolbar")
    {
        $this->_toolbar->setId($toolbarId);
        return $this;
    }

    /**
     * Add onLoad jquery function
     * @return My_View_Helper_Toolbar
     */
    public function addOnLoad()
    {
        $containerId = $this->_toolbar->getId();
        $this->view->JQuery()->addOnLoad("createToolbar(\"{$containerId}\");");

        return $this;
    }

    /**
     * Creates button toolbar
     * @return string
     */
    public function render()
    {
        $rv = array();
        $containerId = $this->_toolbar->getId();

        foreach($this->_toolbar->getButtons() as $button)
        {
            $attributes = $this->_parseAttributes($button['attributes']);
            $type = $this->_toolbar->isButtonset() ? "radio" : "button";
            if($this->_toolbar->isButtonset())
            {
                $rv[] = "<input 
                         type=\"$type\"
                         id=\"{$button["id"]}\"
                         name=\"$containerId\"
                         $attributes/>";
                $rv[] = "<label for=\"{$button["id"]}\">{$button['value']}</label>";
            }
            else
            {
                $rv[] = "<button
                         id=\"{$button["id"]}\"
                         name=\"$containerId\"
                         $attributes>{$button['value']}</button>";
            }
        }
        $buttons = implode("\n", $rv);
        $container = "";
        if (!empty($buttons))
        {
            $container = "<div id=\"$containerId\">$buttons</div>";
        }
        return $container;
    }

    /**
     * Add button to the App_Toolbar
     *
     * @param string $id
     * @param string $name
     * @param array $attributes
     * @return My_View_Helper_Toolbar
     */
    public function addButton($id, $name, $attributes = array())
    {
        $this->_toolbar->addButton($id, $name, $attributes);

        return $this;
    }

    /**
     * Enable a button set
     * @param bool $option
     * @return My_View_Helper_Toolbar
     */
    public function setButtonset($option)
    {
        $this->_toolbar->setButtonset($option);

        return $this;
    }

}
?>
