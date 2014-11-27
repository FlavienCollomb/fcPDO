<?php

require_once dirname(__FILE__).'/fcpdo.controller.php';
require_once dirname(__FILE__).'/fcpdo.id.php';

Class FcPDO{
    /**
     * Class FcPDO creates UTF8 PDO interface
     * @var PDO
     */
    private $_pdo;
    /**
     * @var bool
     */
    private $_persistent;
    /**
     * @var bool
     */
    private $_began;
    /**
     * @var array
     */
    private $_prepared;
    /**
     * @var string
     */
    private $_lastQuery;
    /**
     * @var array
     */
    private $_lastParam;
    /**
     * @param array $id
     * @param bool $persistent
     * @throws PDOException
     */
    public function __construct($id,$persistent){
        $this->_persistent=$persistent;
        $this->_began=false;
        $this->_prepared=array();
        try{
            $options = array();
            if ($this->_persistent)
                $options[PDO::ATTR_PERSISTENT] = true;

            $this->_pdo= new PDO('mysql:dbname='.$id['database'].';host='.$id['host'],$id['user'],$id['pass'],$options);
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_pdo->exec("SET CHARACTER SET utf8");
            $this->_pdo->exec("SET NAMES utf8");
        }
        catch(PDOException $e){throw new PDOException($e);}
    }
    /**
     * @param $query
     * @param array $param
     * @param null|int $fetchStyle
     * @return array
     * @throws PDOException
     */
    public function select($query,$param=array(),$fetchStyle=null){
        $this->currently($query,$param);
        try{
            $statement = $this->preparation($query);
            $statement->execute($param);
            return $statement->fetchAll($fetchStyle);
        }
        catch(PDOException $e){throw new PDOException($e);}
    }
    /**
     * @param $query
     * @param array $param
     * @param bool $last
     * @return int
     * @throws PDOException
     */
    public function exe($query,$param=array(),$last=false){
        $this->currently($query,$param);
        try{
            $statement = $this->preparation($query);
            if($statement->execute($param)){
                if($last)
                    return $this->_pdo->lastInsertId();
                else
                    return $statement->rowCount();
            }
        }
        catch(PDOException $e){throw new PDOException($e);}
    }
    /**
     * @return bool
     * @throws PDOException
     */
    public function begin(){
        try{
            if(!$this->_began && !$this->_persistent){
                $this->purge();
                $this->_began=true;
                $this->_pdo->beginTransaction();
                return true;
            }
            else
                return false;
        }
        catch(PDOException $e){throw new PDOException($e);}
    }
    /**
     * @return bool
     * @throws PDOException
     */
    public function commit(){
        if($this->_began && !$this->_persistent){
            try{
                $this->_began=false;
                $this->_pdo->commit();
                return true;
            }
            catch(PDOException $e){throw new PDOException($e);}
        }
        else
            return false;
    }
    /**
     * @return bool
     * @throws PDOException
     */
    public function rollback(){
        if($this->_began && !$this->_persistent){
            try{
                $this->_began=false;
                $this->_pdo->rollback();
            }
            catch(PDOException $e){throw new PDOException($e);}
        }
        else
            return false;
    }
    /**
     * @return bool
     */
    public function transaction(){
        return $this->_began;
    }
    /**
     * @return string
     */
    public function getLastQuery(){
        return $this->_lastQuery;
    }
    /**
     * @return array
     */
    public function getLastParam(){
        return $this->_lastParam;
    }
    /**/
    /**
     * @param string $query
     * @param array $param
     */
    private function currently($query,$param){
        $this->_lastQuery=$query;
        $this->_lastParam=$param;
    }
    /**
     * @param string $query
     * @return PDOStatement
     * @throws PDOException
     */
    private function preparation($query){
        if(count($this->_prepared) > 1000)
            $this->purge();

        try{
            if($this->_began)
                return $this->_pdo->prepare($query, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            else{
                if(!isset($this->_prepared[$query]))
                    $this->_prepared[$query]=$this->_pdo->prepare($query, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                return $this->_prepared[$query];
            }
        }
        catch(PDOException $e){throw new PDOException($e);}
    }
    private function purge(){
        $this->_prepared=array();
    }
}
