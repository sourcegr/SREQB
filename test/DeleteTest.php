<?php

namespace Test;


use Sre\QueryBuilder\QueryBuilder;
use Sre\QueryBuilder\DB;
use Sre\QueryBuilder\Raw;
use Sre\QueryBuilder\Grammars\MySQL;
use Sre\QueryBuilder\Grammars\DeleteErrorException;
use PHPUnit\Framework\TestCase;
use Test\Stub\Grammar;
use InvalidArgumentException;

class DeleteTest extends TestCase
{
    static $table = 'table';

    private function init()
    {
        $grammar = new Grammar();
        $qb = new QueryBuilder(static::$table);
        $qb->setGrammar($grammar);
        return $qb;
    }

    public function testDeleteAll()
    {
        $res = $this->init();
        [$actual, $params] = $res->delete();

        $expected = 'DELETE FROM ' . static::$table;
        $expectedParams = [];

        $this->assertEquals($expected, $actual, 'testDelete SQL');
        $this->assertEquals($expectedParams, $params, 'testDelete params');
    }
    public function testDeleteWhere()
    {
        $res = $this->init();
        [$actual, $params] = $res->where('id', 1)->delete();

        $expected = 'DELETE FROM ' . static::$table.' WHERE id=?';
        $expectedParams = [1];

        $this->assertEquals($expected, $actual, 'testDelete SQL');
        $this->assertEquals($expectedParams, $params, 'testDelete params');
    }
}