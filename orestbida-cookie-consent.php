<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Twig\TwigFilter;

/**
 * Class OrestbidaCookieConsentPlugin
 * @category   Extensions
 * @package    Grav\Plugin
 * @author     Pedro Moreno <https://github.com/pmoreno-rodriguez>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @link       https://github.com/pmoreno-rodriguez/grav-plugin-orestbida-cookie-consent
 * 
 * This plugin integrates the "Cookie Consent" library by Orestbida into Grav,
 * allowing website owners to manage cookie consent in compliance with GDPR and other privacy regulations.
 * It provides a customizable banner for users to accept or reject cookies and supports multiple cookie categories.
 */
class OrestbidaCookieConsentPlugin extends Plugin
{
    /**
     * Base path for plugin assets (CSS and JS files).
     */
    private const ASSETS_PATH = 'plugin://orestbida-cookie-consent/assets/';

    /**
     * Twig template used to initialize the cookie consent banner.
     */
    private const INIT_TEMPLATE = 'partials/orestbida-cookieconsent.html.twig';

    /**
     * Current version of the cookieconsent library to use with CDN.
     */
    private const CDN_VERSION = '3.1.0';

    /**
     * Unique marker to avoid duplicate script injection.
     */
    private const UNIQUE_MARKER = 'orestbida-cookie-consent-init';

    /**
     * Track if inline JS was added via assets
     */
    private $inlineJsAdded = false;

    /**
     * Returns a list of events this plugin is subscribed to.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initializes the plugin.
     */
    public function onPluginsInitialized(): void
    {
        // Do not proceed if the plugin is running in the admin panel.
        if ($this->isAdmin()) {
            return;
        }

        // Enable the necessary events for the plugin to work on the frontend.
        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onTwigExtensions' => ['onTwigExtensions', 0],
            'onOutputGenerated' => ['onOutputGenerated', 0],
        ]);
    }

    /**
     * Adds the plugin's template directory to Twig's search paths.
     */
    public function onTwigTemplatePaths(): void
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Adds CSS and JS to the site (will be rendered in head).
     * Also attempts hybrid injection via assets->addInlineJs().
     */
    public function onTwigSiteVariables(): void
    {
        $assets = $this->grav['assets'];
        
        // Validate and get configuration
        $config = $this->validateConfig();

        // Load theme CSS if specified
        $this->loadThemeCSS($assets, $config);
        
        // Load library files (CDN or local)
        $this->loadLibraryFiles($assets, $config);

        // HYBRID INJECTION: Try to inject via assets system
        try {
            $twig = $this->grav['twig'];
            $lang = $this->getCurrentLanguage();

            // Generate the initialization script
            $initScript = $twig->twig->render(self::INIT_TEMPLATE, [
                'config' => $config,
                'lang' => $lang
            ]);

            // Validate script content
            if (!empty(trim($initScript))) {
                // Add inline JS with high priority to execute after library loads
                $assets->addInlineJs($initScript, ['group' => 'bottom', 'priority' => 10]);
                $this->inlineJsAdded = true;
            }
        } catch (\Exception $e) {
            $this->grav['log']->error('CookieConsent Plugin Error (inline injection): ' . $e->getMessage());
        }
    }

    /**
     * FALLBACK: Injects the initialization script before closing body tag
     * if the theme didn't render the inline JS from assets.
     */
    public function onOutputGenerated(): void
    {
        try {
            // Get the current output
            $output = $this->grav->output;

            // Check if scripts are already injected (either via assets or previous fallback)
            if (strpos($output, self::UNIQUE_MARKER) !== false) {
                return;
            }

            // If inline JS was added via assets and the theme rendered it, skip fallback
            if ($this->inlineJsAdded && strpos($output, 'CookieConsent.run') !== false) {
                return;
            }

            // FALLBACK INJECTION: Theme doesn't support {% block bottom %} or didn't render inline JS
            $twig = $this->grav['twig'];
            $config = $this->validateConfig();
            $lang = $this->getCurrentLanguage();

            // Generate the initialization script
            $initScript = $twig->twig->render(self::INIT_TEMPLATE, [
                'config' => $config,
                'lang' => $lang
            ]);

            // Validate script content
            if (empty(trim($initScript))) {
                throw new \RuntimeException('Empty initialization script generated');
            }

            // Wrap the initialization script in script tags with unique marker
            $scriptTag = "<!-- " . self::UNIQUE_MARKER . " -->\n<script>\n" . $initScript . "\n</script>";

            // Inject before closing </body> tag
            if (($bodyPos = strripos($output, '</body>')) !== false) {
                $output = substr_replace($output, $scriptTag . "\n", $bodyPos, 0);
                $this->grav->output = $output;
            } else {
                // Fallback: append at the end if no </body> tag found
                $this->grav->output = $output . $scriptTag;
            }
        } catch (\Exception $e) {
            // Log error but don't break the site
            $this->grav['log']->error('CookieConsent Plugin Error (fallback): ' . $e->getMessage());
        }
    }

    /**
     * Validates and returns plugin configuration.
     * 
     * @return array Validated configuration
     */
    private function validateConfig(): array
    {
        static $cachedConfig = null;
        
        if ($cachedConfig !== null) {
            return $cachedConfig;
        }
        
        $config = $this->config->get('plugins.orestbida-cookie-consent');
        if (empty($config)) {
            throw new \RuntimeException('Missing configuration for plugin orestbida-cookie-consent');
        }

        $cachedConfig = $config;
        return $cachedConfig;
    }

    /**
     * Gets the current language from Grav or returns default.
     * 
     * @return string Language code
     */
    private function getCurrentLanguage(): string
    {
        return $this->grav['language']->getLanguage() ?: 'en';
    }

    /**
     * Loads the theme CSS if specified in configuration.
     * 
     * @param object $assets Grav assets manager
     * @param array $config Plugin configuration
     */
    private function loadThemeCSS($assets, array $config): void
    {
        $theme = $this->config->get('plugins.orestbida-cookie-consent.theme');
        
        if ($theme) {
            $assets->addCss(self::ASSETS_PATH . 'css/cookies_themes/' . $theme . '.css');
        }
    }

    /**
     * Loads the library files from CDN or local path.
     * 
     * @param object $assets Grav assets manager
     * @param array $config Plugin configuration
     */
    private function loadLibraryFiles($assets, array $config): void
    {
        $cdnEnabled = $this->config->get('plugins.orestbida-cookie-consent.cdn');
        
        if ($cdnEnabled) {
            $assets->addCss("https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@" . self::CDN_VERSION . "/dist/cookieconsent.css");
            $assets->addJs("https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@" . self::CDN_VERSION . "/dist/cookieconsent.umd.js");
        } else {
            $assets->addCss(self::ASSETS_PATH . 'css/cookieconsent.css');
            $assets->addJs(self::ASSETS_PATH . 'js/cookieconsent.umd.js');
        }
    }

    /**
     * Adds custom Twig filters to the Twig environment.
     */
    public function onTwigExtensions(): void
    {
        $this->grav['twig']->twig()->addFilter(
            new TwigFilter('escape_quotes', [$this, 'escapeQuotes'])
        );
    }

    /**
     * Escapes double quotes in strings for JavaScript.
     * 
     * @param string $text Text to escape
     * @return string Escaped text
     */
    public function escapeQuotes(?string $text): string
    {
        if ($text === null) {
            return '';
        }
        return str_replace('"', '\\"', $text);
    }
}