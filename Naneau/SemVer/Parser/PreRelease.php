<?php
/**
 * PreRelease.php
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Parser
 */

namespace Naneau\SemVer\Parser;

use Naneau\SemVer\Parser\Versionable as VersionableParser;

use Naneau\SemVer\Version\PreRelease as PreReleaseVersion;

/**
 * PreRelease
 *
 * PreRelease part parser uses Versionable parser for most of the grunt work,
 * but parses "greek" name, etc. as well.
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Parser
 */
class PreRelease
{
    /**
     * Parse pre release version string
     *
     * @param string
     * @return PreReleaseVersion
     **/
    public static function parse($string)
    {
        // Type X.Y.Z, can be parsed as Versionable
        if (substr_count($string, '.') === 2) {
            return VersionableParser::parse($string, 'Naneau\SemVer\Version\PreRelease');
        }

        $preRelease = new PreReleaseVersion;

        $parts = explode('.', $string);

        // Sanity check
        if (count($parts) === 0) {
            return $preRelease;
        }

        // Set the greek name
        $preRelease->setGreek(
            $parts[0]
        );

        // If there's another part it's a release number
        if (isset($parts[1])) {
            $preRelease->setReleaseNumber(
                (int) $parts[1]
            );
        }

        return $preRelease;
    }
}

