<?php

namespace GeneratorTest;

use Generator\Generator;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use SplFileObject;

/**
 * Author: niidashi
 * Date: 2016/01/25
 * Time: 1:13
 */
class GeneratorTest extends PHPUnit_Framework_TestCase
{
    private $Gen;

    public function setUp()
    {
        $this->Gen = new Generator(
            new SplFileObject(__DIR__ . '/template.php', 'w')
        );
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function setContentsのセット確認()
    {
        $expected = '12345';
        $this->Gen->setContents($expected);

        $refClass = new ReflectionClass($this->Gen);
        $contents = $refClass->getProperty('contents');
        $contents->setAccessible(true);
        $actual = $contents->getValue($this->Gen);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function replaceの置換確認()
    {
        $expected =<<<'EOL'
<?php
class Foo extends Bar {
    private $baz = 'qux';
}
EOL;

        $format =<<<'EOL'
<?php
class :ClassName: extends :ParentClass: {
    private $baz = ':value:';
}
EOL;

        $this->Gen->setContents($format);
        $this->Gen->replace(
            [
                ':ClassName:' => 'Foo',
                ':ParentClass:' => 'Bar',
                ':value:' => 'qux',
            ]
        );

        $refClass = new ReflectionClass($this->Gen);
        $contents = $refClass->getProperty('contents');
        $contents->setAccessible(true);
        $actual = $contents->getValue($this->Gen);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function fireまでの通し確認()
    {
        $expected = 1264;
        $mock = m::mock('SplFileObject', [__DIR__ . '/template.php', 'w']);
        $mock->shouldReceive('fwrite')->once()->andReturn($expected);
        $Gen = new Generator($mock);
        $Gen->setContents('FooBarBaxQux');
        $actual = $Gen->fire();
        $this->assertSame($expected, $actual);
    }
}
