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

    public function toolbar()
    {
        return $this;
    }

    /**
     * Creates button toolbar
     * @return string
     */
    public function render()
    {
        $rv = array();

        foreach($this->_toolbar->getButtons() as $button)
        {
            $attributes = $this->_parseAttributes($button['attributes']);
            $rv[] = "<button id='{$button["id"]}' name='{$button['id']}' $attributes>{$button['value']}</button>";
        }
        return implode("\n", $rv);
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

}
?>
