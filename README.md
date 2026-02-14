# Orestbida Cookie Consent Plugin

The **Orestbida Cookie Consent** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav) that allows you to manage cookies on your website using the popular JavaScript library [Cookie Consent by Orestbida](https://github.com/orestbida/cookieconsent). This plugin provides a customizable and GDPR-compliant cookie consent banner, enabling users to accept or reject cookies based on their preferences.

## Installation

Installing the Orestbida Cookie Consent plugin can be done in one of three ways: using the Grav Package Manager (GPM), manual installation, or via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [Grav Package Manager (GPM)](https://learn.getgrav.org/cli-console/grav-cli-gpm), navigate to the root of your Grav installation using your terminal and run the following command:

```bash
bin/gpm install orestbida-cookie-consent
```

This will install the plugin into the `/user/plugins` directory of your Grav installation. The plugin files will be located at:

```
/your/site/grav/user/plugins/orestbida-cookie-consent
```

### Manual Installation

To install the plugin manually, follow these steps:

1. Download the latest release of the plugin from [GitHub](https://github.com/pmoreno-rodriguez/grav-plugin-orestbida-cookie-consent/releases).
2. Extract the zip file into the `/user/plugins` directory of your Grav installation.
3. Rename the extracted folder to `orestbida-cookie-consent`.

The plugin files should now be located at:

```
/your/site/grav/user/plugins/orestbida-cookie-consent
```

### Admin Plugin Installation

If you are using the Grav Admin Plugin, you can install the Orestbida Cookie Consent plugin directly from the Admin Panel:

1. Navigate to the `Plugins` section in the Admin Panel.
2. Click the `Add` button.
3. Search for `Orestbida Cookie Consent` and click `Install`.

## Configuration

Before configuring the plugin, you should copy the default configuration file from:

```
user/plugins/orestbida-cookie-consent/orestbida-cookie-consent.yaml
```

to:

```
user/config/plugins/orestbida-cookie-consent.yaml
```

Then, edit the copied file to customize the plugin settings. If you are using the Admin Plugin, the configuration file will be automatically created and saved in the `user/config/plugins/` folder when you save the settings.

### Default Configuration

Here is the default configuration with explanations for each option:

```yaml
enabled: true                  # Enable or disable the plugin.
cdn: true                      # Use the CDN to load the Cookie Consent library.
theme:                         # Choose a theme for the cookie banner (options: default, light-funky, dark-turquoise, elegant).

# Consent Modal Settings
consent_layout:               # Layout for the consent modal (options: box, box_inline, box_wide, cloud, cloud_inline, bar, bar_inline).
consent_position:             # Position of the consent modal (options: top_center, top_left, top_right, bottom_center, bottom_left, bottom_right, middle_center,middle_left, middle_right).
consent_title:                # Title of the consent modal.
consent_description:          # Description in the consent modal.
consent_acceptAllBtn:         # Text for the "Accept All" button.
consent_acceptNecessaryBtn:   # Text for the "Reject All" button.
consent_showPreferencesBtn:   # Text for the "Manage Preferences" button.
consent_footer:               # Footer text for the consent modal (supports Markdown).

# Preferences Modal Settings
preferences_layout:           # Layout for the preferences modal (options: box, bar, bar_wide).
preferences_position:         # Position of the preferences modal (options: left, right).
preferences_title:            # Title of the preferences modal.
preferences_acceptAllBtn:     # Text for the "Accept All" button in the preferences modal.
preferences_acceptNecessaryBtn:   # Text for the "Reject All" button in the preferences modal.
preferences_savePreferencesBtn:   # Text for the "Save Preferences" button.

# Cookie Categories
categories:
  necessary: true              # Enable the "Necessary" category (always enabled and cannot be disabled).
  functionality: false         # Enable the "Functionality" category.
  analytics: false             # Enable the "Analytics" category.
  marketing: false             # Enable the "Marketing" category.

# Sections Content
cookies_sections:
    # For each section, you can set two values:
    title:                    # Title of the section
    description:              # Description for the section

# Advanced Settings
autoshow: true  # Automatically show the consent modal if consent is not valid.
show_delay: 3000 # Set the delay in milliseconds before the cookie consent banner is shown.
hideFromBots: false # Stops the plugin's execution when a bot/crawler is detected, to prevent them from indexing the modal's content.
disablePageInteraction: false # Creates a dark overlay and blocks the page scroll until consent is expressed.
```

### `autoshow` and `show_delay`

- `autoshow` maps directly to CookieConsent's native `autoShow` option.
- The plugin also calls `CookieConsent.show()` after `show_delay` milliseconds.
- This means `show_delay` applies to the plugin-driven display timing.

## Usage

Once the plugin is installed and configured, the cookie consent banner will automatically appear on your website. Users can interact with the banner to accept or reject cookies based on their preferences. The plugin supports multiple cookie categories, allowing you to provide granular control over cookie usage.

### Customizing the Banner

You can customize the appearance and behavior of the cookie consent banner by modifying the configuration options in the `orestbida-cookie-consent.yaml` file. For example:

- Change the theme to match your website's design.
- Adjust the layout and position of the consent modal.
- Customize the text for buttons, titles, and descriptions.

### Adding Custom CSS

If you need further customization, you can add your own CSS styles by overriding the plugin's default styles. Place your custom CSS in a file and include it in your Grav theme.

## Credits

This plugin integrates the [Cookie Consent library](https://github.com/orestbida/cookieconsent) developed by Orest Bida. Special thanks to the creator for providing an excellent open-source solution for cookie consent management.

## To Do

- [ ] Add support for additional languages.
- [ ] Improve accessibility features.
- [ ] Add more customization options for advanced users

Feel free to contribute to this plugin by submitting issues or pull requests on [GitHub](https://github.com/pmoreno-rodriguez/grav-plugin-orestbida-cookie-consent). Your feedback and contributions are greatly appreciated!

