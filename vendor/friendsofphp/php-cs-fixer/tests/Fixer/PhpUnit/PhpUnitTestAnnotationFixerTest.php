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

namespace PhpCsFixer\Tests\Fixer\PhpUnit;

use PhpCsFixer\Tests\Test\AbstractFixerTestCase;
use PhpCsFixer\WhitespacesFixerConfig;

/**
 * @internal
 *
 * @covers \PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer
 *
 * @extends AbstractFixerTestCase<\PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer>
 *
 * @author Gert de Pagter
 *
 * @phpstan-import-type _AutogeneratedInputConfiguration from \PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer
 */
final class PhpUnitTestAnnotationFixerTest extends AbstractFixerTestCase
{
    /**
     * @param _AutogeneratedInputConfiguration $config
     *
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, ?string $input = null, array $config = []): void
    {
        $this->fixer->configure($config);
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<string, array{0: string, 1?: null|string, 2?: _AutogeneratedInputConfiguration}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'Annotation is used, and it should not be' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     *
     */
    public function testItDoesSomething() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function itDoesSomething() {}
}',
            ['style' => 'prefix'],
        ];

        yield 'Annotation is not used, but should be' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function itDoesSomething() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function testItDoesSomething() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation is not used, but should be, class is extra indented' => [
            '<?php
if (1) {
    class Test extends \PhpUnit\FrameWork\TestCase
    {
        /**
         * @test
         */
        public function itDoesSomething() {}
    }
}',
            '<?php
if (1) {
    class Test extends \PhpUnit\FrameWork\TestCase
    {
        public function testItDoesSomething() {}
    }
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation is not used, but should be, and there is already a docBlock' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @dataProvider blabla
     *
     * @test
     */
    public function itDoesSomething() {}
    }',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @dataProvider blabla
     */
    public function testItDoesSomething() {}
    }',
            ['style' => 'annotation'],
        ];

        yield 'Annotation is used, but should not be, and it depends on other tests' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     *
     */
    public function testAaa () {}

    public function helperFunction() {}

    /**
     * @depends testAaa
     *
     *
     */
    public function testBbb () {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function aaa () {}

    public function helperFunction() {}

    /**
     * @depends aaa
     *
     * @test
     */
    public function bbb () {}
}',
            ['style' => 'prefix'],
        ];

        yield 'Annotation is not used, but should be, and it depends on other tests' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function aaa () {}

    /**
     * @depends aaa
     *
     * @test
     */
    public function bbb () {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function testAaa () {}

    /**
     * @depends testAaa
     */
    public function testBbb () {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation is removed, the function is one word and we want it to use camel case' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     *
     */
    public function testWorks() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function works() {}
}',
        ];

        yield 'Annotation is added, and it is snake case' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function it_has_snake_case() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function test_it_has_snake_case() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation gets added, it has an @depends, and we use snake case' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function works_fine () {}

    /**
     * @depends works_fine
     *
     * @test
     */
    public function works_fine_too() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function test_works_fine () {}

    /**
     * @depends test_works_fine
     */
    public function test_works_fine_too() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Class has both camel and snake case, annotated functions and not, and wants to add annotations' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function snake_cased () {}

    /**
     * @test
     */
    public function camelCased () {}

    /**
     * Description.
     *
     * @depends camelCased
     *
     * @test
     */
    public function depends_on_someone () {}

    //It even has a comment
    public function a_helper_function () {}

    /**
     * @depends depends_on_someone
     *
     * @test
     */
    public function moreDepends() {}

    /**
     * @depends depends_on_someone
     *
     * @test
     */
    public function alreadyAnnotated() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function test_snake_cased () {}

    public function testCamelCased () {}

    /**
     * Description.
     *
     * @depends testCamelCased
     */
    public function test_depends_on_someone () {}

    //It even has a comment
    public function a_helper_function () {}

    /**
     * @depends test_depends_on_someone
     */
    public function testMoreDepends() {}

    /**
     * @depends test_depends_on_someone
     *
     * @test
     */
    public function alreadyAnnotated() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation has to be added to multiple functions' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function itWorks() {}

    /**
     * @test
     */
    public function itDoesSomething() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function testItWorks() {}

    public function testItDoesSomething() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Class with big doc blocks and multiple functions has to remove annotations' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{

    /**
     * This test is part of the database group and has a provider.
     *
     * @param int $paramOne
     * @param bool $paramTwo
     *
     *
     * @dataProvider provides
     * @group Database
     */
    public function testDatabase ($paramOne, $paramTwo) {}

    /**
     * Provider for the database test function
     *
     * @return array
     */
    public function provides() {}

    /**
     * I am just a helper function but I have test in my name.
     * I also have a doc Block
     *
     * @return Foo\Bar
     */
    public function help_test() {}


    protected function setUp() {}

    /**
     * I depend on the database function, but I already
     * had test in my name and a docblock
     *
     * @depends testDatabase
     */
    public function testDepends () {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{

    /**
     * This test is part of the database group and has a provider.
     *
     * @param int $paramOne
     * @param bool $paramTwo
     *
     * @test
     * @dataProvider provides
     * @group Database
     */
    public function database ($paramOne, $paramTwo) {}

    /**
     * Provider for the database test function
     *
     * @return array
     */
    public function provides() {}

    /**
     * I am just a helper function but I have test in my name.
     * I also have a doc Block
     *
     * @return Foo\Bar
     */
    public function help_test() {}


    protected function setUp() {}

    /**
     * I depend on the database function, but I already
     * had test in my name and a docblock
     *
     * @depends database
     */
    public function testDepends () {}
}',
        ];

        yield 'Test Annotation has to be removed, but its just one line' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /** */
    public function testItWorks() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /** @test */
    public function itWorks() {}
}',
        ];

        yield 'Test annotation has to be added, but there is already a one line doc block' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @group Database
     *
     * @test
     */
    public function itTestsDatabase() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /** @group Database */
    public function testItTestsDatabase() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Test annotation has to be added, but there is already a one line doc block which is a sentence' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * I really like this test, it helps a lot
     *
     * @test
     */
    public function itTestsDatabase() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /** I really like this test, it helps a lot */
    public function testItTestsDatabase() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Test annotation has to be added, but there is already a one line comment present' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    //I really like this test, it helps a lot
    /**
     * @test
     */
    public function itTestsDatabase() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    //I really like this test, it helps a lot
    public function testItTestsDatabase() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Test annotation has to be added, there is a one line doc block which is an @depends tag' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function itTestsDatabase() {}

    /**
     * @depends itTestsDatabase
     *
     * @test
     */
    public function itDepends() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function testItTestsDatabase() {}

    /** @depends testItTestsDatabase */
    public function testItDepends() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation gets removed, but the function has a @testWith' => [
            '<?php
final class ProcessLinterProcessBuilderTest extends TestCase
{
    /**
     *
     * @param string $executable
     * @param string $file
     * @param string $expected
     *
     * @testWith ["php", "foo.php", "\"php\" -l \"foo.php\""]
     *           ["C:\Program Files\php\php.exe", "foo bar\baz.php", "\"C:\Program Files\php\php.exe\" -l \"foo bar\baz.php\""]
     * @requires OS Linux|Darwin
     */
    public function testPrepareCommandOnPhpOnLinuxOrMac($executable, $file, $expected)
    {
        $builder = new ProcessLinterProcessBuilder($executable);

        $this->assertSame(
            $expected,
            $builder->build($file)->getCommandLine()
        );
    }
}',
            '<?php
final class ProcessLinterProcessBuilderTest extends TestCase
{
    /**
     * @test
     * @param string $executable
     * @param string $file
     * @param string $expected
     *
     * @testWith ["php", "foo.php", "\"php\" -l \"foo.php\""]
     *           ["C:\Program Files\php\php.exe", "foo bar\baz.php", "\"C:\Program Files\php\php.exe\" -l \"foo bar\baz.php\""]
     * @requires OS Linux|Darwin
     */
    public function prepareCommandOnPhpOnLinuxOrMac($executable, $file, $expected)
    {
        $builder = new ProcessLinterProcessBuilder($executable);

        $this->assertSame(
            $expected,
            $builder->build($file)->getCommandLine()
        );
    }
}',
        ];

        yield 'Annotation gets added, but there is already an @testWith in the doc block' => [
            '<?php
final class ProcessLinterProcessBuilderTest extends TestCase
{
    /**
     * @param string $executable
     * @param string $file
     * @param string $expected
     *
     * @testWith ["php", "foo.php", "\"php\" -l \"foo.php\""]
     *           ["C:\Program Files\php\php.exe", "foo bar\baz.php", "\"C:\Program Files\php\php.exe\" -l \"foo bar\baz.php\""]
     * @requires OS Linux|Darwin
     *
     * @test
     */
    public function prepareCommandOnPhpOnLinuxOrMac($executable, $file, $expected)
    {
        $builder = new ProcessLinterProcessBuilder($executable);

        $this->assertSame(
            $expected,
            $builder->build($file)->getCommandLine()
        );
    }
}',
            '<?php
final class ProcessLinterProcessBuilderTest extends TestCase
{
    /**
     * @param string $executable
     * @param string $file
     * @param string $expected
     *
     * @testWith ["php", "foo.php", "\"php\" -l \"foo.php\""]
     *           ["C:\Program Files\php\php.exe", "foo bar\baz.php", "\"C:\Program Files\php\php.exe\" -l \"foo bar\baz.php\""]
     * @requires OS Linux|Darwin
     */
    public function testPrepareCommandOnPhpOnLinuxOrMac($executable, $file, $expected)
    {
        $builder = new ProcessLinterProcessBuilder($executable);

        $this->assertSame(
            $expected,
            $builder->build($file)->getCommandLine()
        );
    }
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation gets properly removed, even when it is in a weird place' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * I am a comment about the function
     */
    public function testIHateMyTestSuite() {}

    /**
     * I am another comment about a function
     */
    public function testThisMakesNoSense() {}

    /**
     * This comment has   more issues
     */
    public function testItUsesTabs() {}

    /**
     * @depends testItUsesTabs
     */
    public function testItDependsReally() {}

    /**
     * @depends testItUsesTabs
     */
    public function testItDependsSomeMore() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * I am a comment @test about the function
     */
    public function iHateMyTestSuite() {}

    /**
     * I am another comment about a function @test
     */
    public function thisMakesNoSense() {}

    /**
     * This comment has @test   more issues
     */
    public function itUsesTabs() {}

    /**
     * @depends itUsesTabs @test
     */
    public function itDependsReally() {}

    /**
     * @test @depends itUsesTabs
     */
    public function itDependsSomeMore() {}
}',
        ];

        yield 'Annotation gets added when a single line has doc block has multiple tags already' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * There is some text here @group Database @group Integration
     *
     * @test
     */
    public function whyDoThis() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /** There is some text here @group Database @group Integration */
    public function testWhyDoThis() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation gets removed when a single line doc block has the tag, but there are other things as well' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /** There is some text here @group Database @group Integration */
    public function testWhyDoThis() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /** There is some @test text here @group Database @group Integration */
    public function testWhyDoThis() {}
}',
        ];

        yield 'Annotation is used, and should be' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function itDoesSomething() {}
}',
            null,
            ['style' => 'annotation'],
        ];

        yield 'Annotation is not used, and should not be' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function testItDoesSomethingWithoutPhpDoc() {}
    /**
     * No annotation, just text
     */
    public function testItDoesSomethingWithPhpDoc() {}

    public function testingItDoesSomethingWithoutPhpDoc() {}
    /**
     * No annotation, just text
     */
    public function testingItDoesSomethingWithPhpDoc() {}
}',
        ];

        yield 'Annotation is added when it is already present in a weird place' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * I am a comment @test about the function
     *
     * @test
     */
    public function iHateMyTestSuite() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * I am a comment @test about the function
     */
    public function iHateMyTestSuite() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Docblock does not get converted to a multi line doc block if it already has @test annotation' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /** @test */
    public function doesSomeThings() {}
}',
            null,
            ['style' => 'annotation'],
        ];

        yield 'Annotation does not get added if class is not a test' => [
            '<?php
class Waterloo
{
    public function testDoesSomeThings() {}
}',
            null,
            ['style' => 'annotation'],
        ];

        yield 'Annotation does not get removed if class is not a test' => [
            '<?php
class Waterloo
{
    /**
     * @test
     */
    public function doesSomeThings() {}
}',
        ];

        yield 'Annotation does not get added if there are no tests in the test class' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function setUp() {}

    public function itHelpsSomeTests() {}

    public function someMoreChanges() {}
}',
            null,
            ['style' => 'annotation'],
        ];

        yield 'Abstract test gets annotation removed' => [
            '<?php
abstract class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     *
     */
    abstract function testFooBar();
}',
            '<?php
abstract class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    abstract function fooBar();
}',
            ['style' => 'prefix'],
        ];

        yield 'Annotation present, but method already have test prefix' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     *
     */
    public function testarossaIsFromItaly() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function testarossaIsFromItaly() {}
}',
            ['style' => 'prefix'],
        ];

        yield 'Annotation present, but method is test prefix' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     *
     */
    public function test() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function test() {}
}',
            ['style' => 'prefix'],
        ];

        yield 'Abstract test gets annotation added' => [
            '<?php
abstract class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    abstract function fooBar();
}',
            '<?php
abstract class Test extends \PhpUnit\FrameWork\TestCase
{
    abstract function testFooBar();
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation gets added, but there is a number after the testprefix so it keeps the prefix' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function test123fooBar() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function test123fooBar() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation missing, but there is a lowercase character after the test prefix so it keeps the prefix' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function testarossaIsFromItaly() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function testarossaIsFromItaly() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation present, but there is a lowercase character after the test prefix so it keeps the prefix' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function testarossaIsFromItaly() {}
}',
            null,
            ['style' => 'annotation'],
        ];

        yield 'Annotation missing, method qualifies as test, but test prefix cannot be removed' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function test() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function test() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation missing, method qualifies as test, but test_ prefix cannot be removed' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function test_() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function test_() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'Annotation present, method qualifies as test, but test_ prefix cannot be removed' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function test_() {}
}',
            null,
            ['style' => 'annotation'],
        ];

        yield 'Annotation missing, method after fix still has "test" prefix' => [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    public function test_foo() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    public function test_test_foo() {}
}',
            ['style' => 'annotation'],
        ];

        yield 'do not touch single line @depends annotation when already correct' => [
            '<?php class FooTest extends \PHPUnit\Framework\TestCase
                {
                    public function testOne() {}

                    /** @depends testOne */
                    public function testTwo() {}

                    /**    @depends    testTwo    */
                    public function testThree() {}
                }',
        ];
    }

    /**
     * @param _AutogeneratedInputConfiguration $config
     *
     * @dataProvider provideWithWhitespacesConfigCases
     */
    public function testWithWhitespacesConfig(string $expected, ?string $input = null, array $config = []): void
    {
        $this->fixer->setWhitespacesConfig(new WhitespacesFixerConfig("\t", "\r\n"));
        $this->fixer->configure($config);
        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<int, array{string, string}>
     */
    public static function provideWithWhitespacesConfigCases(): iterable
    {
        yield [
            '<?php

                    class FooTest extends \PHPUnit_Framework_TestCase {

                    /**
                     *
                     */
                    public function testFooTest() {}
                    }
                ',
            '<?php

                    class FooTest extends \PHPUnit_Framework_TestCase {

                    /**
                     * @test
                     */
                    public function fooTest() {}
                    }
                ',
        ];
    }

    /**
     * @dataProvider provideFix80Cases
     *
     * @param _AutogeneratedInputConfiguration $config
     *
     * @requires PHP 8.0
     */
    public function testFix80(string $expected, string $input, array $config): void
    {
        $this->fixer->configure($config);

        $this->doTest($expected, $input);
    }

    /**
     * @return iterable<int, array{string, 1?: ?string}>
     */
    public static function provideFix80Cases(): iterable
    {
        yield [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    #[OneTest]
    public function itWorks() {}

    /**
     * @test
     */
    #[TwoTest]
    public function itDoesSomething() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    #[OneTest]
    public function testItWorks() {}

    #[TwoTest]
    public function testItDoesSomething() {}
}',
            ['style' => 'annotation'],
        ];

        yield [
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    /**
     * @test
     */
    #[OneTest]
    #[Internal]
    public function itWorks() {}

    /**
     * @test
     */
    #[TwoTest]
    #[Internal]
    public function itDoesSomething() {}
}',
            '<?php
class Test extends \PhpUnit\FrameWork\TestCase
{
    #[OneTest]
    #[Internal]
    public function testItWorks() {}

    #[TwoTest]
    #[Internal]
    public function testItDoesSomething() {}
}',
            ['style' => 'annotation'],
        ];
    }
}
