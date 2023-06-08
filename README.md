Customizable PHP Error Handler (custom-php-error-handler)
========================
<strong>Copyright (c) 2014-2023 Rich Morgan, rich@richmorgan.me</strong>


Description
------------
This is a custom, flexible and configurable error handler useful for debugging.  It writes the specified level of error messages to a log file and can be set to either bypass or not bypass the internal PHP error handler.  Also can log backtraces of the function calls and the complete context of variables present at the time of the error.


Installation
------------
Include this error handling script at the top of your own PHP scripts. For example:
```php
include_once('MyErrHandler.php');
```


Usage
-----
1. Set the error reporting to the level you want to capture.
2. Set the error handler to this custom function.
3. The filename parameter is optional and defaults to "php_errors.log" in the same directory.

For example, if you want to capture all errors, notices, warnings:
```php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
set_error_handler('MyErrHandler', E_ALL);
```


License
------
Released under the MIT License.

<pre>
The MIT License (MIT)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
</pre>


Contributing
------------
All code contributions, bugfixes, enhancements, etc are welcome!  Feel free to fork this project and submit pull requests.


Version History
---------------
<strong>Version 1.0.0:</strong><br />-Initial release 12/6/2014
