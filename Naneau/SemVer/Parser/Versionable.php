<?php
/**
 * Versionable.php
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Parser
 */

namespace Naneau\SemVer\Parser;

use Naneau\SemVer\Version\Versionable as VersionableVersion;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * Versionable
 *
 * Parses strings in form X.Y.Z into major, minor, patch of Versionable
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Parser
 */
class Versionable
{
    /**
     * Parse a string into Versionable properties
     *
     * @throws InvalidArgumentException
     *
     * @param  string             $string
     * @param  string             $className
     * @return VersionableVersion
     **/
    public static function parse($string, $className)
    {
        // Sanity check
        if (substr_count($string, '.') !== 2) {
            throw new InvalidArgumentException(
                'part "' . $string . '" can not be parsed into a SemVer'
                . ' major.minor.patch version'
            );
        }

        // Simple explode will do here
        $parts = explode('.', $string);

        // Instantiate
        $versionable = new $className;

        // Sanity check of class type
        if (!($versionable instanceof VersionableVersion)) {
            throw new InvalidArgumentException(
                '"' . $className . '" is not Versionable'
            );
        }

        // Versionable parts
        $versionable
            ->setMajor((int) $parts[0])
            ->setMinor((int) $parts[1])
            ->setPatch((int) $parts[2]);

        return $versionable;
    }
}
