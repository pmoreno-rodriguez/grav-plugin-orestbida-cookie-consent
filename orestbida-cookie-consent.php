<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Twig\TwigFilter;

/**
 * Class OrestbidaCookieConsentPlugin
 * 
 * This plugin integrates the "Cookie Consent" library by Orestbida into Grav,
 * allowing website owners to manage cookie consent in compliance with GDPR and other privacy regulations.
 * It provides a customizable banner for users to accept or reject cookies and supports multiple cookie categories.
 * 
 * @package Grav\Plugin
 */
class OrestbidaCookieConsentPlugin extends Plugin
{
    /**
     * Base path for plugin assets (CSS and JS files).
     *
     * @var string
     */
    private $assetsPath = 'plugin://orestbida-cookie-consent/assets/';

    /**
     * Twig template used to initialize the cookie consent banner.
     *
     * @var string
     */
    private $initTemplate = 'partials/orestbida-cookieconsent.html.twig';

    /**
     * Returns a list of events this plugin is subscribed to.
     *
     * The plugin listens to the `onPluginsInitialized` event to initialize itself.
     * 
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initializes the plugin.
     *
     * This method checks if the plugin is running in the admin panel. If so, it stops execution.
     * Otherwise, it enables the necessary events for the plugin to function on the frontend.
     */
    public function onPluginsInitialized()
    {
        // Do not proceed if the plugin is running in the admin panel.
        if ($this->isAdmin()) {
            return;
        }

        // Enable the necessary events for the plugin to work on the frontend.
        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0], // Add plugin templates to Twig paths.
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0], // Add CSS, JS, and inline scripts.
            'onTwigExtensions' => ['onTwigExtensions', 0], // Add custom Twig filters.
        ]);
    }

    /**
     * Adds the plugin's template directory to Twig's search paths.
     *
     * This allows Twig to locate and render the plugin's templates.
     */
    public function onTwigTemplatePaths()
    {
        // Add the plugin's templates directory to Twig's paths.
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Adds CSS, JS, and inline scripts to the site.
     *
     * This method loads the necessary assets for the cookie consent banner, including:
     * - CSS themes for the banner.
     * - The Cookie Consent library (either from a CDN or local files).
     * - Inline JavaScript to initialize the banner with user-defined settings.
     */
    public function onTwigSiteVariables()
    {
        $twig = $this->grav['twig'];
        $assets = $this->grav['assets'];
        $config = $this->config->toArray();

        // Get plugin configuration options.
        $cdnEnabled = $this->config->get('plugins.orestbida-cookie-consent.cdn');
        $theme = $this->config->get('plugins.orestbida-cookie-consent.theme');
        $lang = $this->grav['language']->getLanguage() ?: 'en'; // Default to English if no language is set.

        // Load the selected theme CSS if a theme is specified.
        if ($theme) {
            $assets->addCss($this->assetsPath . 'css/cookies_themes/' . $theme . '.css');
        }

        // Load the Cookie Consent library from a CDN or local files based on configuration.
        if ($cdnEnabled) {
            $assets->addCss("//cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.css");
            $assets->addJs("//cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.umd.js", ['group' => 'bottom']);
        } else {
            $assets->addCss($this->assetsPath . 'css/cookieconsent.css');
            $assets->addJs($this->assetsPath . 'js/cookieconsent.umd.js', ['group' => 'bottom']);
        }

        // Render the initialization script using Twig and pass configuration and language variables.
        $assets->addInlineJs(
            $twig->twig->render($this->initTemplate, ['config' => $config, 'lang' => $lang]),
            ['group' => 'bottom'] // Ensure the script is added before the closing </body> tag.
        );
    }

    /**
     * Adds custom Twig filters to the Twig environment.
     *
     * This method registers a custom Twig filter (`escape_quotes`) that escapes double quotes
     * in strings, which is useful for embedding text in JavaScript.
     */
    public function onTwigExtensions()
    {
        // Add a custom Twig filter to escape double quotes in strings.
        $this->grav['twig']->twig()->addFilter(
            new TwigFilter('escape_quotes', function ($text) {
                return str_replace('"', '\\"', $text); // Escape double quotes for JavaScript compatibility.
            })
        );
    }
}