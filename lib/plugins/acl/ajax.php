<?php
/**
 * AJAX call handler for ACL plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 * @author     Ed Pate <dokuwikid@jaxcon.net>
 */

//fix for Opera XMLHttpRequests
if(!count($_POST) && $HTTP_RAW_POST_DATA){
  parse_str($HTTP_RAW_POST_DATA, $_POST);
}

if(!defined('DOKU_INC')) define('DOKU_INC',dirname(__FILE__).'/../../../');
require_once(DOKU_INC.'inc/init.php');
//close session
session_write_close();

// *Begin* ACLMOD v1.1 changes
if
    (
        !$INFO['isadmin']
        &&
        (
            ($_REQUEST['id'] != '')
            &&
            (
                (auth_quicknsaclcheck($_REQUEST['id']) < AUTH_ACLMOD )
                ||
                (auth_quicknsaclcheck($_REQUEST['id'].':') <AUTH_ACLMOD )
            )
        )
        &&
        (
            ( $_REQUEST['ns'] != '')
            &&
            (
                (auth_quicknsaclcheck($_REQUEST['ns']) < AUTH_ACLMOD )
                ||
                (auth_quicknsaclcheck($_REQUEST['ns'].':') <AUTH_ACLMOD )
            )
        )
    ) die('for admins only--');
// **End** ACLMOD v1.1 changes
if(!checkSecurityToken()) die('CRSF Attack');

$ID    = getID();

$acl = plugin_load('admin','acl');
$acl->handle();

$ajax = $_REQUEST['ajax'];
header('Content-Type: text/html; charset=utf-8');

if($ajax == 'info'){
    $acl->_html_info();
}elseif($ajax == 'tree'){
    global $conf;
    global $ID;
// line added for ACLMOD v1.1
    global $INFO;

    $dir = $conf['datadir'];
    $ns  = $_REQUEST['ns'];
    if($ns == '*'){
        $ns ='';
    }
    $ns  = cleanID($ns);
    $lvl = count(explode(':',$ns));
    $ns  = utf8_encodeFN(str_replace(':','/',$ns));

    $data = $acl->_get_tree($ns,$ns);

// *Begin* ACLMOD v1.1 changes
    foreach($data as $item){
            f ( ( $item['type'] == 'd' && ( auth_quicknsaclcheck($item['id'].':') >= AUTH_ACLMOD ) ) || ( $item['type'] == 'f' && ( auth_quicknsaclcheck($item['id']) >= AUTH_A
CLMOD ) )  ) {
            $item['level'] = $lvl+1;
            echo $acl->_html_li_acl($item);
            echo '<div class="li">';
            echo $acl->_html_list_acl($item);
            echo '</div>';
            echo '</li>';
// **End** ACLMOD v1.1 changes
    }
}


