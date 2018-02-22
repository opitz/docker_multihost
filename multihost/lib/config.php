<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

if (php_sapi_name() === 'cli') {
    $server_name = gethostname();
    $CFG->wwwroot = $server_name;
} else {
    $scheme = 'https';
    $server_name = filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL);
    $CFG->wwwroot   = $scheme . '://' . $server_name ;

    if (gethostname() !== 'multihost_centos7_php7_httpd') {
        $CFG->wwwroot   = $scheme . '://' . $server_name .':8443';
    }

    if($_SERVER['DOCUMENT_ROOT'] == '/var/www/html'){
        $subfolder = explode('/', $_SERVER['SCRIPT_FILENAME'])[4];
        $CFG->wwwroot .= '/' . $subfolder;
    } else {
        $subfolder = null;
    }
}
$db_name = 'mo_qmplus34_180206';
$moodledata_folder = (isset($subfolder)) ? $subfolder : $server_name;

$CFG->dataroot  = '/var/moodledata/'.$moodledata_folder;
$CFG->admin     = 'admin';
$CFG->debug = 32767;
$CFG->debugdisplay = 1;
$CFG->directorypermissions = 0777;

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'db_host';
$CFG->dbname    = $db_name;
$CFG->dbuser    = 'moodle_user';
$CFG->dbpass    = 'moodle';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array(
    'dbpersist' => 0,
    'dbport' => '',
    'dbsocket' => '',
    'dbcollation' => 'utf8mb4_unicode_ci',
);


// MIS common configuration
$CFG->mis_host   = 'db_host';
$CFG->mis_dbase  = 'qmu_mis';
$CFG->mis_dbtype = 'mysqli';
$CFG->mis_user   = 'moodle_user';
$CFG->mis_pass   = 'moodle';

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
