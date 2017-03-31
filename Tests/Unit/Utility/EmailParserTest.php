<?php

namespace Ecodev\Newsletter\Tests\Unit\Utility;

use Ecodev\Newsletter\Utility\EmailParser;

/**
 * Test case for class Ecodev\Newsletter\Utility\EmailParser.
 */
class EmailParserTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    public function dataProviderTestParser()
    {
        $pattern = dirname(__DIR__) . '/Fixtures/bounce/*.eml';
        $result = [];
        foreach (glob($pattern) as $filename) {
            $parts = explode('-', basename($filename));
            $result[] = [
                (int) $parts[0],
                $parts[1] ?: null,
                $parts[2],
                $filename,
            ];
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderTestParser
     * @param mixed $expectedBounce
     * @param mixed $expectedAuthCode
     * @param mixed $message
     * @param mixed $filename
     */
    public function testParser($expectedBounce, $expectedAuthCode, $message, $filename)
    {
        $emailSource = file_get_contents($filename);
        $parser = new EmailParser();
        $parser->parse($emailSource);
        $this->assertSame($expectedBounce, $parser->getBounceLevel(), 'Bounce level should be ' . var_export($expectedBounce, true) . ' for ' . $message);
        $this->assertSame($expectedAuthCode, $parser->getAuthCode(), 'AuthCode should be ' . var_export($expectedAuthCode, true) . ' for ' . $message);
    }
}
