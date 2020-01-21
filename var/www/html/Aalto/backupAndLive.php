<?php
/**
 * Created by PhpStorm.
 * User: Akhil M
 * Date: 12/16/2015
 * Time: 12:13 PM
 */
error_reporting(E_ALL);
ini_set("display_errors",1);
set_time_limit(0);
$folders = array("application/models", "application/views", "application/controllers", "application/helpers", "application/libraries", "assets/js", "assets/css");

//$folders = array("controller", "login", "modalviews");
echo "<pre>";
$feature = '';

if (isset($_GET['feature']))
{
    $feature = $_GET['feature'];
}

if (isset($_GET['backup']))
{
    take_backup($feature);
}

if (isset($_GET['view']))
{
    view_backups();
}

if (isset($_GET['live']))
{
    makeLiveFromDemo();
}

if (isset($_GET['refreshDemo']))
{
    refreshDemo();
}

function makeLiveFromDemo()
{
    $baseFolder = "/var/www/html/demo";

    if (!file_exists($baseFolder))
    {
        echo "backup does not exist <br />";
        return;
    }

    $folders = $GLOBALS["folders"];// = array("/application/models", "/application/views", "/application/controllers", "/js", "/style");

    foreach ($folders as $folder)
    {
        $source = "$baseFolder/$folder/*";
        $destination = "/var/www/html/bookeventz.com/$folder/";
        $backupCommand = "nohup cp -rf $source $destination &";
        echo $backupCommand . "<br /><br />";
        $command_out = shell_exec($backupCommand);
        echo $command_out;
    }

//    $createHomePagesCommand = 'nohup /usr/bin/wget "http://www.bookeventz.com/home/createStaticPagesAllCities" out.html &';
//    echo $createHomePagesCommand;
//    $command_out = shell_exec($createHomePagesCommand);
//    echo $command_out;
}


function refreshDemo()
{
    $baseFolder = "/var/www/html/bookeventz.com";

    if (!file_exists($baseFolder))
    {
        echo "backup does not exist <br />";
        return;
    }

    $folders = $GLOBALS["folders"];// = array("/application/models", "/application/views", "/application/controllers", "/js", "/style");

    foreach ($folders as $folder)
    {
        $source = "$baseFolder/$folder/*";
        $destination = "/var/www/html/demo/$folder/";
        $backupCommand = "nohup cp -rf $source $destination >> out.txt &";
        echo $backupCommand . "<br /><br />";
        $command_out = shell_exec($backupCommand);
        echo $command_out;
    }
}

function take_backup($feature)
{
    //$baseFolder = "/var/www/html/bookeventz.com";
    $baseFolder = "/var/www/html/Aalto";
    //$destinationBase = "/home/ubuntu/bookeventz_backups/$feature" . date("Y-m-d_H:i:s");
    $destinationBase = "/home/avanish/backups/$feature" . date("Y-m-d_H:i:s");
    $mkdir = "mkdir -p $destinationBase/application/";

    echo $mkdir . "<br /><br />";
    $command_out = shell_exec($mkdir);
    echo $command_out;


    $folders = $GLOBALS["folders"];

    foreach ($folders as $folder)
    {
        $source = "$baseFolder/$folder/*";
        $destination = "$destinationBase/$folder/";

        $mkdir = "mkdir $destination";
        echo $mkdir . "<br /><br />";
        $command_out = shell_exec($mkdir);

        $backupCommand = "nohup cp -a $source $destination &";
        echo $backupCommand . "<br /><br />";
        $command_out = shell_exec($backupCommand);
        echo $command_out;
    }
}

function view_backups()
{
    $viewFilesCommand = 'ls -utl /home/ubuntu/bookeventz_backups';
    $command_out = shell_exec($viewFilesCommand);
    echo $command_out;
}

?>
