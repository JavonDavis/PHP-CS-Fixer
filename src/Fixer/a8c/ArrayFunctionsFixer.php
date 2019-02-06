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

namespace PhpCsFixer\Fixer\a8c;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Tokenizer\Tokens;

final class ArrayFunctionsFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'New code MUST NOT use array() to create array primitives; use the [] shorthand syntax instead.

This is just for the sake of consistency. The shorthand is fewer characters to type and is commonly used outside of WordPress code that must support PHP < 5.4.',
            [
                new CodeSample(
                    '<?php $foo=array(1,2,3);'
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_ARRAY);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
//            if (!$token->isGivenKind(T_ARRAY)) {
//                continue;
//            }

            continue;
            $prevTokenIndex = $tokens->getPrevMeaningfulToken($index);
            $prevToken = $tokens[$prevTokenIndex];

            if ($prevToken->equals(';')) {
                $tokens->clearAt($index);
            }
        }
    }
}
