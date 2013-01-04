<?php
/**
 * Build.php
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Parser
 */

namespace Naneau\SemVer\Parser;

use Naneau\SemVer\Version\Build as BuildVersion;

/**
 * Build
 *
 * Build string parser
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Parser
 */
class Build
{
    /**
     * Parse a build part string
     *
     * A build part can look like build.11.e0f985a
     *
     * @param  string       $string
     * @return BuildVersion
     **/
    public static function parse($string)
    {
        // Explode over '.'
        $parts = explode('.', $string);

        // Instantiate
        $buildVersion = new BuildVersion;

        // No parts is no build?
        if (count($parts) === 0) {
            return $buildVersion;
        }

        // Discard "build" string should it prepend the build
        if ($parts[0] === 'build') {
            array_shift($parts);
        }

        // If the first part is a number it's the build number
        if (isset($parts[0]) && is_numeric($parts[0])) {
            $buildVersion->setNumber(
                (int) array_shift($parts)
            );
        }

        // All other parts are custom and can simply be put on a stack
        foreach ($parts as $part) {
            $buildVersion->addPart($part);
        }

        // Return
        return $buildVersion;
    }
}
