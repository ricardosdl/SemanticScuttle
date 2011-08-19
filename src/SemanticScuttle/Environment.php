<?php
/**
 * SemanticScuttle - your social bookmark manager.
 *
 * PHP version 5.
 *
 * @category Bookmarking
 * @package  SemanticScuttle
 * @author   Christian Weiske <cweiske@cweiske.de>
 * @license  AGPL http://www.gnu.org/licenses/agpl.html
 * @link     http://sourceforge.net/projects/semanticscuttle
 */

/**
 * Server environment handling methods
 *
 * @category Bookmarking
 * @package  SemanticScuttle
 * @author   Christian Weiske <cweiske@cweiske.de>
 * @license  AGPL http://www.gnu.org/licenses/agpl.html
 * @link     http://sourceforge.net/projects/semanticscuttle
 */
class SemanticScuttle_Environment
{
    /**
     * Determines the correct $_SERVER['PATH_INFO'] value
     *
     * @return string New value
     */
    public static function getServerPathInfo()
    {
        if (isset($_SERVER['PHAR_PATH_TRANSLATED'])) {
            $fscript = '/' . $_SERVER['SCRIPT_NAME'];
            if ($fscript == $_SERVER['PATH_INFO']) {
                return null;
            } else if (substr($_SERVER['PATH_INFO'], 0, strlen($fscript)) == $fscript) {
                return substr($_SERVER['PATH_INFO'], strlen($fscript));
            }
        }
        /* old code that does not work today.
           if you find that this code helps you, tell us
           and send us the output of var_export($_SERVER);
        // Correct bugs with PATH_INFO (maybe for Apache 1 or CGI) -- for 1&1 host...
        if (isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO'])) {
            if (strlen($_SERVER["PATH_INFO"])<strlen($_SERVER["ORIG_PATH_INFO"])) {
                $_SERVER["PATH_INFO"] = $_SERVER["ORIG_PATH_INFO"];
            }
            if (strcasecmp($_SERVER["PATH_INFO"], $_SERVER["SCRIPT_NAME"]) == 0) {
                unset($_SERVER["PATH_INFO"]);
            }
            if (strpos($_SERVER["PATH_INFO"], '.php') !== false) {
                unset($_SERVER["PATH_INFO"]);
            }
        }
        */

        if (isset($_SERVER['PATH_INFO'])) {
            return $_SERVER['PATH_INFO'];
        }

        return null;
    }


    /**
     * Determines the root directory from the server environment.
     * The root directory is the path that needs to be prepended
     * to relative links.
     *
     * Returns $GLOBALS['root'] if set.
     *
     * @return string Base URL with trailing slash
     */
    public static function getRoot()
    {
        if (isset($GLOBALS['root'])) {
            return $GLOBALS['root'];
        }

        $rootTmp = '/';
        if (isset($_SERVER['PHAR_PATH_TRANSLATED'])) {
            $rootTmp = $_SERVER['SCRIPT_NAME'] . '/';
            $_SERVER['SCRIPT_NAME'] = substr(
                $_SERVER['PATH_TRANSLATED'],
                strpos($_SERVER['PATH_TRANSLATED'], $rootTmp)
                + strlen($rootTmp)
                + 4 /* strip "www/" */
            );
        }

        $pieces = explode('/', $_SERVER['SCRIPT_NAME']);
        foreach ($pieces as $piece) {
            //we eliminate possible sscuttle subfolders (like gsearch for example)
            if ($piece != '' && !strstr($piece, '.php')
                && $piece != 'gsearch' && $piece != 'ajax'
            ) {
                $rootTmp .= $piece .'/';
            }
        }
        if (($rootTmp != '/') && (substr($rootTmp, -1, 1) != '/')) {
            $rootTmp .= '/';
        }

        //we do not prepend http since we also want to support https connections
        // "http" is not required; it's automatically determined by the browser
        // depending on the current connection.
        return '//' . $_SERVER['HTTP_HOST'] . $rootTmp;
    }
}
?>