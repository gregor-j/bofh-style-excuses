<?php

use GregorJ\BOFHStyleExcuses\Excuses;

class ExcusesTest extends \PHPUnit\Framework\TestCase
{

    private function generateListOfThreeExcusesURL()
    {
        $tmpfile = tempnam("/tmp", "excuse");
        $excuses = [
            "This is the first excuse.",
            "This is the second excuse.",
            "This is the third excuse."
        ];
        file_put_contents($tmpfile, implode(PHP_EOL, $excuses));
        return sprintf("file://%s", $tmpfile);
    }

    public function testConstructor()
    {
        $url = $this->generateListOfThreeExcusesURL();
        $excuses = new Excuses($url);
        $this->assertTrue($excuses->hasExcuses());
        $this->assertEquals(3, $excuses->count());
        unlink($url);
    }

    //this test takes too long, therefore I disabled it.
    public function DISABLEDtestConstructorDefaultUrl()
    {
        $excuses = new Excuses();
        $this->assertTrue($excuses->hasExcuses());
        $this->assertGreaterThan(10, $excuses->count());
    }

    public function testGetExcusePosition()
    {
        $url = $this->generateListOfThreeExcusesURL();
        $excuses = new Excuses($url);
        $this->assertEquals("This is the first excuse.", $excuses->get(0));
        $this->assertEquals("This is the second excuse.", $excuses->get(1));
        $this->assertEquals("This is the third excuse.", $excuses->get(2));
    }

    public function testRandomExcusePositionGenerator()
    {
        $url = $this->generateListOfThreeExcusesURL();
        $excuses = new Excuses($url);
        $issued_excuses = [];
        while (count($issued_excuses) < 3) {
            $pos = $excuses->randomPosition();
            $this->assertArrayNotHasKey($pos, $issued_excuses);
            $issued_excuses[$pos] = true;
        }
        $pos = $excuses->randomPosition();
        $this->assertArrayHasKey($pos, $issued_excuses);
    }

}
