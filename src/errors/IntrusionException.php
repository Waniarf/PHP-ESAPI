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
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @version   SVN: $Id$
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * An IntrusionException should be thrown anytime an error condition arises that
 * is likely to be the result of an attack in progress. IntrusionExceptions are
 * handled specially by the IntrusionDetector, which is equipped to respond by
 * either specially logging the event, logging out the current user, or invalidating
 * the current user's account.
 * <p>
 * Unlike other exceptions in the ESAPI, the IntrusionException is a
 * RuntimeException so that it can be thrown from anywhere and will not require a
 * lot of special exception handling.
 *
 * @category  OWASP
 *
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @version   Release: @package_version@
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class IntrusionException extends Exception
{
    protected $logMessage; // Message to be sent to the log

    /**
     * Instantiates a new intrusion exception.
     *
     * @param string $userMessage The message displayed to the user
     * @param string $logMessage  the message logged
     *
     * @return does not return a value.
     */
    public function __construct($userMessage = '', $logMessage = '')
    {
        parent::__construct($userMessage);

        $this->logMessage = $logMessage;
    }

    /**
     * Returns a String containing a message that is safe to display to users.
     *
     * @return string a String containing a message that is safe to display to users
     */
    public function getUserMessage()
    {
        return $this->getMessage();
    }

    /**
     * Returns a String that is safe to display in logs, but probably not to users.
     *
     * @return string a String containing a message that is safe to display in
     *                logs, but probably not to users
     */
    public function getLogMessage()
    {
        return $this->logMessage;
    }
}
