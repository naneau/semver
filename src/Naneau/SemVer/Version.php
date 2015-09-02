<?php
/**
 * Version.php
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Version
 */

namespace Naneau\SemVer;

use Naneau\SemVer\Version\Versionable;
use Naneau\SemVer\Version\Build;
use Naneau\SemVer\Version\PreRelease;
use InvalidArgumentException;

/**
 * Version
 *
 * A single SemVer version
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Version
 */
class Version extends Versionable
{
    /**
     * Pre release version
     *
     * @var PreRelease
     **/
    private $preRelease;

    /**
     * Build version
     *
     * @var Build
     **/
    private $build;

    /**
     * Original version String
     *
     * @var originalVersionString
     */
    private $originalVersionString;

    /**
     * Set the original version string for later usage
     *
     * @param $version
     */
    public function setOriginalVersion($version)
    {
        $this->originalVersionString = $version;
    }

    /**
     * Get the original version string
     *
     * @return string
     */
    public function getOriginalVersion()
    {
        return $this->originalVersionString;
    }

    /**
     * Get the pre release version
     *
     * @return PreRelease
     */
    public function getPreRelease()
    {
        return $this->preRelease;
    }

    /**
     * Set the pre release version
     *
     * @param  PreRelease $preRelease
     * @return Version
     */
    public function setPreRelease(PreRelease $preRelease)
    {
        $this->preRelease = $preRelease;

        return $this;
    }

    /**
     * Does this Version have a pre release?
     *
     * @return bool
     **/
    public function hasPreRelease()
    {
        return ($this->preRelease instanceof PreRelease);
    }

    /**
     * Get the build
     *
     * @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * Set the build
     *
     * @param  Build   $build
     * @return Version
     */
    public function setBuild(Build $build)
    {
        $this->build = $build;

        return $this;
    }

    /**
     * Does this Version have a Build?
     *
     * @return bool
     **/
    public function hasBuild()
    {
        return ($this->build instanceof Build);
    }

    /**
     * Get the next logical version relative to the provided base version. If
     * no base is supplied, base will be the same as the current version.
     *
     * @param  Version|string|null $base
     * @return Version
     * @throws InvalidArgumentException
     */
    public function next($base = null)
    {
        //  Ensure that $base is a Version. Parse it if we must, use ourself if
        //  it is empty.
        if (empty($base)) {
            $base = $this;
        }
        else {
            if (is_string($base)) {
                $base = Parser::parse($base);
            }
            elseif (! $base instanceof Version) {
                throw new InvalidArgumentException("\$base must be of type Version");
            }
        }

        // If the base is ahead of this Version then the next version will be
        // the base.
        if (Compare::greaterThan($base, $this)) {
            return $base->cleanCopy();
        }

        $next = new Version;

        $next->setMajor($this->getMajor());
        $next->setMinor($this->getMinor());
        $next->setPatch($this->getPatch());

        if ($base->hasPreRelease()) {
            if ($this->hasPreRelease()) {
                // We already know that $base is less than or equal to $this
                // and we won't be jumping to the next greek value. So it is
                // safe use $this prerelease and just increment the release
                // number.
                $pre = new PreRelease;
                $pre->setGreek($this->getPreRelease()->getGreek());
                $pre->setReleaseNumber($this->getPreRelease()->getReleaseNumber() + 1);

                $next->setPreRelease($pre);
            }
            else {
                throw new InvalidArgumentException("This version has left prerelease without updating the base. Base should not be prerelease.");
            }
        }
        elseif ( ! $this->hasPreRelease()) {
            $next->setPatch($this->getPatch() + 1);
        }
        // The case of $this having a pre-release when $base does not means
        // that we are essentially just leaving pre-release. Nothing needs to
        // be done.

        return $next;
    }

    /**
     * Create a new Version that discards the entity information of build and
     * originalVersionString
     *
     * @return Version
     */
    public function cleanCopy()
    {
        $version = new Version;

        $version->setMajor($this->getMajor());
        $version->setMinor($this->getMinor());
        $version->setPatch($this->getPatch());

        if ($this->hasPreRelease()) {
            $version->preRelease = clone($this->getPreRelease());
        }

        return $version;
    }

    /**
     * String representation of this Version
     *
     * @return string
     **/
    public function __toString()
    {
        $string = parent::__toString();

        // Add pre-release
        if ($this->hasPreRelease()) {
            $string .= '-' . $this->getPreRelease();
        }

        // Add build
        if ($this->hasBuild()) {
            $string .= '+' . $this->getBuild();
        }

        return $string;
    }

}
