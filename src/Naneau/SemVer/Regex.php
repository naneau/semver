<?php
/**
 * Regex.php
 *
 * @category        Naneau
 * @package         SemVer
 */

namespace Naneau\SemVer;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * Regex
 *
 * Regex matching and parsing for SemVer strings
 *
 * @category        Naneau
 * @package         SemVer
 */
class Regex
{
    /**
     * Single SemVer expression
     *
     * @var string
     **/
    private static $version = '/^(?<version>[0-9]+\.[0-9]+\.[0-9]+)(?<prerelease>-[0-9a-zA-Z.]+)?(?<build>\+[0-9a-zA-Z.]+)?$/';

    /**
     * Match a SemVer using a regex
     *
     * Array wil at least have a key `version`
     *
     * But might also contain:
     *
     *  - prerelease
     *  - build
     *
     * @throws InvalidArgumentException
     *
     * @param  string              $string
     * @return array[string]string
     **/
    public static function matchSemVer($string)
    {
        // Array of matches for PCRE
        $matches = array();

        // Match the possible parts of a SemVer
        $matched = preg_match(
            self::$version,
            $string,
            $matches
        );

        // No match, invalid
        if (!$matched) {
            throw new InvalidArgumentException(
                '"' . $string . '" is not a valid SemVer'
            );
        }

        // Return matched array
        return $matches;
    }
}
