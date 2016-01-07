<?php
/**
 * Installation File.
 * @version 1.0
 * @author Elias Cédric Laouiti
 * @author Maxime Florile
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 *
 */

$neededDatas = array(
    "MySQL" => array(
        "dbHost"        => null,
        "dbName"        => null,
        "dbUser"        => null,
        "dbPassword"    => null
    ),
    "SMTP" => array(
        "smtpFrom"      => null,
        "smtpFromName"  => null,
        "smtpHost"      => null,
        "smtpUser"      => null,
        "smtpPass"      => null,
        "smtpPort"      => null,
        "smtpType"      => null,
    )
);

$iniFilePath = dirname(__FILE__)."/../conf.ini";

if(!isCommandLineInterface()){
    echo "Please, use the cli console to install this API.<br/>";
    echo "If you can't use the cli, edit manualy '/conf.ini'";
    exit(-1);
}

echo "|*******************************************************| \n";
echo "|**   Welcome to the installation of OpenBooking API  **| \n";
echo "|**   We will configure the database access and the   **| \n";
echo "|**               email smtp server...                **| \n";
echo "|*******************************************************| \n";

manualInstallation();


/**
 * Ask where is wp-config.php file
 * @return string
 */
function askWPConfigDir(){
    echo "\nCan you tell me where i can find your wp-config file : ";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    return $line;
}

/**
 * Ask if we want to retry to search wp-config.php
 * @param $line
 * @return string
 */
function retryLocateWPConfig($line){
    echo "\nI can't find the wp-config.php file in '". trim($line)."' ";
    echo "\nDo you want to retry ? (y/n) ";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    return trim($line);
}

/**
 * Configure conf.ini with wp-config.php content
 */
function WPInstallation(){
    global $neededDatas;
    $wpConfigDir = null;
    $wpConfigContent = null;
    while($wpConfigDir == null){
        $line = askWPConfigDir();
        if(file_exists(dirname(__FILE__)."/".trim($line))){
            $wpConfigDir = trim($line);
            break;
        } else {
            $line = retryLocateWPConfig($line);
            if($line == 'y') {
                continue;
            } else {
                exit("Cancel by user");
            }
        }
    }
    require_once dirname(__FILE__)."/".$wpConfigDir;

    $tmp = array(
        "DB_HOST"               => array("key" => "MySQL",  "value" => "dbHost"),
        "DB_NAME"               => array("key" => "MySQL",  "value" => "dbName"),
        "DB_USER"               => array("key" => "MySQL",  "value" => "dbUser"),
        "DB_PASSWORD"           => array("key" => "MySQL",  "value" => "dbPassword"),
        "WP_SMTP_FROM"          => array("key" => "SMTP",   "value" => "smtpFrom"),
        "WP_SMTP_FROM_NAME"     => array("key" => "SMTP",   "value" => "smtpFromName"),
        "WP_SMTP_HOST"          => array("key" => "SMTP",   "value" => "smtpHost"),
        "WP_SMTP_USER"          => array("key" => "SMTP",   "value" => "smtpUser"),
        "WP_SMTP_PASSWORD"      => array("key" => "SMTP",   "value" => "smtpPass"),
        "WP_SMTP_PORT"          => array("key" => "SMTP",   "value" => "smtpPort"),
        "WP_SMTP_ENCRYPTION"    => array("key" => "SMTP",   "value" => "smtpType")
    );

    foreach($tmp AS $const => $key){
        if(defined($const)){
            $neededDatas[$key['key']][$key['value']] = constant($const);
        } else {
            echo "\e[1;31m\nI can't find the constant ".$const." in your wp-config file.";
            echo "\nWhat is your ".$const." ?\n\e[0m";
            $handle = fopen ("php://stdin","r");
            $line = fgets($handle);
            $neededDatas[$key['key']][$key['value']] = trim($line);
        }
    }
    if(createDatabaseContent()){
        write_ini_file($neededDatas);
    }
}

/**
 * Manually configure the conf.ini file
 */
function manualInstallation(){
    global $neededDatas;
    $tmp = array(
        "database host"                                 => array("key" => "MySQL",  "value" => "dbHost"),
        "database name"                                 => array("key" => "MySQL",  "value" => "dbName"),
        "database user"                                 => array("key" => "MySQL",  "value" => "dbUser"),
        "database password"                             => array("key" => "MySQL",  "value" => "dbPassword"),
        "SMTP sender email"                             => array("key" => "SMTP",   "value" => "smtpFrom"),
        "SMTP sender name"                              => array("key" => "SMTP",   "value" => "smtpFromName"),
        "SMTP host"                                     => array("key" => "SMTP",   "value" => "smtpHost"),
        "SMTP user"                                     => array("key" => "SMTP",   "value" => "smtpUser"),
        "SMTP password"                                 => array("key" => "SMTP",   "value" => "smtpPass"),
        "SMTP port"                                     => array("key" => "SMTP",   "value" => "smtpPort"),
        "SMTP encrypton mode (default no encryption)"   => array("key" => "SMTP",   "value" => "smtpType")
    );
    foreach($tmp AS $cat => $key){

        echo "\nWhat is your ".$cat." ?\n";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        $neededDatas[$key['key']][$key['value']] = trim($line);
    }
    if(createDatabaseContent()){
        write_ini_file($neededDatas);
    }
}

/**
 * Create the db schema
 * Remove comments and database creation on install.sql file...
 */
function createDatabaseContent(){
    global $neededDatas;
    $req='';
    $req = file_get_contents (dirname(__FILE__)."/install.sql");
    $req = str_replace("\n"," ",$req);
    $req = str_replace("\r"," ",$req);

    $dsn = 'mysql:dbname='.$neededDatas['MySQL']['dbName'].';host='.$neededDatas['MySQL']['dbHost'];
    $user = $neededDatas['MySQL']['dbUser'];
    $password = $neededDatas['MySQL']['dbPassword'];
    try{
        $pdo = new PDO($dsn, $user, $password);
        $pdo->query($req);
        return true;
    } catch (Exception $e){
        echo "\nI can't connect to SQL database. Please check your credentials";
        exit(-1);
    }
}

/**
 * Write the conf.ini file
 * @param $assoc_arr
 * @return bool
 */
function write_ini_file($assoc_arr) {
    global $iniFilePath;
    $content = "";
    foreach ($assoc_arr as $key=>$elem) {
        $content .= "[".$key."]\n";
        foreach ($elem as $key2=>$elem2) {
            if(is_array($elem2))
            {
                for($i=0;$i<count($elem2);$i++)
                {
                    $content .= $key2."[] = \"".$elem2[$i]."\"\n";
                }
            }
            else if($elem2=="") $content .= $key2." = \n";
            else $content .= $key2." = \"".$elem2."\"\n";
        }
    }

    if ($handle = fopen($iniFilePath, 'w')) {
        $success = fwrite($handle, $content);
        fclose($handle);
        if($success){
            echo "\e[1;32m\nInstallation is complete. Enjoy! \n\e[0m";
            return true;
        }
    }
    echo "\e[1;31m\nI can't write conf.ini file. Can you paste this into conf.ini file :\n\e[0m";
    echo $content;
    return false;
}

/**
 * Test if is CLI or browser
 * @return bool
 */
function isCommandLineInterface()
{
    return (php_sapi_name() === 'cli');
}
