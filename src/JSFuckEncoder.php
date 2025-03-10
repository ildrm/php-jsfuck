<?php

class JSFuckEncoder {
    private static $baseStrings = [
        'false'     => '![]+[]',
        'true'      => '!![]+[]',
        'undefined' => '[][[]]+[]',
        'object'    => '{}+[]',
    ];

    private static $charMapCache = [];

    private static function generateCharMap() {
        if (!empty(self::$charMapCache)) return;

        foreach (self::$baseStrings as $string => $expression) {
            $chars = str_split($string);
            foreach ($chars as $index => $char) {
                if (!isset(self::$charMapCache[$char])) {
                    // For "object", since the actual output is "[object Object]", we increment the index by 1.
                    $exprIndex = ($string === 'object') ? $index + 1 : $index;
                    self::$charMapCache[$char] = "({$expression})[$exprIndex]";
                }
            }
        }

        // Mapping adjustments for special characters:

        // Space: In "[object Object]", index 7 corresponds to a space.
        self::$charMapCache[' '] = "({}+[])[7]";

        // Open and close parentheses: Using String.fromCharCode as a fallback.
        self::$charMapCache['('] = "([].filter.constructor('return String.fromCharCode(40)')())";
        self::$charMapCache[')'] = "([].filter.constructor('return String.fromCharCode(41)')())";

        // Dot: ASCII code 46
        self::$charMapCache['.'] = "([].filter.constructor('return String.fromCharCode(46)')())";

        // Semicolon: ASCII code 59
        self::$charMapCache[';'] = "([].filter.constructor('return String.fromCharCode(59)')())";
    }

    private static function encodeNumber($num) {
        if ($num === 0) return '+[]';

        $result = [];
        for ($i = 0; $i < $num; $i++) {
            $result[] = '+!![]';
        }
        return '('. implode('', $result) .')';
    }

    private static function encodeChar($char) {
        self::generateCharMap();

        if (isset(self::$charMapCache[$char])) {
            return self::$charMapCache[$char];
        }

        $lower = mb_strtolower($char, 'UTF-8');
        if ($lower !== $char && isset(self::$charMapCache[$lower])) {
            // Using standard access to the toUpperCase method.
            return '('. self::$charMapCache[$lower] .')["toUpperCase"]()';
        }

        $codePoint = mb_ord($char, 'UTF-8');
        $encodedNumber = self::encodeNumber($codePoint);
        $result = "([].filter.constructor('return String.fromCharCode({$encodedNumber})')())";
        self::$charMapCache[$char] = $result;
        return $result;
    }

    public static function encode($input) {
        $result = [];
        $length = mb_strlen($input, 'UTF-8');
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($input, $i, 1, 'UTF-8');
            $result[] = self::encodeChar($char);
        }
        $encodedString = implode('+', $result);
        // Convert the string into an executable function.
        return "([].filter.constructor({$encodedString})())";
    }
}

// Test
// $originalScript = 'alert("test");';
// $encodedScript = JSFuckEncoder::encode($originalScript);
// $code = '<script>' . $encodedScript . ';</script>';
?>
