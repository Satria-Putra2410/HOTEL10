<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Tests\Fixer\Whitespace;

use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @internal
 *
 * @covers \PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer
 *
 * @extends AbstractFixerTestCase<\PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer>
 *
 * @author Javier Spagnoletti <phansys@gmail.com>
 *
 * @phpstan-import-type _AutogeneratedInputConfiguration from \PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer
 */
final class NoSpacesAroundOffsetFixerTest extends AbstractFixerTestCase
{
    /**
     * @param _AutogeneratedInputConfiguration $configuration
     *
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, ?string $input = null, array $configuration = []): void
    {
        $this->fixer->configure($configuration);
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<array{0: string, 1?: null|string, 2?: _AutogeneratedInputConfiguration}>
     */
    public static function provideFixCases(): iterable
    {
        yield [
            '<?php
$foo = array(1, 2, 3);
$var = $foo[1];',
            '<?php
$foo = array(1, 2, 3);
$var = $foo[ 1 ];',
        ];

        yield [
            '<?php
$arr = [2,   2 , ];
$var = $arr[0];',
            '<?php
$arr = [2,   2 , ];
$var = $arr[ 0 ];',
        ];

        yield [
            '<?php
$arr[2] = 3;',
            '<?php
$arr[ 2    ] = 3;',
        ];

        yield [
            '<?php
$arr[] = 3;',
            '<?php
$arr[  ] = 3;',
        ];

        yield [
            '<?php
$arr[]["some_offset"][] = 3;',
            '<?php
$arr[  ][ "some_offset"   ][     ] = 3;',
        ];

        yield [
            '<?php
$arr[]["some  offset with  spaces"][] = 3;',
            '<?php
$arr[  ][ "some  offset with  spaces"   ][     ] = 3;',
        ];

        yield [
            '<?php
$var = $arr[0];',
            '<?php
$var = $arr[     0   ];',
        ];

        yield [
            '<?php
$var = $arr[0][0];',
            '<?php
$var = $arr[    0        ][ 0  ];',
        ];

        yield [
            '<?php
$var = $arr[$a[$b]];',
            '<?php
$var = $arr[    $a    [ $b    ]  ];',
        ];

        yield [
            '<?php
$var = $arr[$a[$b]];',
            '<?php
$var = $arr[	$a	[	$b	]	];',
        ];

        yield [
            '<?php
$var = $arr[0][
     0];',
            '<?php
$var = $arr[0][
     0 ];',
        ];

        yield [
            '<?php
$var = $arr[0][0
         ];',
            '<?php
$var = $arr[0][     0
         ];',
        ];

        yield [
            '<?php
$a = $b[0]    ;',
            '<?php
$a = $b   [0]    ;',
        ];

        yield [
            '<?php
$a = array($b[0]     ,   $b[0]  );',
            '<?php
$a = array($b      [0]     ,   $b [0]  );',
        ];

        yield [
            '<?php
$withComments[0] // here is a comment
    [1] // and here is another
    [2][3] = 4;',
            '<?php
$withComments [0] // here is a comment
    [1] // and here is another
    [2] [3] = 4;',
        ];

        yield [
            '<?php
$c = SOME_CONST[0][1][2];',
            '<?php
$c = SOME_CONST [0] [1]   [2];',
        ];

        yield [
            '<?php
$f = someFunc()[0][1][2];',
            '<?php
$f = someFunc() [0] [1]   [2];',
        ];

        yield [
            '<?php
$foo[][0][1][2] = 3;',
            '<?php
$foo [] [0] [1]   [2] = 3;',
        ];

        yield [
            '<?php
$foo[0][1][2] = 3;',
            '<?php
$foo [0] [1]   [2] = 3;',
        ];

        yield [
            '<?php
$bar = $foo[0][1][2];',
            '<?php
$bar = $foo [0] [1]   [2];',
        ];

        yield [
            '<?php
$baz[0][1][2] = 3;',
            '<?php
$baz [0]
     [1]
     [2] = 3;',
        ];

        yield 'leave new lines alone' => [<<<'EOF'
            <?php

            class Foo
            {
                private function bar()
                {
                    if ([1, 2, 3] && [
                        'foo',
                        'bar' ,
                        'baz'// a comment just to mix things up
                    ]) {
                        return 1;
                    };
                }
            }
            EOF];

        yield [
            '<?php

$withComments[0] // here is a comment
    [1] // and here is another
    [2] = 3;',
        ];

        yield [
            '<?php
$a = $b[# z
 1#z
 ];',
            '<?php
$a = $b[ # z
 1#z
 ];',
        ];

        yield 'leave complex string' => [<<<'EOF'
            <?php

            echo "I am printing some spaces here    {$foo->bar[1]}     {$foo->bar[1]}.";
            EOF];

        yield 'leave functions' => [<<<'EOF'
            <?php

            function someFunc()    {   $someVar = [];   }
            EOF];

        yield 'Config "default".' => [
            '<?php [ $a ] = $a;
if ($controllerName = $request->attributes->get(1)) {
    return false;
}
[  $class  ,   $method  ] = $this->splitControllerClassAndMethod($controllerName);
$a = $b[0];
',
            '<?php [ $a ] = $a;
if ($controllerName = $request->attributes->get(1)) {
    return false;
}
[  $class  ,   $method  ] = $this->splitControllerClassAndMethod($controllerName);
$a = $b   [0];
',
            ['positions' => ['inside', 'outside']],
        ];
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidFixerConfigurationException::class);
        $this->expectExceptionMessageMatches('/^\[no_spaces_around_offset\] Invalid configuration: The option "positions" .*\.$/');

        $this->fixer->configure(['positions' => ['foo']]); // @phpstan-ignore-line
    }

    /**
     * @param _AutogeneratedInputConfiguration $configuration
     *
     * @dataProvider provideFixPre80Cases
     *
     * @requires PHP <8.0
     */
    public function testFixPre80(string $expected, ?string $input = null, array $configuration = []): void
    {
        $this->fixer->configure($configuration);
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<int, array{0: string, 1?: null|string, 2?: _AutogeneratedInputConfiguration}>
     */
    public static function provideFixPre80Cases(): iterable
    {
        yield [
            '<?php
$foo{0}{1}{2} = 3;',
            '<?php
$foo {0} {1}   {2} = 3;',
        ];

        yield [
            '<?php
$foobar = $foo{0}[1]{2};',
            '<?php
$foobar = $foo {0} [1]   {2};',
        ];

        yield [
            '<?php
$var = $arr[0]{0
         };',
            '<?php
$var = $arr[0]{     0
         };',
        ];

        yield from self::provideMultiDimensionalArrayCases();
    }

    /**
     * @param _AutogeneratedInputConfiguration $configuration
     *
     * @dataProvider provideFix80Cases
     *
     * @requires PHP 8.0
     */
    public function testFix80(string $expected, ?string $input, array $configuration): void
    {
        $this->fixer->configure($configuration);
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<int, array{string, string, _AutogeneratedInputConfiguration}>
     */
    public static function provideFix80Cases(): iterable
    {
        foreach (self::provideMultiDimensionalArrayCases() as $index => $test) {
            $test[0] = str_replace('{', '[', $test[0]);
            $test[0] = str_replace('}', ']', $test[0]);
            $test[1] = str_replace('{', '[', $test[1]);
            $test[1] = str_replace('}', ']', $test[1]);

            yield $index => $test;
        }
    }

    /**
     * @return iterable<int, array{string, string, _AutogeneratedInputConfiguration}>
     */
    private static function provideMultiDimensionalArrayCases(): iterable
    {
        yield [
            <<<'EOT'
                <?php
                $arr1[]  ["some_offset"] [] {"foo"} = 3;
                EOT,
            <<<'EOT'
                <?php
                $arr1[  ]  [ "some_offset"   ] [     ] { "foo" } = 3;
                EOT,
            ['positions' => ['inside']],
        ];

        yield [
            <<<'EOT'
                <?php
                $arr1[  ][ "some_offset"   ][     ]{ "foo" } = 3;
                EOT,
            <<<'EOT'
                <?php
                $arr1[  ]  [ "some_offset"   ] [     ] { "foo" } = 3;
                EOT,
            ['positions' => ['outside']],
        ];
    }
}
