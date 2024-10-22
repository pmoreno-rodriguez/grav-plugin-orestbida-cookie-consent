<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Twig\TwigFilter;


/**
 * Class OrestbidaCookieConsentPlugin
 * @package Grav\Plugin
 */
class OrestbidaCookieConsentPlugin extends Plugin
{
    
    /**
     * Base assets path
     *
     * @var string
     */
    private $assetsPathCss = 'plugin://orestbida-cookie-consent/assets/css/';
    private $assetsPathJs = 'plugin://orestbida-cookie-consent/assets/js/';
    
    /**
     * Init cookie-consent banner template
     *
     * @var string
     */
    private $initTemplate = 'partials/orestbida-cookieconsent.html.twig';
    
    
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */

    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onTwigExtensions' => ['onTwigExtensions', 0],
        ]);
    }

    /**
     * [onTwigTemplatePaths] Add twig paths to plugin templates.
     */
    public function onTwigTemplatePaths()
    {
       // Push own templates to twig paths
       array_push($this->grav['twig']->twig_paths,
        __DIR__ . '/templates');
    }
    /**
     * Add plugin CSS and JS files to the grav assets.
     */
    public function onTwigSiteVariables()
    {
        
        $twig = $this->grav['twig'];
        $assets = $this->grav['assets'];
        $config = $this->config->toArray();

        $cdnEnabled = $this->config->get('plugins.orestbida-cookie-consent.cdn');
        $theme = $this->config->get('plugins.orestbida-cookie-consent.theme');
        
        $lang = $this->grav['language']->getLanguage() ?: 'en';   
        
        if ($theme) {
            $assets->addCss($this->assetsPathCss . 'cookies_themes/' . $theme . '.css');
        }
        
        if ($cdnEnabled) {
            $assets->addCss("//cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.css");
            $assets->addJs("//cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.umd.js", ['group' => 'bottom']);
        } else {
            $assets->addCss($this->assetsPathCss . 'cookieconsent.css');
            $assets->addJs($this->assetsPathJs . 'cookieconsent.umd.js', ['group' => 'bottom']);
        }

        // Agregamos el JavaScript embebido con Twig y pasamos las variables
        $assets->addInlineJs(
            $twig->twig->render(
                $this->initTemplate,
                [
                    'config' => $config,
                    'lang' => $lang // Aquí agregas la variable 'lang'
                ]
            ),
            ['group' => 'bottom'] // Esto asegura que se agregue antes del cierre de <body>
        );        


        }

    public function onTwigExtensions()
    {
        $twig = $this->grav['twig'];

        $twig->twig()->addFilter(
            new TwigFilter('escape_quotes', function ($text) {
                return str_replace('"', '\\"', $text);  // Replace double quotes with escaped quotes
            })
        );
    }
}
