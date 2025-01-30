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
theme: default                 # Choose a theme for the cookie banner (options: default, light-funky, dark-turquoise, elegant).

# Consent Modal Settings
consent_layout: block          # Layout for the consent modal (options: box, box_inline, box_wide, cloud, cloud_inline, bar, bar_inline).
consent_position: bottom_right # Position of the consent modal (options: top_center, top_left, top_right, bottom_center, bottom_left, bottom_right, middle_center, middle_left, middle_right).
consent_title: "We use cookies!" # Title of the consent modal.
consent_description: "This website uses cookies to ensure you get the best experience on our website." # Description in the consent modal.
consent_acceptAllBtn: "Accept all" # Text for the "Accept All" button.
consent_acceptNecessaryBtn: "Reject all" # Text for the "Reject All" button.
consent_showPreferencesBtn: "Manage preferences" # Text for the "Manage Preferences" button.
consent_footer: ""             # Footer text for the consent modal (supports Markdown).

# Preferences Modal Settings
preferences_layout: box        # Layout for the preferences modal (options: box, bar, bar_wide).
preferences_position: left     # Position of the preferences modal (options: left, right).
preferences_title: "Cookie Preferences" # Title of the preferences modal.
preferences_acceptAllBtn: "Accept all" # Text for the "Accept All" button in the preferences modal.
preferences_acceptNecessaryBtn: "Reject all" # Text for the "Reject All" button in the preferences modal.
preferences_savePreferencesBtn: "Save preferences" # Text for the "Save Preferences" button.

# Cookie Categories
categories:
  necessary: true              # Enable the "Necessary" category (always enabled and cannot be disabled).
  functionality: false         # Enable the "Functionality" category.
  analytics: false             # Enable the "Analytics" category.
  marketing: false             # Enable the "Marketing" category.

# Sections Content
cookies_sections:
  usage:
    title: "Usage"             # Title for the "Usage" section.
    description: "This website uses cookies to ensure you get the best experience." # Description for the "Usage" section.
  necessary:
    title: "Necessary Cookies" # Title for the "Necessary Cookies" section.
    description: "These cookies are essential for the website to function properly." # Description for the "Necessary Cookies" section.
  functionality:
    title: "Functionality Cookies" # Title for the "Functionality Cookies" section.
    description: "These cookies enable additional functionality like remembering preferences." # Description for the "Functionality Cookies" section.
  analytics:
    title: "Analytics Cookies" # Title for the "Analytics Cookies" section.
    description: "These cookies help us analyze how visitors use the website." # Description for the "Analytics Cookies" section.
  marketing:
    title: "Marketing Cookies" # Title for the "Marketing Cookies" section.
    description: "These cookies are used to deliver personalized advertisements." # Description for the "Marketing Cookies" section.
  more_info:
    title: "More Information"  # Title for the "More Information" section.
    description: "For more details about our cookie policy, please visit our [Privacy Policy](#)." # Description for the "More Information" section.
```

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

