<?php

declare(strict_types=1);

namespace PetrKnap;

use PetrKnap\Shorts\PhpUnit\MarkdownFileTestInterface;
use PetrKnap\Shorts\PhpUnit\MarkdownFileTestTrait;
use PetrKnap\ZonedDateTimePersistence\UtcWithLocal;
use PHPUnit\Framework\TestCase;

final class ReadmeTest extends TestCase implements MarkdownFileTestInterface
{
    use MarkdownFileTestTrait;

    public static function getPathToMarkdownFile(): string
    {
        return __DIR__ . '/../../README.md';
    }

    public static function getExpectedOutputsOfPhpExamples(): iterable
    {
        return [
            UtcWithLocal::class =>
                '2025-10-26 02:45 GMT+0200: We still have summer time' . PHP_EOL .
                '2025-10-26 02:15 GMT+0100: Now we have winter time' . PHP_EOL,
        ];
    }
}
