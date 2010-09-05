<?php
/**
 * Re-usable utilites
 *
 * @author Edder
 */
class App_Utils {

    /**
     * Create a list from array, index and value
     * @param array $data
     * @param mixed $index
     * @param mixed $value
     * @return array
     */
    public static function toList($data, $index, $value)
    {
        $rv = array();

        if (!empty($data))
        {
            foreach($data as $item)
            {
                if (is_array($value))
                {
                    foreach($value as $valueIndex => $subvalue)
                    {
                        $separator = $valueIndex == 0 ? "" : " ";
                        $rv[$item[$index]] = $separator . $item[$subvalue];
                    }
                }
                else 
                {
                    $rv[$item[$index]] = $item[$value];
                }
                if (is_string($rv[$item[$index]]))
                    $rv[$item[$index]] = ucfirst($rv[$item[$index]]);
            }
        }
        asort($rv);
        return $rv;
    }

    /**
     * Format a date based on application configuration date format
     * only support fullformat entry
     * @param string $date
     * @return string
     */
    public static function formatDate($date)
    {
        $dateFormat = Zend_Registry::get('date');

        if (!isset($dateFormat['fullformat']))
            $dateFormat['fullformat'] = "d-m-Y H:m:s";

        return date($dateFormat['fullformat'], strtotime($date));
    }
}
?>
