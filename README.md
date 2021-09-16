# WP Plugin Framework

An extensible WordPress Plugin Framework for WordPress Plugin developers.\
Inspired by and coded starting from [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate).

## Features

* The `wp-plugin-framework` directory contains a fully executable WordPress plugin.
* The WP Plugin Framework is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* All classes, functions, and variables are documented so that you know what you need to change.
* The WP Plugin Framework uses a directory/file organization scheme that makes it easy to organize your files following a Model View Controller architecture.

## Installation

The WP Plugin Framework can be installed directly into your plugins folder "as-is".
Assuming that you want to call your plugin `My Custom Plugin`, follow these steps:

* rename the directory `wp-plugin-framework` to `my-custom-plugin`
* rename the file `wp-plugin-framework.php` to `my-custom-plugin.php`
* search and replace `namespace WpPluginFramework` with `namespace MyCustomPlugin`

It's safe to activate the plugin at this point.

## Customization

To have a fully customized plugin you will have to follow these steps:

* rename all the references to text domain `wp-plugin-framework` to `my-custom-plugin`
* change the plugin arguments, within the function get_args(), to suit your needs
* create your assets files in the `assets/` directory
* create your language files in the `languages/` directory
* register all your hooks and customize the Main class `class-main.php` in the `app/` directory
* customize the Activator, Deactivator, Uninstaller classes in the `app/classes/` directory
* write your custom models, controllers and views in the respective directories within the `app/` directory

## Recommended Tools

### i18n (Internationalization) Tools

These are tools that are recommended for providing correct, translatable files:

* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.

## License

The WP Plugin Framework is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

## Important Notes

### Licensing

The WP Plugin Framework is licensed under the GPL v2 or later; however, if you opt to use third-party code that is not compatible with v2, then you may need to switch to using code that is GPL v3 compatible.

For reference, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that covers the Apache 2.0 License used by [Bootstrap](http://twitter.github.io/bootstrap/).
