<?php

declare(strict_types=1);

namespace PetrKnap;

use PetrKnap\Shorts\PhpUnit\MarkdownFileTestInterface;
use PetrKnap\Shorts\PhpUnit\MarkdownFileTestTrait;
use PetrKnap\Persistence\ZonedDateTime\UtcWithLocal;
use PetrKnap\Persistence\ZonedDateTime\UtcWithTimezone;
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
            UtcWithLocal::class => self::OUTPUT_IN_MARKDOWN,
            UtcWithTimezone::class => self::OUTPUT_IN_MARKDOWN,
        ];
    }
}
