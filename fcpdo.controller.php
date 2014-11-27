<?php

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
