<?php

use Naneau\SemVer\Parser;

use \PHPUnit_Framework_TestCase as TestCase;

/**
 * ParseTest
 *
 * Testing the parsers
 *
 * @category      Naneau
 * @package       SemVer
 * @subpackage    Tests
**/
class ParseTest extends TestCase
{
    /**
     * Test parsing of versionable X.Y.Z
     *
     * @return void
     **/
    public function testVersionable()
    {
        $v1 = Parser::parse('0.1.1-rc.1');
        $v2 = Parser::parse('5.2.0+build.269.1244d66');
        $v2 = Parser::parse('1.111.2-alpha.1235+build.4');
        $v3 = Parser::parse('20.0.123+build.6');

        // Major
        $this->assertEquals(
            $v1->getMajor(),
            0,
            'Major should parse'
        );
        $this->assertEquals(
            $v2->getMajor(),
            1,
            'Major should parse'
        );
        $this->assertEquals(
            $v3->getMajor(),
            20,
            'Major should parse'
        );

        // Minor
        $this->assertEquals(
            $v1->getMinor(),
            1,
            'Minor should parse'
        );
        $this->assertEquals(
            $v2->getMinor(),
            111,
            'Minor should parse'
        );
        $this->assertEquals(
            $v3->getMinor(),
            0,
            'Minor should parse'
        );

        // Patch
        $this->assertEquals(
            $v1->getPatch(),
            1,
            'Patch should parse'
        );
        $this->assertEquals(
            $v2->getPatch(),
            2,
            'Patch should parse'
        );
        $this->assertEquals(
            $v3->getPatch(),
            123,
            'Patch should parse'
        );
    }

    /**
     * Test pre release parser in greek form
     *
     * @return void
     **/
    public function testPreReleaseGreek()
    {
        // Greek string
        $this->assertEquals(
            Parser::parse('0.0.1-alpha.1')->getPreRelease()->getGreek(),
            'alpha'
        );
        $this->assertEquals(
            Parser::parse('0.0.1-beta')->getPreRelease()->getGreek(),
            'beta'
        );
        $this->assertEquals(
            Parser::parse('0.0.1-rc.1')->getPreRelease()->getGreek(),
            'rc'
        );
        $this->assertEquals(
            Parser::parse('0.0.1-foo.1')->getPreRelease()->getGreek(),
            'foo'
        );

        // Release number
        $this->assertEquals(
            Parser::parse('0.0.1-alpha')->getPreRelease()->getReleaseNumber(),
            0
        );
        $this->assertEquals(
            Parser::parse('0.0.1-alpha.1')->getPreRelease()->getReleaseNumber(),
            1
        );
        $this->assertEquals(
            Parser::parse('0.0.1-beta.2')->getPreRelease()->getReleaseNumber(),
            2
        );
        $this->assertEquals(
            Parser::parse('0.0.1-rc.123')->getPreRelease()->getReleaseNumber(),
            123
        );

        // Make sure versionable doesn't parse into positive ints
        $this->assertEquals(
            Parser::parse('0.0.1-alpha.1')->getPreRelease()->getMajor(),
            0
        );
        $this->assertEquals(
            Parser::parse('0.0.1-alpha.1')->getPreRelease()->getMinor(),
            0
        );
        $this->assertEquals(
            Parser::parse('0.0.1-alpha.1')->getPreRelease()->getPatch(),
            0
        );
    }

    /**
     * Test pre release parser in greek form
     *
     * @return void
     **/
    public function testPreReleaseVersionable()
    {
        $this->assertEquals(
            Parser::parse('0.0.1-0.1.2')->getPreRelease()->getMajor(),
            0
        );
        $this->assertEquals(
            Parser::parse('0.0.1-0.1.2')->getPreRelease()->getMinor(),
            1
        );
        $this->assertEquals(
            Parser::parse('0.0.1-0.1.123')->getPreRelease()->getPatch(),
            123
        );

        // Make sure there are no greek parts
        $this->assertEmpty(
            Parser::parse('0.0.1-0.1.123')->getPreRelease()->getGreek()
        );
        $this->assertEquals(
            Parser::parse('0.0.1-0.1.123')->getPreRelease()->getReleaseNumber(),
            0
        );
    }

    /**
     * Test the detection of builds
     *
     * @return void
     **/
    public function testBuildDetection()
    {
        $this->assertTrue(
            Parser::parse('0.0.1+build.1')->hasBuild()
        );
        $this->assertFalse(
            Parser::parse('0.0.1')->hasBuild()
        );
        $this->assertFalse(
            Parser::parse('0.0.1-alpha.1')->hasBuild()
        );
    }

    /**
     * Test the build parser
     *
     * @return void
     **/
    public function testBuildNumber()
    {
        $this->assertEquals(
            Parser::parse('0.0.1+build.1')->getBuild()->getNumber(),
            1
        );
        $this->assertEquals(
            Parser::parse('0.0.1+build')->getBuild()->getNumber(),
            0
        );
        $this->assertEquals(
            Parser::parse('0.0.1-alpha.12345+build.3')->getBuild()->getNumber(),
            3
        );
        $this->assertEquals(
            Parser::parse('0.0.1+build.12345.aaaaaa')->getBuild()->getNumber(),
            12345
        );

    }

    /**
     * Test the parsing of remaining parts in the build version
     *
     * @return void
     **/
    public function testBuildParts()
    {
        $this->assertTrue(
            in_array(
                'aaaaaa',
                Parser::parse('0.0.1+build.12345.aaaaaa.bbbbbb.cccccc')->getBuild()->getParts()
            )
        );
        $this->assertTrue(
            in_array(
                'bbbbbb',
                Parser::parse('0.0.1+build.aaaaaa.bbbbbb.cccccc')->getBuild()->getParts()
            )
        );
        $this->assertTrue(
            in_array(
                'cccccc',
                Parser::parse('0.0.1+build.12345.aaaaaa.bbbbbb.cccccc')->getBuild()->getParts()
            )
        );
    }

    /**
     * Test invalid version
     *
     * @expectedException InvalidArgumentException
     * @return void
     **/
    public function testInvalid1()
    {
        $v1 = Parser::parse('foo.1.1');
    }

    /**
     * Test invalid version
     *
     * @expectedException InvalidArgumentException
     * @return void
     **/
    public function testInvalid2()
    {
        $v1 = Parser::parse('0.foo.1');
    }

    /**
     * Test invalid version
     *
     * @expectedException InvalidArgumentException
     * @return void
     **/
    public function testInvalid3()
    {
        $v1 = Parser::parse('10.1.foo');
    }

    /**
     * Test invalid version
     *
     * @expectedException InvalidArgumentException
     * @return void
     **/
    public function testInvalid4()
    {
        $v1 = Parser::parse('0.0.0-!@#');
    }

    /**
     * Test invalid version
     *
     * @expectedException InvalidArgumentException
     * @return void
     **/
    public function testInvalid5()
    {
        $v1 = Parser::parse('0.0.0-build.1+!@#');
    }
}
