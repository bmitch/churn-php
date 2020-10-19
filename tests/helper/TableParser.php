<?php declare(strict_types = 1);

namespace Churn\Tests\Helper;

use const PHP_EOL;
use function preg_match;

class TableParser
{
    public static function parse(string $output): array
    {
        return self::parseTable(array_map('rtrim', explode(PHP_EOL, $output)));
    }

    private static function parseTable(array $rows): array
    {
        $headers = [];
        $i = 0;
        foreach ($rows as $i => $row) {
            if (isset($rows[$i + 2]) && $rows[$i + 2] === $row /*&& preg_match('/^\\+-+(\\|-+)*\\+$/', $row) === 1*/) {
                $headers = array_map('trim', explode('|', substr($rows[$i + 1], 1, -1)));
                break;
            }
        }
        if (empty($headers)) {
            return [];
        }
        $table = [];
        for ($i = $i + 3; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (!isset($row[0]) || $row[0] !== '|') {
                break;
            }
            $values = array_map('trim', explode('|', substr($row, 1, -1)));
            $table[] = array_combine($headers, $values);
        }
        return $table;
    }
}
