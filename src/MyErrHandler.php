<?php
/******************************************************************************
 * This file is part of the MyErrHandler package. You can always find the latest
 * version of this project at: https://github.com/ionux/custom-php-error-handler
 *
 * @license Copyright 2014-2017 Rich Morgan <rich@richmorgan.me>, MIT License 
 * see https://github.com/ionux/custom-php-error-handler/blob/master/LICENSE
 ******************************************************************************/


/**
 * This is a custom, flexible and configurable error handler useful for debugging.
 *
 * The below values are used to build up a bitmask that specifies which errors to
 * report. You can use the bitwise operators to combine these values or mask out
 * certain types of errors. Note that only '|', '~', '!', '^' and '&' will be
 * understood within php.ini.  You may use these constant names in php.ini but not
 * outside of PHP, like in httpd.conf, where you'd use the bitmask values instead.
 * Be careful with using the backtrace functionality because it can generate a lot
 * of output and, consequently, make your log files very large and difficult to read.
 *
 * @param  int     $errno           The level of the error raised.
 * @param  string  $errstr          The error message.
 * @param  string  $errfile         The filename that the error was raised in.
 * @param  int     $errline         The line number the error was raised at.
 * @param  array   $errcontext      An array of every variable that existed in the scope the error was triggered in.
 * @param  bool    $errbacktrace    Whether or not you want to log a backtrace of the calling functions.
 * @param  bool    $bypassinternal  Whether or not you want to bypass the internal PHP error handler.
 * @param  bool    $errsavefile     The filename where you'd like to save the error information to.
 *
 * @return bool                     True, bypass PHP errhandler. False, don't.
 */
function MyErrHandler($errno, $errstr, $errfile, $errline, $errcontext, $errbacktrace = false, $bypassinternal = true, $errsavefile = 'php_errors.log')
{
    // Return to PHP error handler if this error code
    // is not included in error_reporting bitmask.
    if (!(error_reporting() & $errno)) {
        return false;
    }

    // In case there's no timezone set or not present in php.ini
    // we will set this to UTC here and suppress any nagging.
    date_default_timezone_set(@date_default_timezone_get());

    $filename     = (!isset($errsavefile) || empty($errsavefile)) ? 'php_errors.log' : trim($errsavefile);
    $error_string = date("Y-m-d H:i:s");

    /**
     * The below values are used to build up a bitmask that specifies which errors to
     * report. You can use the bitwise operators to combine these values or mask out
     * certain types of errors. Note that only '|', '~', '!', '^' and '&' will be
     * understood within php.ini.  You may use these constant names in php.ini but not
     * outside of PHP, like in httpd.conf, where you'd use the bitmask values instead.
     */
    switch ($errno) {

         // Fatal run-time errors. These indicate errors that can not be recovered from,
         // such as a memory allocation problem. Execution of the script is halted.
        case E_ERROR:
            $error_string .= "** FATAL ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Error on line $errline in file $errfile";
            break;

        // Run-time warnings (non-fatal errors). Execution of the script is not halted.
        case E_WARNING:
            $error_string .= "** WARNING ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Warning on line " . $errline . " in file " . $errfile;
            break;

        // Compile-time parse errors. Parse errors should only be generated by the parser.
        case E_PARSE:
            $error_string .= "** PARSE ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Parse exception on line " . $errline . " in file " . $errfile;
            break;

        // Run-time notices. Indicate that the script encountered something that could
        // indicate an error, but could also happen in the normal course of running a script.
        case E_NOTICE:
            $error_string .= "** NOTICE ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Notice on line " . $errline . " in file " . $errfile;
            break;

        // Fatal errors that occur during PHP's initial startup. This is like an E_ERROR,
        // except it is generated by the core of PHP.
        case E_CORE_ERROR:
            $error_string .= "** CORE_ERROR ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Core error on line " . $errline . " in file " . $errfile;
            break;

        // Warnings (non-fatal errors) that occur during PHP's initial startup. This is like
        // an E_WARNING, except it is generated by the core of PHP.
        case E_CORE_WARNING:
            $error_string .= "** CORE_WARNING ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Core warning on line " . $errline . " in file " . $errfile;
            break;

        // Fatal compile-time errors. This is like an E_ERROR, except it is generated by
        // the Zend Scripting Engine.
        case E_COMPILE_ERROR:
            $error_string .= "** COMPILE_ERROR ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Compile error on line " . $errline . " in file " . $errfile;
            break;

        // Compile-time warnings (non-fatal errors). This is like an E_WARNING, except it
        // is generated by the Zend Scripting Engine.
        case E_COMPILE_WARNING:
            $error_string .= "** COMPILE_WARNING ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Compile warning on line " . $errline . " in file " . $errfile;
            break;

        // User-generated error message. This is like an E_ERROR, except it is generated in
        // PHP code by using the PHP function trigger_error().
        case E_USER_ERROR:
            $error_string .= "** USER_ERROR ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   User error on line " . $errline . " in file " . $errfile;
            break;

        // User-generated notice message. This is like an E_WARNING, except it is generated in
        // PHP code by using the PHP function trigger_error().
        case E_USER_WARNING:
            $error_string .= "** USER_WARNING ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   User warning on line " . $errline . " in file " . $errfile;
            break;

        // User-generated notice message. This is like an E_NOTICE, except it is generated in
        // PHP code by using the PHP function trigger_error().
        case E_USER_NOTICE:
            $error_string .= "** USER_NOTICE ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   User notice on line " . $errline . " in file " . $errfile;
            break;

        // Enable to have PHP suggest changes to your code which will ensure the best
        // interoperability and forward compatibility of your code.  NOTE: This option
        // is *not* included in E_ALL until PHP 5.4.
        case E_STRICT:
            $error_string .= "** STRICT ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Strict exception on line " . $errline . " in file " . $errfile;
            break;

        // Run-time notices. Enable this to receive warnings about code that will not
        // work in future versions of PHP.
        case E_DEPRICATED:
            $error_string .= "** DEPRICATED ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Depricated exception on line " . $errline . " in file " . $errfile;
            break;

        // User-generated warning message. This is like an E_DEPRECATED, except it is
        // generated in PHP code by using the PHP function trigger_error().
        case E_USER_DEPRICATED:
            $error_string .= "** USER_DEPRICATED ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   User depricated exception on line " . $errline . " in file " . $errfile;
            break;

        // Catchable fatal error. It indicates that a probably dangerous error occurred,
        // but did not leave the Engine in an unstable state. If the error is not caught
        // by a user defined handle (see also set_error_handler()), the application aborts
        // as it was an E_ERROR.
        case E_RECOVERABLE_ERROR:
            $error_string .= "** RECOVERABLE_ERROR ** [" . $errno . "] " . $errstr . "\r\n" .
                             "   Recoverable error on line " . $errline . " in file " . $errfile;
            break;

        // All errors and warnings, as supported, except of level E_STRICT prior to PHP 5.4.0.
        // This case also falls through to the default case for any errors not handled here.
        case E_ALL:
        default:
            $error_string .= "** Unknown error type: [" . $errno . "] " . $errstr . "\r\n" .
                             "   Unknown error on line " . $errline . " in file " . $errfile;
            break;
        }

        $error_string .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")\r\n" .
                         "The variable context was: " . var_export($errcontext, true) . "\r\n";

        if ($errbacktrace == true) {
            $error_string .= "   BACKTRACE: " . var_export(debug_backtrace(), true) . "\r\n";
        }

    @file_put_contents(date("Ymd") . '-' . $filename, $error_string, FILE_APPEND | LOCK_EX);

    $error_string = '';

    // These are serious errors so the script needs to
    // die() here.  Otherwise the function will return
    // execution back to the next statement after the
    // error-causing line of code.
    if ($errno == E_USER_ERROR    ||
        $errno == E_ERROR         ||   
        $errno == E_PARSE         ||
        $errno == E_CORE_ERROR    ||
        $errno == E_COMPILE_ERROR)
    {
        exit(1);
    }

    // Don't execute PHP internal error handler.
    if ($bypassinternal == true) {
        return true;
    }

    // Otherwise, execute PHP internal error handler.
    return false;
}
