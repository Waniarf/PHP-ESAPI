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
 * @author    Linden Darling <linden.darling@jds.net.au>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @version   SVN: $Id$
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * Reference implementation of the CSS codec.
 *
 * @category  OWASP
 *
 * @author    Linden Darling <linden.darling@jds.net.au>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @version   Release: @package_version@
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class OracleCodec extends Codec
{
    /**
     * Public Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function encodeCharacter($immune, $c)
    {
        //detect encoding, special-handling for chr(172) and chr(128) to chr(159)
        // which fail to be detected by mb_detect_encoding()
        $initialEncoding = $this->detectEncoding($c);

        // Normalize encoding to UTF-32
        $_4ByteUnencodedOutput = $this->normalizeEncoding($c);

        // Start with nothing; format it to match the encoding of the string passed
        //as an argument.
        $encodedOutput = mb_convert_encoding('', $initialEncoding);

        // Grab the 4 byte character.
        $_4ByteCharacter = $this->forceToSingleCharacter($_4ByteUnencodedOutput);

        // Get the ordinal value of the character.
        [, $ordinalValue] = unpack('N', $_4ByteCharacter);

        //check if character is an apostrophe
        if ($_4ByteCharacter == $this->normalizeEncoding('\'')) {
            return $encodedOutput . '\'\'';
        }

        return $encodedOutput . chr($ordinalValue);
    }

    /**
     * {@inheritdoc}
     */
    public function decodeCharacter($input)
    {
        if (mb_substr($input, 0, 1, 'UTF-32') === null) {
            // 1st character is null, so return null
            // eat the 1st character off the string and return null
            //todo: this is not neccessary
            $input = mb_substr($input, 1, mb_strlen($input, 'UTF-32'), 'UTF-32');

            return [
                'decodedCharacter' => null,
                'encodedString' => null,
            ];
        }

        // if this is not an encoded character, return null
        if (mb_substr($input, 0, 1, 'UTF-32') != $this->normalizeEncoding("'")) {
            // 1st character is not part of encoding pattern, so return null
            return [
                'decodedCharacter' => null,
                'encodedString' => null,
            ];
        }

        // 1st character is part of encoding pattern...

        // if this is not an encoded character, return null
        if (mb_substr($input, 1, 1, 'UTF-32') != $this->normalizeEncoding("'")) {
            // 1st character is not part of encoding pattern, so return null
            return [
                'decodedCharacter' => null,
                'encodedString' => null,
            ];
        }

        return [
            'decodedCharacter' => $this->normalizeEncoding("'"),
            'encodedString' => $this->normalizeEncoding("''"),
        ];
    }
}
