<?php
/**
 * OWASP Enterprise Security API (ESAPI).
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 *
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Mike Boberski <boberski_michael@bah.com>
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
 * Use this ESAPI security control to call command-line operating
 * system functions.
 *
 * The idea behind this interface is to run an OS command with reduced
 * security risk.
 *
 * @category  OWASP
 *
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @author    Linden Darling <linden.darling@jds.net.au>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 *
 * @version   Release: @package_version@
 *
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface Executor
{
    /**
     * Invokes the specified executable with default workdir and not logging
     * parameters.
     *
     * @param string $executable The command to execute
     * @param array  $params     the parameters of the command being executed
     *
     * @return does not return a value.
     */
    public function executeSystemCommand($executable, $params);

    /**
     * Executes a system command after checking that the executable exists and
     * escaping all the parameters to ensure that injection is impossible.
     * Implementations must change to the specified working
     * directory before invoking the command.
     *
     * note: this is PHP's equivalent to ESAPI4JAVA's overloaded
     * executeSystemCommand($executable, $params, $workdir, $codec, $logParams)
     * note: the codec argument has been eliminated from this implementation since
     * PHP's escapeshellcmd function does enough to not require explicit OS codecs
     *
     * @param string $executable The command to execute
     * @param array  $params     the parameters of the command being executed
     * @param string $workdir    the working directory
     * @param bool   $logParams  use FALSE if any parameters contains sensitive or
     *                           confidential information. (this is an ESAPI 2.0
     *                           feature)
     *
     * @throws ExecutorException the service exception
     *
     * @return string the output of the command being run
     */
    public function executeSystemCommandLonghand($executable, $params, $workdir, $logParams);
}
