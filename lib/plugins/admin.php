<?php
/**
 * Admin Plugin Prototype
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Christopher Smith <chris@jalakai.co.uk>
 * @author     Ed Pate <dokuwikid@jaxcon.net>
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the admin function
 * need to inherit from this class
 */
class DokuWiki_Admin_Plugin extends DokuWiki_Plugin {

    function getMenuText($language) {
        $menutext = $this->getLang('menu');
        if (!$menutext) {
            $info = $this->getInfo();
            $menutext = $info['name'].' ...';
        }
        return $menutext;
    }

    function getMenuSort() {
        return 1000;
    }

    function handle() {
        trigger_error('handle() not implemented in '.get_class($this), E_USER_WARNING);
    }

    function html() {
        trigger_error('html() not implemented in '.get_class($this), E_USER_WARNING);
    }

// *Begin* ACLMOD v1.1 changes
    function forAdminOnly() {
        if ($INFO['nsperm'] >= AUTH_ACLMOD) {
            return false;
        } else {
            return true;
        }
    }
// **End** ACLMOD v1.1 changes

    function getTOC(){
        return array();
    }
}
//Setup VIM: ex: et ts=4 enc=utf-8 :

