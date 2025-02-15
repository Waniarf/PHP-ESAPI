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
 * @category  OWASP
 *
 * @author    jah <jah@jahboite.co.uk>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * Reference Implementation of the Sanitizer Interface.
 *
 * PHP version 5.2
 *
 * @category  OWASP
 *
 * @version   1.0
 *
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class DefaultSanitizer implements Sanitizer
{
    private $encoder;

    public function __construct()
    {
        $this->encoder = ESAPI::getEncoder();
    }

    /**
     * Returns valid, "safe" HTML.
     *
     * This implementation uses HTMLPurifier {@link http://htmlpurifier.org}.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return valid, "safe" HTML.
     */
    public function getSanitizedHTML($context, $input)
    {
        $hvr = new HTMLValidationRule('HTML_Validator', $this->encoder);

        return $hvr->sanitize($context, $input);
    }

    /**
     * Returns valid, "safe" email address.
     *
     * This implementation uses a PHP filter {@link http://php.net/manual/en/filter.filters.sanitize.php}.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return valid, "safe" email address.
     */
    public function getSanitizedEmailAddress($context, $input)
    {
        $evr = new EmailAddressValidationRule('EmailAddress_Validator', $this->encoder);

        return $evr->sanitize($context, $input);
    }

    /**
     * Returns valid, "safe" URL.
     *
     * This implementation uses a PHP filter {@link http://php.net/manual/en/filter.filters.sanitize.php}.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return valid, "safe" URL.
     */
    public function getSanitizedURL($context, $input)
    {
        $uvr = new URLValidationRule('URL_Validator', $this->encoder);

        return $uvr->sanitize($context, $input);
    }

    /**
     * Returns valid, "safe" English language word based on the provided guess.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input An array with the unsanitized word and a guess.
     *
     * @return valid, "safe" word.
     */
    public function getSanitizedWord($context, $input)
    {
        $wvr = new WordValidationRule('Word_Validator', $this->encoder);

        return $wvr->sanitize($context, $input);
    }
}
