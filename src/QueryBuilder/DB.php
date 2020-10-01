<?php


namespace Sre\QueryBuilder;


use Sre\QueryBuilder\Grammars\MySQL;
use InvalidArgumentException;

class DB
{
    private $grammar = null;

    public function __construct($grammar)
    {
        if (!$grammar) {
            throw new InvalidArgumentException('DB Should be constructed with a grammar');
        }
        $this->grammar = $grammar;
    }


    /**
     * @param $table
     * @return QueryBuilder
     */
    public function Table($table) {
        $qb = new QueryBuilder($table);
        $qb->setGrammar($this->grammar);
        return $qb;
    }

    public function getGrammar() {
        return $this->grammar;
    }
    public function setGrammar($grammar) {
        $this->grammar = $grammar;
    }
}