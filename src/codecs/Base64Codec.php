<?php
/**
 * OWASP Enterprise Security API (ESAPI).
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * PHP version 5.2
 *
 * @category  OWASP
 *
 * @author    Martin Reiche <martin.reiche.ka@gmail.com>
 * @author    jah <jah@jahboite.co.uk>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @version   SVN: $Id$
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * Reference implementation of the base 64 codec.
 *
 * @category  OWASP
 *
 * @author    Martin Reiche <martin.reiche.ka@gmail.com>
 * @author    jah <jah@jahboite.co.uk>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @version   Release: @package_version@
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class Base64Codec extends Codec
{
    /**
     * Public Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Encodes the input string to Base64.
     *
     * The output is wrapped at 76 characters by default, but this behaviour may
     * be overridden by supplying a value of FALSE for the $wrap
     * parameter.
     *
     * @param string $input The input string to be encoded
     * @param bool   $wrap  if should wrap output
     *
     * @return string the encoded string
     */
    public function encode($input, $wrap = true)
    {
        $encoded = base64_encode($input);

        if ($wrap === false) {
            return $encoded;
        }

        // wrap encoded string into lines of not more than 76 characters
        $detectedCharacterEncoding = Codec::detectEncoding($encoded);
        $wrapped = '';
        $limit = mb_strlen($encoded, $detectedCharacterEncoding);
        $index = 0;
        while ($index < $limit) {
            if ($wrapped != '') {
                $wrapped .= "\r\n";
            }
            $wrapped .= mb_substr($encoded, $index, 76);
            $index += 76;
        }

        return $wrapped;
    }

    /**
     * Encodes a single character to Base64.
     *
     * @param string $immune: not used, but needs to be here to be compatible with Codec::encodeCharacter
     * @param string $input   the character to encode
     *
     * @return string the base64 encoded character
     */
    public function encodeCharacter($immune = '', $c)
    {
        $detectedCharacterEncoding = Codec::detectEncoding($c);
        $c = mb_substr(
            $c, 0, 1,
            $detectedCharacterEncoding
        );

        return $this->encode($c, false);
    }

    /**
     * Decodes the given input string from Base64 to plain text.
     *
     * @param string $input The base64 encoded input string
     *
     * @return string the decoded string
     */
    public function decode($input)
    {
        return base64_decode($input);
    }

    /**
     * Decodes a character from Base64 to plain text.
     *
     * @param string $input The character to decode
     *
     * @return string the decoded character
     */
    public function decodeCharacter($input)
    {
        return $this->decode($input);
    }
}
