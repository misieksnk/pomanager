<?php
namespace MisiekSnk\PoManager;

use MisiekSnk\MsgFmt\Generate;
use Sepia\FileHandler;
use Sepia\PoParser;

/**
 * Class PoManager
 *
 * @package MisiekSnk\PoManager
 */
class PoManager
{
    private $poFile;
    private $parser;
    private $parsedTranslations = [];
    private $translations = [];
    private $parsedKeys = [];

    /**
     * @param $filename string Path to .po file
     */
    public function __construct($filename)
    {
        $this->poFile = $filename;
        $fileHandler = new FileHandler($this->poFile);
        $this->parser = new PoParser($fileHandler);
        $this->fillArrays();
    }

    /**
     * @return array
     */
    public function getTranslationsArray()
    {
        return $this->translations;
    }

    /**
     * @param $msgId string Phase to translate
     * @return bool|string
     */
    public function getTranslation($msgId)
    {
        return isset($this->translations[$msgId]) ? $this->translations[$msgId] : false;
    }

    /**
     * @param $msgId string Phrase to translate
     * @param $msgStr string Translated phrase
     * @throws \Exception
     */
    public function setTranslation($msgId, $msgStr)
    {
        $this->translations[$msgId] = $msgStr;
        $this->parsedTranslations[$this->parsedKeys[$msgId]]['msgstr'] = explode(PHP_EOL, $msgStr);
        $this->parser->setEntry($this->parsedKeys[$msgId], $this->parsedTranslations[$this->parsedKeys[$msgId]]);
        $this->parser->writeFile($this->poFile);
    }

    /**
     * Updates .mo file for current .po file
     */
    public function updateMo()
    {
        $moWriter = new Generate();
        $moWriter->convert($this->poFile);
    }

    /**
     * @throws \Exception
     */
    private function fillArrays()
    {
        $this->parsedTranslations = $this->parser->parse();

        $translations = [];
        $parsedKeys = [];
        foreach ($this->parsedTranslations as $key => $parsedTranslation) {
            $niceKey = $this->getNiceKey($key);
            $parsedKeys[$niceKey] = $key;
            $translations[$niceKey] = trim(join(PHP_EOL, $parsedTranslation['msgstr']));
        }

        ksort($translations);
        
        $this->translations = $translations;
        $this->parsedKeys = $parsedKeys;
    }

    /**
     * @param $dirtyKey string Array key from Sepia array
     * @return string
     */
    private function getNiceKey($dirtyKey)
    {
        $niceKey = preg_replace('!\s+!', ' ', trim(str_replace([PHP_EOL, '<##EOL##>'], [' ', ' '], $dirtyKey)));

        return $niceKey;
    }
}
