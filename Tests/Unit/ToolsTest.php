<?php

namespace Ecodev\Newsletter\Tests\Unit;

use Ecodev\Newsletter\Tools;

/**
 * Unit test for \Ecodev\Newsletter\Tools
 */
class ToolsTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    public function testEncryption()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] = 'encryptionKeyValue';
        $encrypted = Tools::encrypt('my value');
        $this->assertNotSame('my value', $encrypted, 'must be encrypted');
        $decrypted = Tools::decrypt($encrypted);
        $this->assertSame('my value', $decrypted, 'must be original value');
    }
}
