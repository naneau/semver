<?php
/**
 * Sort.php
 *
 * @category        Naneau
 * @package         SemVer
 */

namespace Naneau\SemVer;

use Naneau\SemVer\Parser;
use Naneau\SemVer\Version;

use \InvalidArgumentException as InvalidArgumentException;

/**
 * Sort
 *
 * Sorting sets of SemVer Versions
 *
 * @category        Naneau
 * @package         SemVer
 */
class Sort
{
    /**
     * Sort a set of SemVer versions
     *
     * This method is a catch-all and accepts:
     *
     *  - A single array of strings or Versions
     *  - A variable number of strings and Versions
     *
     * Strings will be parsed into Version instances
     *
     * The result will be a sorted array of Versions, in ascending order
     *
     * @param  array|string|Version $v1
     * @param  string|Version       $v1
     * ...
     * @param  string|Version       $vn
     * @return array[int]SemVer
     **/
    public static function sort()
    {
        $arguments = func_get_args();

        if (count($arguments) === 0) {
            return array();
        }

        // Versions array
        $versions = array();

        // If the first argument is an array, use that
        if (is_array($arguments[0])) {
            $versions = $arguments[0];
        } else {
            $versions = $arguments;
        }

        // Parse into Version isntances
        foreach($versions as $key => $version) {
            if ($version instanceof Version) {
                $versions[$key] = $version;
            } else if (is_string($version)) {
                $versions[$key] = Parser::parse($version);
            } else {
                throw new InvalidArgumentException(
                    'Invalid version given, pass either Version instances or strings'
                );
            }
        }

        // Use the array sorter
        return self::sortArray($versions);
    }

    /**
     * Sort an array of Versions
     *
     * unlike sort() this method accepts only an array of Version instances
     *
     * This method uses QuickSort for the actual sorting
     *
     * @param array[int]Version
     * @return array[int]Version
     **/
    public static function sortArray(array $versions)
    {
        // Empty array does not needs sorting
        if (count($versions) === 0) {
            return array();
        }

        // Array of one item (pivot) from the middle
        $pivotArray = array_splice(
            $versions,
            (int) floor((count($versions) - 1) / 2),
            1
        );

        // Smaller/greater than pivot stack
        $smaller = array();
        $greater = array();

        // Fill stacks
        foreach($versions as $version) {
            if (Compare::greaterThan($version, $pivotArray[0])) {
                $greater[] = $version;
            } else {
                $smaller[] = $version;
            }
        }

        // Recurse and merge results
        return array_merge(
            self::sortArray($smaller),
            $pivotArray,
            self::sortArray($greater)
        );
    }
}
