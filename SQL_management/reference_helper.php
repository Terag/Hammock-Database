<?php
/**
 * This file provides a function to permit to change certain part of a string with defined references
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category modules
 * @package SQL
 * @namespace SQL
 * @filesource
 */

/**
 * Replace defined structure in str par defined references
 *
 * Param1 : $ref_structure
 * `array(
 *      '<REF>' => array('VALUE' => <value>, 'N' => <reference|number>, 'array_name' => <array>)
 * );`
 *
 * Param2 : $ref_data
 * `array(
 *      '<data_name>' => array( 'array' => <array|string>, 'separator' => <separator>)
 * );`
 *
 * @param string $str
 * @param array $ref_structure
 * @param array $ref_data
 * @return mixed string
 * @throws Exception type of array is string and separator not set
 *
 * @example SQL_management/examples.php 119 24  "Reference Helper example of use"
 * @tags SQL References
 * @source SQL_management\reference_helper.php
 */
function str_link_references($str, array &$ref_structure, array &$ref_data) {

    foreach($ref_data as &$array) {

        if(is_string($array['array'])) {

            if(isset($array['separator'])) {
                $array['array'] = explode($array['separator'], $array['array']);
            } else {
                throw new Exception('type of array is string and separator not set');
            }
        }
    }

    // Use this string and print_r($matches) to understand usage : '0802-%DOC%-TEST-%H%-%E[1]%'
    preg_match_all('/%(?<REF>[^%\[\]]+)(\[(?<N>\d)\])?%/',$str, $matches);

    for($i=0; $i<sizeof($matches[0]); $i++) {

        $REF = $matches['REF'][$i];
        if(isset($ref_structure[$REF])) {
            $STRUCT = $ref_structure[$REF];
        } else {
            $STRUCT = NULL;
        }

        if(isset($STRUCT['VALUE'])) {
            $str = str_replace('%'.$REF.'%','<b>'.$STRUCT['VALUE'].'</b>', $str);
        }
        elseif (isset($STRUCT)) {

            $N = $STRUCT['N'];
            if($N == 'number') {
                $N = $matches['N'][$i];
            }

            if(isset($N) AND isset($ref_data[$STRUCT['array_name']]['array'][$N])) {
                $str = str_replace('%'.$REF.'['.$N.']%','<b>'.$ref_data[$STRUCT['array_name']]['array'][$N].'</b>', $str);
            } else {
                $str = preg_replace('/%'.$REF.'\[\d\]%/','<b>!ERROR!</b>', $str);
            }
        }
        else {
            $str = str_replace('%'.$REF.'%','<b>!ERROR!</b>', $str);
        }
    }

    return $str;
}