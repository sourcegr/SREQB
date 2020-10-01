<?php

namespace Tests;


use Sourcegr\QueryBuilder\QueryBuilder;
use Sourcegr\QueryBuilder\DB;
use Sourcegr\QueryBuilder\Raw;
use Sourcegr\QueryBuilder\Grammars\MySQL;
use Sourcegr\QueryBuilder\Exceptions\DeleteErrorException;
use Tests\Stub\Grammar;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

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