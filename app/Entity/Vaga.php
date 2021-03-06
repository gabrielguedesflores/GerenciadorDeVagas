<?php

namespace App\Entity;

use \App\Db\Database;

use \PDO;

class Vaga{
        
    /**
     * Identificador único da vaga
     *
     * @var integer
     */
    public $id;

   /**
    * Titulo da vaga
    *
    * @var string
    */
   public $titulo;   

   /**
    * Descricao da vaga
    *
    * @var string
    */
   public $descricao;
   
   /**
    * Define se a vaga está ativa
    *
    * @var string(s/n)
    */
   public $ativo;
   
   /**
    * Data de publicação da vaga
    *
    * @var string
    */
   public $data;
    
    /**
     * Este método vai cadastrar uma nova vaga no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->data = date('Y-m-d H:i:s');
        $obDatabase = new Database('vagas');
        $this->id = $obDatabase->insert([
                                                                            'titulo'         => $this->titulo,
                                                                            'descricao' => $this->descricao,
                                                                            'ativo'          => $this->ativo,
                                                                            'data'           => $this->data
                                                                        ]);
        //populamos a propriedade $id com o retorno do metodo insert()   
        
        return true;
    }
        
    /**
     * Atualiza a vaga no banco de dados
     *
     * @return boolean
     */
    public function atualizar()
    {
        return (new Database('vagas'))->update('id = ' . $this->id, [
                                                                                                                            'titulo'         => $this->titulo,
                                                                                                                            'descricao' => $this->descricao,
                                                                                                                            'ativo'          => $this->ativo,
                                                                                                                            'data'           => $this->data
                                                                                                                        ]);
                            
    }
    
    /**
     * Excuir vagas do banco de dados
     *
     * @return boolean
     */
    public function excluir()
    {
        return (new Database('vagas'))->delete('id = ' . $this->id);
    }


    /**
     * método que obtem as vagas do banco de dados
     *
     * @param  string $where
     * @param  string $order
     * @param  string $limit
     * @return array 
     */
    public static function getVagas($where = null, $order = null, $limit = null)
    {
        return (new Database('vagas'))->select($where, $order, $limit)  //estático que cria uma instancia na própria classe e chama o método select()
                                                                    ->fetchAll(PDO::FETCH_CLASS, self::class); //salva o retorno e transforma em um array de classes e do tipo instancia de propria classe

    }
    
    /**
     * Responsável por buscar uma vaga filtrando pelo ID
     *
     * @param  integer $id
     * @return Vaga
     */
    public static function getVaga($id){
        return (new Database('vagas'))->select('id = '.$id)
                                                                    ->fetchObject(self::class);  //no parametro é passado a classe que quero instanciar
    }


}