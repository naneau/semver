<?php
/**
 * Versionable.php
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Version
 */

namespace Naneau\SemVer\Version;

/**
 * Versionable
 *
 * Versionable class, both the main SemVer version as well as pre-release parts
 * can contain X.Y.Z version numbers
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Version
 */
abstract class Versionable
{
    /**
     * Major version
     *
     * @var int
     **/
    private $major = 0;

    /**
     * Minor version
     *
     * @var int
     **/
    private $minor = 0;

    /**
     * Patch version
     *
     * @var int
     **/
    private $patch = 0;

    /**
     * Get the major of the Version
     *
     * @return int
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set the major of the Version
     *
     * @param  int     $major
     * @return Version
     */
    public function setMajor($major)
    {
        $this->major = $major;

        return $this;
    }

    /**
     * Get the minor of the Version
     *
     * @return int
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * Set the minor of the Version
     *
     * @param  int     $minor
     * @return Version
     */
    public function setMinor($minor)
    {
        $this->minor = $minor;

        return $this;
    }

    /**
     * Get the patch of the version
     *
     * @return int
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * Set the patch of the version
     *
     * @param  int     $patch
     * @return Version
     */
    public function setPatch($patch)
    {
        $this->patch = $patch;

        return $this;
    }

    /**
     * To strings
     *
     * @return void
     **/
    public function __toString()
    {
        return sprintf(
            '%d.%d.%d',
            $this->getMajor(),
            $this->getMinor(),
            $this->getPatch()
        );
    }
}
