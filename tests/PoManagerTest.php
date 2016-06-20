<?php

namespace MisiekSnk\PoManager;

/**
 * Class PoManagerTest
 */
class PoManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        copy(dirname(__FILE__) . '/translations/pl.default.po', dirname(__FILE__) . '/translations/pl.po');
    }
    
    public function tearDown()
    {
        unlink(dirname(__FILE__) . '/translations/pl.po');
    }

    /**
     * @test
     */
    public function itShouldReturnTranslationsArray()
    {
        $poManager = new PoManager();
        $poManager->open(dirname(__FILE__) . '/translations/pl.po');

        $transltions = $poManager->getTranslationsArray();
        $this->assertInternalType('array', $transltions);
        $this->assertArrayHasKey('Phrase with no translation', $transltions);
    }

    /**
     * @test
     * @dataProvider dpItShouldReturnTranslation
     */
    public function itShouldReturnTranslation($phrase, $expected)
    {
        $poManager = new PoManager();
        $poManager->open(dirname(__FILE__) . '/translations/pl.po');

        $translation = $poManager->getTranslation($phrase);
        $this->assertEquals($expected, $translation);
    }

    /**
     * @test
     */
    public function itShouldChangeTranslation()
    {
        $poManager = new PoManager();
        $poManager->open(dirname(__FILE__) . '/translations/pl.po');

        $phrase = 'Phrase with no translation';
        $translated = 'Fraza bez translacji';
        $poManager->setTranslation($phrase, $translated);

        $translation = $poManager->getTranslation($phrase);

        $this->assertEquals($translated, $translation);
    }

    /**
     * @test
     */
    public function itShouldGenerateMoFile()
    {
        $poManager = new PoManager();
        $poManager->open(dirname(__FILE__) . '/translations/pl.po');
        $poManager->updateMo();

        $this->assertTrue(file_exists(dirname(__FILE__) . '/translations/pl.mo'));

        unlink(dirname(__FILE__) . '/translations/pl.mo');
    }

    //================================

    public function dpItShouldReturnTranslation()
    {
        return [
            ['Phrase with no translation', ''],
            ['Simple phrase', 'Prosta fraza'],
            ['Multiline phrase To translate', 'Wielolinijkowa fraza' . PHP_EOL . PHP_EOL . 'Do tłumaczenia'],
            ['Not exists', false]
        ];
    }
}
