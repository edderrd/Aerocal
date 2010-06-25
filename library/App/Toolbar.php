<?php
/**
 * Provide a common way to create toolbar buttons
 *
 * @author Edder
 */
class App_Toolbar {
    
    private static $_buttons = array();
    /**
     * Application instance (Singleton pattern)
     * @var App_Toolbar
     */
    private static $_instance = null;
    /**
     * @var Bool
     */
    protected static $_buttonSet = true;
    /**
     * @var string
     */
    protected static $_id = "";

    /**
     * Creates a new instance of the same class but also set a registry for them
     */
    protected function __construct()
    {
        // nothing
    }

    /**
     * Return current instace (Singleton pattern)
     * @return App_Toolbar
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance))
        {
            $class = __CLASS__;
            self::$_instance = new $class();
            $registry = Zend_Registry::getInstance();
            $registry[__CLASS__] = self::$_instance;
        }
        return self::$_instance;
    }

    /**
     * Define a instance and Registry instance
     * @param App_Toolbar $instance
     * @return App_Toolbar
     */
    public static function setInstance(App_Toolbar $instance)
    {
        self::$_instance = $instance;
        $registry = Zend_Registry::get(__CLASS__);
        $registry[__CLASS__] = self::$_instance;

        return self::$_instance;
    }

    /**
     * Add a button to the stack
     * @param string $id
     * @param array $attributes
     */
    public function addButton($id, $value, $attributes)
    {
        if (empty($id) and empty($executeFunction) and empty($value))
            throw new Exception("To add a button you need a least a id and a function");

        self::$_buttons[$id] = array(
            'id'        => $id,
            'value'     => $value,
            'attributes'  => $attributes
        );

        return self::$_instance;
    }

    /**
     * Return all buttons defined
     * @return array
     */
    public function getButtons()
    {
        if (!empty(self::$_buttons))
            return self::$_buttons;

        return array();
    }

    /**
     * Enable buttonset
     * @param bool $option
     */
    public function setButtonset($option)
    {
        self::$_buttonSet = $option;
    }

    /**
     * @return bool
     */
    public function isButtonSet()
    {
        return self::$_buttonSet;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        self::$_id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return self::$_id;
    }
}
?>
