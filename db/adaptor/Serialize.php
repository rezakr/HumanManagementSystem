<?php
namespace db\adaptor;
require_once 'Adaptor.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Serialize
 * Running with serialize/unserialize.
 *
 * @author reza
 */
class Serialize extends Adaptor{
    protected function _encode($array) {
            return serialize($array);
    }

    protected function _decode($array) {
        return unserialize($array);
    }

    
}

?>
