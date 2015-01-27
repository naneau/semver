<?php
/**
 * Parser.php
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Parser
 */

namespace Naneau\SemVer;

use Naneau\SemVer\Parser\Build as BuildParser;
use Naneau\SemVer\Parser\PreRelease as PreReleaseParser;
use Naneau\SemVer\Parser\Versionable as VersionableParser;

use Naneau\SemVer\Regex;

use Naneau\SemVer\Version as SemVerVersion;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * Parser
 *
 * Full SemVer version parser
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Parser
 */
class Parser
{
    /**
     * Parse a string into a SemVer Version
     *
     * @throws InvalidArgumentException
     *
     * @param string $string
     * @return SemVerVersion
     **/
    public static function parse($string)
    {
        $matches = Regex::matchSemVer($string);

        // Parse the SemVer root
        $version = VersionableParser::parse(
            $matches[1],
            'Naneau\SemVer\Version'
        );

        // There is a pre-release part
        if (!empty($matches['prerelease'])) {
            $version->setPreRelease(
                PreReleaseParser::parse(
                    ltrim($matches['prerelease'], '-')
                )
            );
        }

        // There is a build number
        if (!empty($matches['build'])) {
            $version->setBuild(
                BuildParser::parse(
                    ltrim($matches['build'], '+')
                )
            );
        }

        // Return
        $version->setOriginalVersion($string);
        return $version;
    }
}
