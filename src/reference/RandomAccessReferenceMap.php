<?php
/**
 * OWASP Enterprise Security API (ESAPI).
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2011 The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 *
 *  @author Andrew van der Stock
 *  @created 2009
 *
 *  @since 1.6
 *
 *  @license BSD license
 */

/**
 * Reference Implementation of the RandomAccessReferenceMap interface.
 *
 * @category  OWASP
 *
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @version   Release: @package_version@
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class RandomAccessReferenceMap implements AccessReferenceMap
{
    private $dtoi;

    private $itod;

    private $random = 0;

    public function __construct($directReferences = null)
    {
        $this->random = mt_rand();

        $this->dtoi = new ArrayObject();
        $this->itod = new ArrayObject();

        if (!empty($directReferences)) {
            $this->update($directReferences);
        }
    }

    /**
     * Get an iterator through the direct object references. No guarantee is made as
     * to the order of items returned.
     *
     * @return the iterator
     */
    public function iterator()
    {
        return $this->dtoi->getIterator();
    }

    /**
     * Get a safe indirect reference to use in place of a potentially sensitive
     * direct object reference. Developers should use this call when building
     * URL's, form fields, hidden fields, etc... to help protect their private
     * implementation information.
     *
     * @param $directReference The direct reference
     *
     * @return The indirect reference
     */
    public function getIndirectReference($direct)
    {
        if (empty($direct)) {
            return;
        }

        $hash = $this->getHash($direct);

        if (!($this->dtoi->offsetExists($hash))) {
            return;
        }

        return $this->dtoi->offsetGet($hash);
    }

    /**
     * Get the original direct object reference from an indirect reference.
     * Developers should use this when they get an indirect reference from a
     * request to translate it back into the real direct reference. If an
     * invalid indirect reference is requested, then an AccessControlException is
     * thrown.
     *
     * @param $indirectReference The indirect reference
     *
     * @throws AccessControlException If no direct reference exists for the specified indirect reference
     *
     * @return The direct reference
     */
    public function getDirectReference($indirectReference)
    {
        if (!empty($indirectReference) && $this->itod->offsetExists($indirectReference)) {
            return $this->itod->offsetGet($indirectReference);
        }

        throw new AccessControlException('Access denied', 'Request for invalid indirect reference: ' + $indirectReference);
    }

    /**
     * Adds a direct reference to the AccessReferenceMap, then generates and returns
     * an associated indirect reference.
     *
     * @param $direct The direct reference
     *
     * @return The corresponding indirect reference
     */
    public function addDirectReference($direct)
    {
        if (empty($direct)) {
            return;
        }

        $hash = $this->getHash($direct);

        if ($this->dtoi->offsetExists($hash)) {
            return $this->dtoi->offsetGet($hash);
        }

        $indirect = $this->getUniqueRandomReference();

        $this->itod->offsetSet($indirect, $direct);
        $this->dtoi->offsetSet($hash, $indirect);

        return $indirect;
    }

    /**
     * Create a new random reference that is guaranteed to be unique.
     *
     *  @return A random reference that is guaranteed to be unique
     */
    public function getUniqueRandomReference()
    {
        $candidate = null;

        do {
            $candidate = ESAPI::getRandomizer()->getRandomString(6, '123456789');
        } while ($this->itod->offsetExists($candidate));

        return $candidate;
    }

    public function getHash($direct)
    {
        if (empty($direct)) {
            return;
        }

        $hash = hexdec(substr(md5(serialize($direct)), -7));

        return $hash;
    }

    /**
     * Removes a direct reference and its associated indirect reference from the AccessReferenceMap.
     *
     * @param $direct The direct reference to remove
     *
     * @throws AccessControlException
     *
     * @return The corresponding indirect reference
     */
    public function removeDirectReference($direct)
    {
        if (empty($direct)) {
            return;
        }

        $hash = $this->getHash($direct);

        if ($this->dtoi->offsetExists($hash)) {
            $indirect = $this->dtoi->offsetGet($hash);
            $this->itod->offsetUnset($indirect);
            $this->dtoi->offsetUnset($hash);

            return $indirect;
        }
    }

    /**
     * Updates the access reference map with a new set of direct references, maintaining
     * any existing indirect references associated with items that are in the new list.
     * New indirect references could be generated every time, but that
     * might mess up anything that previously used an indirect reference, such
     * as a URL parameter.
     *
     * @param $directReferences A set of direct references to add
     */
    public function update($directReferences)
    {
        $dtoi_old = clone $this->dtoi;

        unset($this->dtoi);
        unset($this->itod);

        $this->dtoi = new ArrayObject();
        $this->itod = new ArrayObject();

        $dir = new ArrayObject($directReferences);
        $directIterator = $dir->getIterator();

        while ($directIterator->valid()) {
            $indirect = null;
            $direct = $directIterator->current();
            $hash = $this->getHash($direct);

            // Try to get the old direct object reference (if it exists)
            // otherwise, create a new entry
            if (!empty($direct) && $dtoi_old->offsetExists($hash)) {
                $indirect = $dtoi_old->offsetGet($hash);
            }

            if (empty($indirect)) {
                $indirect = $this->getUniqueRandomReference();
            }
            $this->itod->offsetSet($indirect, $direct);
            $this->dtoi->offsetSet($hash, $indirect);
            $directIterator->next();
        }
    }
}
