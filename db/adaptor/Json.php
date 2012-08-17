<?php
namespace db\adaptor;
require_once 'Adaptor.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Json
 * Running with JSON_ENCODE/DECODE.
 *
 * @author reza
 */
class Json extends Adaptor{
    protected function _encode($array) {
            return json_encode($array);
    }

    protected function _decode($array) {
        return json_decode($array,true);
    }

    
}

?>
