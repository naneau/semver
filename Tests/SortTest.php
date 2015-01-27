<?php
/**
 * SortTest.php
 *
 * @category        Naneau
 * @package         SemVer
 */

use Naneau\SemVer\Parser;
use Naneau\SemVer\Sort as Sorter;

use \PHPUnit_Framework_TestCase as TestCase;

/**
 * SortTest
 *
 * Testing SemVer sorting
 *
 * @category        Naneau
 * @package         SemVer
 */
class SortTest extends TestCase
{
    /**
     * Test sort of strings
     *
     * @return void
     **/
    public function testSortStrings()
    {
        $v1 = '2.0.2';
        $v2 = '2.0.2';
        $v3 = '0.0.1';
        $v4 = '10.0.1-rc.1+build.12345';
        $v5 = '10.0.2-rc.1+build.12345'; // Biggest
        $v6 = '0.0.4';
        $v7 = '0.0.1-alpha'; // Smallest

        $sorted = Sorter::sort($v1, $v2, $v3, $v4, $v5, $v6, $v7);

        $this->assertCount(
            7,
            $sorted
        );

        $this->assertEquals(
            (string) $sorted[0],
            $v7
        );
        $this->assertEquals(
            (string) $sorted[1],
            $v3
        );
        $this->assertEquals(
            (string) $sorted[2],
            $v6
        );
        $this->assertEquals(
            (string) $sorted[3],
            $v1
        );
        $this->assertEquals(
            (string) $sorted[4],
            $v1
        );
        $this->assertEquals(
            (string) $sorted[5],
            $v4
        );
        $this->assertEquals(
            (string) $sorted[6],
            $v5
        );
    }

    /**
     * Test sort of array
     *
     * @return void
     **/
    public function testSortArray()
    {
        $v1 = Parser::parse('2.0.2');
        $v2 = Parser::parse('2.0.2');
        $v3 = Parser::parse('0.0.1'); // Smallest
        $v4 = Parser::parse('10.0.1-rc.1+build.12345');
        $v5 = Parser::parse('10.0.2-rc.1+build.12345'); // Biggest
        $v6 = Parser::parse('0.0.4');

        $sorted = Sorter::sortArray(array($v1, $v2, $v3, $v4, $v5, $v6));

        $this->assertCount(
            6,
            $sorted
        );

        $this->assertEquals(
            $sorted[0],
            $v3
        );
        $this->assertEquals(
            $sorted[1],
            $v6
        );
        $this->assertEquals(
            $sorted[2],
            $v1
        );
        $this->assertEquals(
            $sorted[3],
            $v1
        );
        $this->assertEquals(
            $sorted[4],
            $v4
        );
        $this->assertEquals(
            $sorted[5],
            $v5
        );
    }

    /**
     * Test sort of array
     *
     * @expectedException InvalidArgumentException
     * @return void
     **/
    public function testSortInvalid()
    {
        Sorter::sort('0.0.1', 2, '1.2.3');
    }
}
