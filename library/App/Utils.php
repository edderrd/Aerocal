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
                $rv[$item[$index]] = $item[$value];
            }
        }
        return $rv;
    }
}
?>
