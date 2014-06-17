<?php
/**
 * Compare.php
 *
 * @category        Naneau
 * @package         SemVer
 */

namespace Naneau\SemVer;

use Naneau\SemVer\Version;
use Naneau\SemVer\Version\Versionable;
use Naneau\SemVer\Version\Build;
use Naneau\SemVer\Version\PreRelease;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * Compare
 *
 * Comparisons and functions around SemVer versions
 *
 * @see Naneau\SemVer\Version
 *
 * @category        Naneau
 * @package         SemVer
 */
class Compare
{
    /**
     * Greek part precedence
     *
     * @var array[int]string
     **/
    private static $greekPrecendence = array('pre-alpha', 'alpha', 'pre-beta',
        'beta', 'pre-rc', 'rc');

    /**
     * Get the greatest SemVer out of a set of Versions
     *
     * @param  Version $v1
     * @param  Version $v2
     * ...
     * @param  Version $vn
     * @return Version
     **/
    public static function greatest()
    {
        // Versions given
        $versions = func_get_args();

        // Number of versions given
        $count = count($versions);

        // Make sure there's one
        if ($count === 0) {
            throw new InvalidArgumentException('No versions given');
        }

        // If there's only one, it is great. Very great.
        if ($count === 1) {
            return $versions[0];
        }

        // Get versions
        $v1 = array_shift($versions);
        $v2 = array_shift($versions);

        if (self::greaterThan($v1, $v2)) {
            $greatest = $v1;
        } else {
            $greatest = $v2;
        }

        // Recurse if there's versions left
        if (count($versions) > 0) {
            return call_user_func_array(
                array('Naneau\SemVer\Compare', 'greatest'),
                array_merge(
                    array($greatest),
                    $versions
                )
            );
        }

        return $greatest;
    }

    /**
     * Are two versions equal to one another?
     *
     * @param  Version $v1
     * @param  Version $v2
     * @return bool
     **/
    public static function equals(Version $v1, Version $v2)
    {
        // Versionable part itself needs to be equal
        if (!self::versionableEquals($v1, $v2)) {
            return false;
        }

        // If only one has a pre-release, they're not equal
        if ($v1->hasPreRelease() && !$v2->hasPreRelease()) {
            return false;
        }
        if (!$v1->hasPreRelease() && $v2->hasPreRelease()) {
            return false;
        }

        // See if pre-releases are equal if both versions have one
        if ($v1->hasPreRelease() && $v2->hasPreRelease()) {
            if (!self::preReleaseEquals($v1->getPreRelease(), $v2->getPreRelease())) {
                return false;
            }
        }

        // If only one has a build version, they're not equal
        if (!$v1->hasBuild() && $v2->hasBuild()) {
            return false;
        }
        if ($v1->hasBuild() && !$v2->hasBuild()) {
            return false;
        }

        // Compare the build version
        if ($v1->hasBuild() && $v2->hasBuild()) {
            return self::buildEquals($v1->getBuild(), $v2->getBuild());
        }

        return true;
    }

    /**
     * Is a Version greater than another one?
     *
     * @param  Version $v1
     * @param  Version $v2
     * @return bool
     **/
    public static function greaterThan(Version $v1, Version $v2)
    {
        // If they are equal, they can not be greater/smaller than each other
        if (self::equals($v1,  $v2)) {
            return false;
        }

        // Compare on the major/minor/patch level if we can
        if (!self::versionableEquals($v1, $v2)) {
            return self::versionableGreaterThan($v1, $v2);
        }

        // v1 has a pre-release, but v2 does not, v2 is bigger
        if ($v1->hasPreRelease() && !$v2->hasPreRelease()) {
            return false;
        }

        // v1 does not have a pre-release, but v2 does, v1 is bigger
        if (!$v1->hasPreRelease() && $v2->hasPreRelease()) {
            return true;
        }

        // Compare on the re-release level if possible
        if ($v1->hasPreRelease() && $v2->hasPreRelease()) {
            if (!self::preReleaseEquals($v1->getPreRelease(), $v2->getPreRelease())) {

                // If v1 has a larger pre-release than v2, it's bigger
                if (self::preReleaseGreaterThan($v1->getPreRelease(), $v2->getPreRelease())) {
                    return true;
                }

                // v2 has a larger pre-release than v1, v2 is bigger.
                return false;
            }
        }

        // Both have the same pre-release version, but only one has a build
        // Version with a build is bigger
        if ($v1->hasBuild() && !$v2->hasBuild()) {
            return true;
        }
        if (!$v1->hasBuild() && $v2->hasBuild()) {
            return false;
        }

        // Compare the build version
        if ($v1->hasBuild() && $v2->hasBuild()) {
            return self::buildGreaterThan($v1->getBuild(), $v2->getBuild());
        }

        return true;
    }

    /**
     * Is a version smaller than another one?
     *
     * @param  Version $v1
     * @param  Version $v2
     * @return bool
     **/
    public static function smallerThan(Version $v1, Version $v2)
    {
        // If it's equal it can not be smaller
        if (self::equals($v1, $v2)) {
            return false;
        }

        // If it's not equal, and not greater, it must be smaller
        return !self::greaterThan($v1, $v2);
    }

    /**
     * Compare two Versionable objects
     *
     * Returns true if first is greater than second, false if not
     *
     * @param  Versionable $v1
     * @param  Versionable $v2
     * @return bool
     **/
    private static function versionableGreaterThan(Versionable $v1, Versionable $v2)
    {
        if ($v1->getMajor() > $v2->getMajor()) {
            return true;
        }
        if ($v1->getMajor() < $v2->getMajor()) {
            return false;
        }

        if ($v1->getMinor() > $v2->getMinor()) {
            return true;
        }
        if ($v1->getMinor() < $v2->getMinor()) {
            return false;
        }

        if ($v1->getPatch() > $v2->getPatch()) {
            return true;
        }
        if ($v1->getPatch() < $v2->getPatch()) {
            return false;
        }

        return false;
    }

  /**
     * Compare two Versionable objects
     *
     * Returns true if they are equal
     *
     * @param  Versionable $v1
     * @param  Versionable $v2
     * @return bool
     **/
    private static function versionableEquals(Versionable $v1, Versionable $v2)
    {
        if ($v1->getMajor() !== $v2->getMajor()) {
            return false;
        }
        if ($v1->getMinor() !== $v2->getMinor()) {
            return false;
        }
        if ($v1->getPatch() !== $v2->getPatch()) {
            return false;
        }

        return true;
    }

    /**
     * Does a build equal another build?
     *
     * @param  Build $v1
     * @param  Build $v2
     * @return bool
     */
    private static function buildEquals(Build $v1, Build $v2)
    {
        return $v1->getNumber() === $v2->getNumber();
    }

    /**
     * Is a build version greater than another one?
     *
     * @param  Build $v1
     * @param  Build $v2
     * @return bool
     */
    private static function buildGreaterThan(Build $v1, Build $v2)
    {
        return $v1->getNumber() > $v2->getNumber();
    }

    /**
     * Does a pre-release equal another one?
     *
     * @param  PreRelease $v1
     * @param  PreRelease $v2
     * @return bool
     **/
    private static function preReleaseEquals(PreRelease $v1, PreRelease $v2)
    {
        // If they don't match on the versionable level they can not match
        if (!self::versionableEquals($v1, $v2)) {
            return false;
        }

        // Both other parts need to match
        if ($v1->getGreek() !== $v2->getGreek()) {
            return false;
        }
        if ($v1->getReleaseNumber() !== $v2->getReleaseNumber()) {
            return false;
        }

        return true;
    }

    /**
     * Is one pre-release version bigger than another one?
     *
     * @param  PreRelease $v1
     * @param  PreRelease $v2
     * @return bool
     **/
    private static function preReleaseGreaterThan(PreRelease $v1, PreRelease $v2)
    {
        // Pre-releases can be denoted as versionable (X.Y.Z) or greek (alpha.5)
        // We let versionable take precedence
        if (!self::versionableEquals($v1, $v2)) {
            // If v1 is bigger on the versionable level, it's bigger.
            return self::versionableGreaterThan($v1, $v2);
        }

        // "Greek" part is "bigger" for v1
        if ($v1->getGreek() !== $v2->getGreek()) {
            return self::greekLargerThan($v1->getGreek(), $v2->getGreek());
        }

        // Release number for v1 is bigger than v2's
        if ($v1->getReleaseNumber() > $v2->getReleaseNumber()) {
            return true;
        }

        return false;
    }

    /**
     * Compare "greek" strings
     *
     * @see SemVer::$greekPrecendence
     *
     * @param  string $greek1
     * @param  string $greek2
     * @return bool
     **/
    private static function greekLargerThan($greek1, $greek2)
    {
        // If they do not exist as valid precendence identifiers assume
        // bigger/smaller based on that
        if (!in_array($greek1, self::$greekPrecendence)) {
            return true;
        }
        if (!in_array($greek2, self::$greekPrecendence)) {
            return false;
        }

        // Use the index in precendence for comparison
        return
            array_search($greek1, self::$greekPrecendence)
            >
            array_search($greek2, self::$greekPrecendence);
    }
}

