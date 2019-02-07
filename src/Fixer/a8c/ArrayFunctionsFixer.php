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
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\CT;

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
        return $tokens->isTokenKindFound(T_FOREACH);
    }

    public function fixForEach(Tokens $tokens, $index) {
        $openIndex = $tokens->getNextTokenOfKind($index, ['{']);
        $closeIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $openIndex);

        $array_variable_content = '';
        for($i = $index; $i < $tokens-> count(); $i++) {
            if($tokens[$i]->isGivenKind(T_VARIABLE)) {
                $array_variable_content = $tokens[$i]->getContent();
                break;
            }
        }
        echo 'closeIndex is ' .$closeIndex;

        $tokens->insertAt($index, new Token([T_FUNCTION, 'function']));
        $index += 1;
        $tokens->insertAt($index, new Token([T_EMPTY, ' ']));
        $index += 1;
        $tokens->insertAt($index, new Token([T_STRING, 'helper1']));
        $index += 1;
        $tokens->insertAt($index, new Token([T_OPEN_TAG, '(']));
        $index += 1;
        $tokens->insertAt($index, new Token([T_VARIABLE, '$helper1_value']));
        $index += 1;
        $tokens->insertAt($index, new Token([T_CLOSE_TAG, ')']));

        $openIndex = $openIndex + 5;
        $closeIndex = $closeIndex + 5;

        $tokensToInsert = [];

        for ($i = $openIndex; $i < $closeIndex; $i++) {
            if($tokens[$i]->getContent() == '$value') {
                array_push($tokensToInsert, new Token([T_VARIABLE, '$helper1_value']));
                continue;
            }
            array_push($tokensToInsert, $tokens[$i]);
        }
        array_push($tokensToInsert, new Token([T_LINE, "\n\t"]));
        array_push($tokensToInsert, new Token([T_RETURN, 'return']));
        array_push($tokensToInsert, new Token([T_STRING, ' $helper1_value;']));
        $index += 1;
        $tokens->insertAt($index, $tokensToInsert);



        $index += count($tokensToInsert);
        $tokens->insertAt($index, new Token([T_LINE, "\n"]));

        $index += 1;
        $tokens->insertAt($index, new Token([T_CLOSE_TAG, '}']));

        $index += 1;
        $tokens->insertAt($index, new Token([T_LINE, "\n"]));

        $index += 1;
        $tokens->insertAt($index, new Token([T_LINE, "\n"]));

        $index += 1;
        $tokens->insertAt($index, new Token([T_FUNC_C, "array_map"]));

        $index += 1;
        $tokens->insertAt($index, new Token([T_OPEN_TAG, '(']));

        $index += 1;
        $tokens->insertAt($index, new Token([ENT_QUOTES, '"']));

        $index += 1;
        $tokens->insertAt($index, new Token([T_VARIABLE, 'helper1']));

        $index += 1;
        $tokens->insertAt($index, new Token([ENT_QUOTES, '"']));

        $index += 1;
        $tokens->insertAt($index, new Token([T_STRING, ',']));

        $index += 1;
        $tokens->insertAt($index, new Token([T_VARIABLE, ' '.$array_variable_content]));

        $index += 1;
        $tokens->insertAt($index, new Token([T_CLOSE_TAG, ')']));

        $index += 1;
        $tokens->insertAt($index, new Token([T_DOUBLE_COLON, ';']));

        $index += 1;
        $tokens->insertAt($index, new Token([T_LINE, "\n"]));


        $tokens->clearRange($index, $tokens->count() - 1);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {

        for ($index = 0; $index < $tokens->count(); ++$index) {
            if ($tokens[$index]->isGivenKind(T_FOREACH)) {
                $this->fixForEach($tokens, $index);
                break;
            }

//            $prevTokenIndex = $tokens->getPrevMeaningfulToken($index);
//            $prevToken = $tokens[$prevTokenIndex];
//
//            if ($prevToken->equals(';')) {
//                $tokens->clearAt($index);
//            }
        }
    }
}
