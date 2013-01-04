<?php
/**
 * PreRelease.php
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Version
 */

namespace Naneau\SemVer\Version;

/**
 * PreRelease
 *
 * Pre release version status
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Version
 */
class PreRelease extends Versionable
{
    /**
     * "Greek" name
     *
     * @var string
     **/
    private $greek;

    /**
     * Release number
     *
     * @var int
     **/
    private $releaseNumber = 0;

    /**
     * Get the "greek" name of the pre-release status
     *
     * @return string
     */
    public function getGreek()
    {
        return $this->greek;
    }

    /**
     * Set the "greek" name of the pre-release status
     *
     * @param string $greek
     * @return PreRelease
     */
    public function setGreek($greek)
    {
        $this->greek = $greek;

        return $this;
    }

    /**
     * Get the release number
     *
     * @return int
     */
    public function getReleaseNumber()
    {
        return $this->releaseNumber;
    }

    /**
     * Set the release number
     *
     * @param int $releaseNumber
     * @return PreRelease
     */
    public function setReleaseNumber($releaseNumber)
    {
        $this->releaseNumber = $releaseNumber;

        return $this;
    }

    /**
     * Get string representation
     *
     * @return string
     **/
    public function __toString()
    {
        if ($this->getMajor() > 0 || $this->getMinor() > 0 || $this->getPatch() > 0) {
            return parent::__toString();
        }

        return $this->getGreek() . '.' . $this->getReleaseNumber();
    }
}
