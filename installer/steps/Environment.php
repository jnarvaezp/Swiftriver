<?php
class Environment implements IInstallStep
{
    private $checks = array();

    public function GetName()
    {
        return "Server checks";
    }

    public function GetDescription()
    {
        return "In this step we will ensure that you have the required PHP version " .
               "running on your server and that the PHP plugins needed are installed.";
    }

    public function RunChecks($postVar)
    {
        //Check the PHP Version
        $versionIsOk = (version_compare(PHP_VERSION, '5.3.0', '>='));
        $versionCheck->name = "PHP Version Check";
        $versionCheck->result = $versionIsOk;
        $versionCheck->text = $versionIsOk
                ? "The PHP version you're running is fine!"
                : "Sorry, the version of PHP you are running doesn't meet the ".
                  "minimum requirement of version 5.3.0";
        $this->checks[] = $versionCheck;

        //Check that PEAR is installed
        $pearIsThere = include_once("PEAR.php");
        $pearCheck->name = "PHP PEAR";
        $pearCheck->result = $pearIsThere;
        $pearCheck->text = $pearIsThere
                ? "PHP Pear is installed!"
                : "Sorry, the PEAR framework isn't accessible. We tried 'include_once(\"PEAR.php\")' ".
                  "but it returned false. Please ensure that PEAR is installed.";
        $this->checks[] = $pearCheck;

        //Check that the pear log package is installed
        $logIsThere = include_once("Log.php");
        $logCheck->name = "PHP PEAR Logging";
        $logCheck->result = $logIsThere;
        $logCheck->text = $logIsThere
                ? "The PHP PEAR Logging system is installed!"
                : "Sorry, we can't find the PEAR Logging framework. We tried ".
                  "'include_once(\"Log.php\")' but it returned false. Please ".
                  "ensure that the PEAR Logging system is installed.";
        $this->checks[] = $logCheck;

        //Check that all the steps passed and if not then return false
        foreach($this->checks as $check)
            if(!$check->result)
                return false;

        //If all the steps passed then return true
        return true;
    }

    public function Render()
    {
        $return = "";
        $return .= "<div class='step-render'><ul>";

        foreach($this->checks as $check)
        {
            $return .= "<li class='" . ($check->result ? "pass" : "fail") . "'>";
            $return .= "<p class='name'>" . $check->name . "</p>";
            $return .= "<p class='result'>" . $check->text . "</p>";
            $return .= "</li>";
        }

        $return .= "</div>";
        return $return;
    }
}
?>