# v2.2.1
## 11/23/2025

1. [](#improved)
    * Refactored `onTwigSiteVariables()` method to include hybrid injection.
    * Optimized `onOutputGenerated()` to detect if script was already rendered before applying fallback.
2. [](#bugfix)
    * Fixed Twig template error that unnecessarily redefined `config` variable, causing plugin not to load properly.

# v2.2.0
## 10/13/2025

1. [](#improved)
    * Improved template security with consistent input sanitization across all user-configurable content
    * Added `onOutputGenerated` hook to ensure plugin works with all theme configurations
    * Fixed CDN URLs to use HTTPS instead of HTTP

# v2.1.0
##  03/22/2025

1. [](#improved)
    * Updated some translations
    * Replaced elements type fields with text and textarea fields in the plugin blueprints to improve usability and simplify content management
    * Added required fields to improve plugin functionality
2. [](#bugfix)
    * Fix: Handle null values in escapeQuotes function to prevent type errors

# v2.0.1
##  03/12/2025

1. [](#improved)
    * Updated CDN version

# v2.0.0
##  03/12/2025

1. [](#new)
    * Upgraded to the latest version of Orestbida Cookie Consent library (v3.1.0)
    * Added option `autoShow`: Automatically show the consent modal if consent is not valid
    * Added option `show_delay` to set the delay in milliseconds before the cookie consent banner is shown
    * Added option `hideFromBots`: Stops the plugin's execution when a bot/crawler is detected, to prevent them from indexing the modal's content
    * Added option `disablePageInteraction`: Creates a dark overlay and blocks the page scroll until consent is expressed
2. [](#improved)
    * Refactored the code to make it more maintainable and easier to extend in the future
    * Updated documentation in README

# v1.0.0
##  01/30/2025

1. [](#new)
    * ChangeLog started...
