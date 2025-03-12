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
     * Adds CSS, JS, and inline scripts to the site.
     */
    public function onTwigSiteVariables(): void
    {
        $twig = $this->grav['twig'];
        $assets = $this->grav['assets'];
        
        // Validate and get configuration
        $config = $this->validateConfig();
        $lang = $this->getCurrentLanguage();

        // Load theme CSS if specified
        $this->loadThemeCSS($assets, $config);
        
        // Load library files (CDN or local)
        $this->loadLibraryFiles($assets, $config);

        // Add initialization script
        $this->addInitScript($twig, $assets, $config, $lang);
    }

    /**
     * Validates and returns plugin configuration.
     * 
     * @return array Validated configuration
     */
    private function validateConfig(): array
    {
        $config = $this->config->toArray();
        
        // Validate required settings (could add more validation logic here)
        if (!isset($config['plugins']['orestbida-cookie-consent'])) {
            throw new \RuntimeException('Plugin configuration not found');
        }
        
        return $config;
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
            $assets->addCss("//cdn.jsdelivr.net/gh/orestbida/cookieconsent@" . self::CDN_VERSION . "/dist/cookieconsent.css");
            $assets->addJs("//cdn.jsdelivr.net/gh/orestbida/cookieconsent@" . self::CDN_VERSION . "/dist/cookieconsent.umd.js", ['group' => 'bottom']);
        } else {
            $assets->addCss(self::ASSETS_PATH . 'css/cookieconsent.css');
            $assets->addJs(self::ASSETS_PATH . 'js/cookieconsent.umd.js', ['group' => 'bottom']);
        }
    }

    /**
     * Adds the initialization script to the page.
     * 
     * @param object $twig Twig environment
     * @param object $assets Grav assets manager
     * @param array $config Plugin configuration
     * @param string $lang Current language
     */
    private function addInitScript($twig, $assets, array $config, string $lang): void
    {
        $assets->addInlineJs(
            $twig->twig->render(self::INIT_TEMPLATE, ['config' => $config, 'lang' => $lang]),
            ['group' => 'bottom']
        );
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
    public function escapeQuotes(string $text): string
    {
        return str_replace('"', '\\"', $text);
    }
}