<?php
/**
 * Build.php
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Version
 */

namespace Naneau\SemVer\Version;

use \InvalidArgumentException;

/**
 * Build
 *
 * Build part
 *
 * @category        Naneau
 * @package         SemVer
 * @subpackage      Version
 */
class Build
{
    /**
     * Build number
     *
     * @var int
     **/
    private $number;

    /**
     * Parts
     *
     * @var array[int]string
     **/
    private $parts = array();

    /**
     * Get the build number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set the build number
     *
     * @param  int   $number
     * @return Build
     */
    public function setNumber($number)
    {
        if ($number < 0) {
            throw new InvalidArgumentException(
                'Build number "' . $number . '" is invalid'
            );
        }

        $this->number = $number;

        return $this;
    }

    /**
     * Get the build parts
     *
     * @return array[int]string
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Set the build parts
     *
     * @param  array[int]string $parts
     * @return Build
     */
    public function setParts($parts)
    {
        $this->parts = array();

        foreach($parts as $part) {
            $this->addPart($part);
        }

        return $this;
    }

    /**
     * Add a part to the build parts stack
     *
     * @param  string $part
     * @return Build
     **/
    public function addPart($part)
    {
        // Sanity check
        if (!ctype_alnum($part)) {
            throw new InvalidArgumentException(
                'Build part "' . $part . '" is not alpha numerical'
            );
        }

        $this->parts[] = $part;

        return $this;
    }

    /**
     * Get string representation
     *
     * @return string
     **/
    public function __toString()
    {
        // If there are other parts
        if (count($this->getParts()) > 0) {
            $parts = array('build');

            // Add number if we have one
            if ($this->getNumber() !== null) {
                $parts[] = $this->getNumber();
            }

            $parts[] = implode('.', $this->getParts());

            return implode('.', $parts);
        }

        // No number, no parts, no output.
        if ($this->getNumber() === null) {
            return '';
        }

        return 'build.' . $this->getNumber();
    }
}
