<?php

declare(strict_types=1);

namespace App\core\traits;

/**
 * Trait for handling Login and Logout logic in forms.
 *
 * The LoginHelperTrait assumes the calling class has:
 * - property $this->debug (Debug|CustomDebug instance)
 * - Includes the static Config class via `use App\core\common\Config`
 * - Has access to a BASE_URL constant
 */
trait LoginHelperTrait
{
    /**
     * Initializes the session for login processing.
     *
     * Ensures that the session is started.
     *
     * @return void
     */
    protected function sessionSetup(): void
    {
        // Start the session for the login form
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Fetches login-related configuration.
     *
     * Retrieves the login configuration from `Config`, with a fallback to an empty array.
     *
     * @param string|null $configKey [optional] The key to retrieve specific configuration data.
     *                                Defaults to 'allowedRedirects'.
     *
     * @return array The login configuration.
     */
    protected function fetchLoginConfig(?string $configKey = null): array
    {
        // NOTE: This method assumes the Config class is used in your app.
        // If not, add it or be prepared to debug like a warrior. 🛡️⚔️

        // assign configKey's default
        $configKey = $configKey ?? 'allowedRedirects';

        // Fetch the login configuration from Config
        $config = $this->fetchConfig('login_config');

        // Extract allowed redirects, with a fallback to an empty array
        return $config[$configKey] ?? [];
    }

    /**
     * Fetches a specific configuration file by name.
     *
     * @param string $configName The configuration file to fetch.
     *
     * @return array The configuration section, or an empty array if not found.
     *
     * @throws \RuntimeException If the Config class is not available.
     */
    protected function fetchConfig(string $configName): array
    {
        // NOTE: This method assumes the Config class is used in your app.
        // If not, add it or be prepared to debug like a warrior. 🛡️⚔️

        // Verify that Config is available
        if (!class_exists(\App\core\common\Config::class)) {
            throw new \RuntimeException("Config class not found. Ensure it's included in your application.");
        }
        // Return the config, with a fallback to an empty array
        return \App\core\common\Config::get($configName) ?? [];
    }

    /**
     * Validates the user's login status and redirects to login if not authenticated.
     *
     * Checks the session for login data and redirects the user to the login page
     * if they are not logged in.
     *
     * @param string $redirect The relative URL path for redirection if the user is not authenticated.
     *
     * @return bool Returns true if the user is authenticated. Otherwise, it redirects and exits.
     */
    protected function checkLoginStatus(string $redirect): bool
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Login", "checkLoginStatus");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($redirect, "{$debugHeading} -- redirect");

        // Verify session status and redirect to login if unset
        if (!isset($_SESSION['login_data'])) {
            header('Location: ' . BASE_URL . $redirect);
            exit();
        }
        return true;
    }

    /**
     * Redirects the user to the designated redirect path if set and valid.
     *
     * Constructs a URL using the BASE_URL constant and performs a redirection.
     * If debug mode is enabled, it opens the redirect in a new browser tab/window.
     *
     * @param string $redirect The relative URL for redirection.
     *
     * @return void
     */
    protected function redirectLoggedInUser(string $redirect): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Login", "redirectLoggedInUser");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($redirect, "{$debugHeading} -- redirect");

        // Handle the redirection
        $url = BASE_URL . $redirect;
        $this->debug->debugVariable($url, "{$debugHeading} -- url");
        if ($this->debug->isDebugMode()) {
            // Open redirect url in a new tab/window
            echo "<script>window.open('" . htmlspecialchars($url) . "', '_blank');</script>";
        } else {
            if ($url) {
                // Redirect to the validated URL
                header("Location: " . $url);
                exit;
            } else {
                // Redirect to the default destination
                header("Location: /");
            }
        }
        exit;
    }

    /**
     * Cleans up session data after login processing.
     *
     * Clears all session variables and destroys the session. This method ensures
     * no residual data remains after the login or logout process.
     *
     * @return void
     */
    protected function sessionCleanup(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Logout", "sessionCleanup");
        $this->debug->debug($debugHeading);

        // Log the current session data for debugging
        $this->debug->debugVariable($_SESSION, "{$debugHeading} -- _SESSION before unset");

        // Clear and destroy the session if it is active
        $this->debug->debug("Logout: Unset and destroy session.");
        session_unset();
        session_destroy();

        // Log session status after clearing for confirmation
        $this->debug->debug("Logout: Session successfully cleared.");
    }
}
