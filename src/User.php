<?php
/**
 * OWASP Enterprise Security API (ESAPI).
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 *
 * @created 2007
 */

/**
 * The User interface represents an application user or user account. There is quite a lot of information that an
 * application must store for each user in order to enforce security properly. There are also many rules that govern
 * authentication and identity management.
 * <P>
 * A user account can be in one of several states. When first created, a User should be disabled, not expired, and
 * unlocked. To start using the account, an administrator should enable the account. The account can be locked for a
 * number of reasons, most commonly because they have failed login for too many times. Finally, the account can expire
 * after the expiration date has been reached. The User must be enabled, not expired, and unlocked in order to pass
 * authentication.
 *
 * @author Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author Chris Schmidt <chrisisbeef@gmail.com>
 *
 * @since June 1, 2007
 */
interface User
{
    /**
     * @return Locale the locale
     */
    public function getLocale();

    /**
     * @param locale the locale to set
     */
    public function setLocale(Locale $locale);

    /**
     * Adds a role to this user's account.
     *
     * @param string $role The role to add
     *
     * @throws AuthenticationException the authentication exception
     */
    public function addRole($role);

    /**
     * Adds a set of roles to this user's account.
     *
     * @param string[] $newRoles The new roles to add
     *
     * @throws AuthenticationException the authentication exception
     */
    public function addRoles($newRoles);

    /**
     * Sets the user's password, performing a verification of the user's old password, the equality of the two new
     * passwords, and the strength of the new password.
     *
     * @param string $oldPassword  The old password
     * @param string $newPassword1 The new password
     * @param string $newPassword2 The new password - used to verify that the new password was typed correctly
     *
     * @throws AuthenticationException If newPassword1 does not match newPassword2, if oldPassword does not match the
     *                                 stored old password or if the new password does not meet complexity requirements
     * @throws EncryptionException
     */
    public function changePassword($oldPassword, $newPassword1, $newPassword2);

    /**
     * Disable this user's account.
     */
    public function disable();

    /**
     * Enable this user's account.
     */
    public function enable();

    /**
     * Gets this user's account id number.
     *
     * @return int The account id
     */
    public function getAccountId();

    /**
     * Gets this user's account name.
     *
     * @return string the account name
     */
    public function getAccountName();

    /**
     * Gets the CSRF token for this user's current sessions.
     *
     * @return string the CSRF token
     */
    public function getCSRFToken();

    /**
     * Returns the date that this user's account will expire.
     *
     * @return DateTime representing the account expiration time.
     */
    public function getExpirationTime();

    /**
     * Returns the number of failed login attempts since the last successful login for an account. This method is
     * intended to be used as a part of the account lockout feature, to help protect against brute force attacks.
     * However, the implementor should be aware that lockouts can be used to prevent access to an application by a
     * legitimate user, and should consider the risk of denial of service.
     *
     * @return int the number of failed login attempts since the last successful login
     */
    public function getFailedLoginCount();

    /**
     * Returns the last host address used by the user. This will be used in any log messages generated by the processing
     * of this request.
     *
     * @return string the last host address used by the user
     */
    public function getLastHostAddress();

    /**
     * Returns the date of the last failed login time for a user. This date should be used in a message to users after a
     * successful login, to notify them of potential attack activity on their account.
     *
     * @throws AuthenticationException The authentication exception
     *
     * @return DateTime Date of the last failed login
     */
    public function getLastFailedLoginTime();

    /**
     * Returns the date of the last successful login time for a user. This date should be used in a message to users
     * after a successful login, to notify them of potential attack activity on their account.
     *
     * @return DateTime Date of the last successful login
     */
    public function getLastLoginTime();

    /**
     * Gets the date of user's last password change.
     *
     * @return DateTime Date of last password change
     */
    public function getLastPasswordChangeTime();

    /**
     * Gets the roles assigned to a particular account.
     *
     * @return string[] an immutable set of roles
     */
    public function getRoles();

    /**
     * Gets the screen name (alias) for the current user.
     *
     * @return string the screen name
     */
    public function getScreenName();

    /**
     * Adds a session for this user.
     *
     * @param $session The session to associate with this user.
     */
    public function addSession($session);

    /**
     * Removes a session for this User.
     *
     * @param $session The session to remove from being associated with this user.
     */
    public function removeSession($session);

    /**
     * Returns the list of sessions associated with this User.
     *
     * @return array
     */
    public function getSessions();

    /**
     * Increment failed login count.
     */
    public function incrementFailedLoginCount();

    /**
     * Checks if user is anonymous.
     *
     * @return bool TRUE, if user is anonymous
     */
    public function isAnonymous();

    /**
     * Checks if this user's account is currently enabled.
     *
     * @return TRUE, if account is enabled
     */
    public function isEnabled();

    /**
     * Checks if this user's account is expired.
     *
     * @return bool TRUE, if account is expired
     */
    public function isExpired();

    /**
     * Checks if this user's account is assigned a particular role.
     *
     * @param string $role The role for which to check
     *
     * @return bool TRUE, if role has been assigned to user
     */
    public function isInRole($role);

    /**
     * Checks if this user's account is locked.
     *
     * @return bool TRUE, if account is locked
     */
    public function isLocked();

    /**
     * Tests to see if the user is currently logged in.
     *
     * @return bool TRUE, if the user is logged in
     */
    public function isLoggedIn();

    /**
     * Tests to see if this user's session has exceeded the absolute time out based
     * on ESAPI's configuration settings.
     *
     * @return bool TRUE, if user's session has exceeded the absolute time out
     */
    public function isSessionAbsoluteTimeout();

    /**
     * Tests to see if the user's session has timed out from inactivity based
     * on ESAPI's configuration settings.
     *
     * A session may timeout prior to ESAPI's configuration setting due to
     * the servlet container setting for session-timeout in web.xml. The
     * following is an example of a web.xml session-timeout set for one hour.
     *
     * <session-config>
     *     <session-timeout>60</session-timeout>
     * </session-config>
     *
     * @return bool TRUE, if user's session has timed out from inactivity based
     *              on ESAPI configuration
     */
    public function isSessionTimeout();

    /**
     * Lock this user's account.
     */
    public function lock();

    /**
     * Login with password.
     *
     * @param string $password The password
     *
     * @throws AuthenticationException if login fails
     */
    public function loginWithPassword($password);

    /**
     * Logout this user.
     */
    public function logout();

    /**
     * Removes a role from this user's account.
     *
     * @param string role The role to remove
     *
     * @throws AuthenticationException The authentication exception
     */
    public function removeRole($role);

    /**
     * Returns a token to be used as a prevention against CSRF attacks. This token should be added to all links and
     * forms. The application should verify that all requests contain the token, or they may have been generated by a
     * CSRF attack. It is generally best to perform the check in a centralized location, either a filter or controller.
     * See the verifyCSRFToken method.
     *
     * @throws AuthenticationException The authentication exception
     *
     * @return string The new CSRF token
     */
    public function resetCSRFToken();

    /**
     * Sets this user's account name.
     *
     * @param string $accountName the new account name
     */
    public function setAccountName($accountName);

    /**
     * Sets the date and time when this user's account will expire.
     *
     * @param DateTime $expirationTime the new expiration time
     */
    public function setExpirationTime(DateTime $expirationTime);

    /**
     * Sets the roles for this account.
     *
     * @param string[] $roles The new roles
     *
     * @throws AuthenticationException The authentication exception
     */
    public function setRoles($roles);

    /**
     * Sets the screen name (username alias) for this user.
     *
     * @param string $screenName the new screen name
     */
    public function setScreenName($screenName);

    /**
     * Unlock this user's account.
     */
    public function unlock();

    /**
     * Verify that the supplied password matches the password for this user. This method
     * is typically used for "reauthentication" for the most sensitive functions, such
     * as transactions, changing email address, and changing other account information.
     *
     * @param string $password The password that the user entered
     *
     * @throws EncryptionException
     *
     * @return bool TRUE, if the password passed in matches the account's password
     */
    public function verifyPassword($password);

    /**
     * Set the time of the last failed login for this user.
     *
     * @param DateTime $lastFailedLoginTime The date and time when the user just failed to login correctly.
     */
    public function setLastFailedLoginTime(DateTime $lastFailedLoginTime);

    /**
     * Set the last remote host address used by this user.
     *
     * @param string $remoteHost The address of the user's current source host.
     */
    public function setLastHostAddress($remoteHost);

    /**
     * Set the time of the last successful login for this user.
     *
     * @param DateTime $lastLoginTime the date and time when the user just successfully logged in.
     */
    public function setLastLoginTime(DateTime $lastLoginTime);

    /**
     * Set the time of the last password change for this user.
     *
     * @param DateTime $lastPasswordChangeTime The date and time when the user just successfully changed his/her
     *                                         password.
     */
    public function setLastPasswordChangeTime(DateTime $lastPasswordChangeTime);

    /**
     * Returns the hashmap used to store security events for this user. Used by the IntrusionDetector.
     *
     * @return array The hashmap
     */
    public function getEventMap();
}
