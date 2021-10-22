<?php 

namespace App\Db;

use \PDO;
use \PDOException;

class Database
{
    
    const HOST  = 'localhost';
    const NAME = 'gabs-vagas';
    const USER   = 'root';
    const PASS   = '';
    
    /**
     *Nome da tabela a ser manipulada
     *
     * @var string
     */
    private $table;
    
    /**
     * Instancia de conexão com banco de dados 
     *
     * @var PDO
     */
    private $connection; 
    
    /**
     * __construct Define a tabela e instacia conexão
     *
     * @param  string $table
     * @return void
     */
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection(); //método criado para não sobrecarregar o construtor
    }
    
    /**
     * método responsável por criar a conexão com o banco de dados e tratar exceções 
     *
     */
    private function setConnection()
    {
        try { 
            $this->connection = new PDO('mysql:host=' .self::HOST . ';dbname='. self::NAME, self::USER, self::PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  //força o PHP e travar caso tenha algum erro de sintaxe no SQL
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());  //obs: tratar as exceções 
        }
    }
        
    /**
     * responsável por executar as querys
     *
     * @param  string $query
     * @param  array $params
     * @return PDOStatement
     */
    public function execute($query, $params = []){
        try { 
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());  //obs: tratar as exceções 
        }
    }


    /**
     * método que vai inserir no banco de dados
     *
     * @param  array $values [ field => value ]
     * @return integer
     */
    public function insert($values)
    {
        //retorna as chaves do array para montar a $query logo abaixo
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');  //criar um array de ? com base no count da variavel $fields 
        $query = 'INSERT INTO '.$this->table.' ('. implode(',', $fields) .') VALUES ('. implode(',', $binds) .')';
        $this->execute($query, array_values($values));
        return  $this->connection->lastInsertId(); //retorna o id que foi inserido
    }
    
    /**
     * Executar consultas no banco de dados
     *
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @param  string $fields
     * @return PDOStatement 
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        //valida se os parametros existem ou serão nulos para preencher a query
        $where = strlen($where) ? 'WHERE ' . $where : ' ';
        $order = strlen($order) ? 'ORDER BY ' . $order : ' ';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : ' ';
        $query = 'SELECT '. $fields .' FROM ' . $this->table . '  ' . $where . ' ' . $order . ' ' . $limit;
        return $this->execute($query);
    }



}
