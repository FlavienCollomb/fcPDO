<?php
/*Copyright (C) 2014 Flavien Collomb Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions: The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.*/

class FcPDOController {
    /**
     * Singleton FcPDOController
     * @var array
     * @access private
     * @static
     */
    private static $_pdo=array();
    /**
     * @param string $database
     * @param int $persistent
     * @return FcPDO
     * @throws PDOException
     * @throws BadMethodCallException
     */
    public static function get($database,$persistent=false) {
        try{
            if(!isset(self::$_pdo[$database][$persistent]))
                self::$_pdo[$database][$persistent]=new FcPDO(FcPDOId::get($database),$persistent);
            return self::$_pdo[$database][$persistent];
        }
        catch(BadMethodCallException $e){throw new BadMethodCallException($e);}
        catch(PDOException $e){throw new PDOException($e);}
    }
}
