<?php

Class FcPDOId{
    /**
     * @param $database
     * @return mixed
     * @throws BadMethodCallException
     */
    public static function get($database){
        if(method_exists(get_class(), $database))
            return call_user_func("FcPDOId::".$database);
        throw new BadMethodCallException();
    }

    /**
      ADD HERE PRIVATE STATIC METHOD FOR EACH OF YOUR DATABASE
      THESE METHODS CALL LIKE YOUR NAMES OF DATABASES RETURN THEIR CONNEXION ID
     **/

    /**
     * @return array
     */
    private static function database_test(){
        return array("database"=>"database_test","host"=>"localhost","user"=>"root","pass"=>"root");
    }
}
