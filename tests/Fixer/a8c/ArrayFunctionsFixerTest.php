<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Tests\Fixer\a8c;

use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @author Your name <your@email.com>
 *
 * @internal
 *
 * @covers \PhpCsFixer\Fixer\a8c\ArrayFunctionsFixer
 */
final class ArrayFunctionsFixerTest extends AbstractFixerTestCase
{
    /**
     * @param string      $expected
     * @param null|string $input
     *
     * @dataProvider provideFixCases
     */
    public function testFix($expected, $input = null)
    {
        $this->doTest($expected, $input);
    }

    public function provideFixCases()
    {
        return [
            [
                '<?php $languages=["PHP", "Python", "Swift", "Javascript"];', // This is expected output
                '<?php $arr = array(1, 2, 3, 4);
foreach ($arr as &$value) {
    $value = $value * 2;
}', // This is input
            ],
        ];
    }
}
