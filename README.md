# contao-themes-net/mate-theme-bundle
mate - exhilarating contao theme for contao cms

license

pdir contao theme licence -> see LICENSE file for more informations

## demo

for demo please visit https://mate.pdir.de

## documentation

for documentation please visit https://docs.contao-themes.net/

## compatible
compatible with Contao >=4.4

## structure

    ./src/Resources
        config = Symfony config (services etc.)
        contao = contao stuff (config, dca etc.)
        public = symlink to web (Images, JS, CSS etc.)
        views  = Templates for Twig

    # Weitere Beispiele für Verzeichnisse in ./src/
    Typ     	                    Verzeichnis
    Commands	                    Command/
    Controllers	                    Controller/
    Service Container Extensions	DependencyInjection/
    Event Listeners	                EventListener/
    Model Klassen	                Model/
    Übersetzungen (Symfony)	        Resources/translations/
    Übersetzungen (Contao)	        Resources/contao/languages/
    Templates (.html5)              Resources/contao/templates/
    Unit-Tests	                    Tests/


## dependencies & licenses

- [Materialize CSS Framework](http://materializecss.com/) | [Github](https://github.com/Dogfalo/materialize) | Code copyright 2017 Materialize. Code released under the MIT license.
- [Contao Theme Helper Bundle](https://github.com/pdir/contao-theme-helper-bundle) by [pdir](https://pdir.de/ "Webdesign für Dresden") | Code copyright by pdir / digital agentur. Code released under LGPL v3.
- [Headroom.js](http://wicky.nillia.ms/headroom.js/) | [Github](https://github.com/WickyNilliams/headroom.js) | Code copyright 2013 by Nick Williams. Code released under the MIT license.
- [Material Icons Inserttag](https://github.com/contao-themes-net/material-icons-inserttag) by [pdir](https://pdir.de/ "Webdesign für Dresden") | Code copyright by pdir / digital agentur. Code released under GNU Lesser General Public License v3.0.


## Install & Documentation

see https://docs.contao-themes.net/

## Configuration

Edit your app/config/parameters.yml file:

    parameters:
      mate_theme.assets.custom_scss: 'my/path/to/_custom.scss'
      mate_theme.assets.custom_variables : 'my/path/to/_custom_variables.scss'

## Other Contao Themes

| [![0.1 Theme](https://contao-themes.net/assets/images/3/0.1_Energy_saving_Contao_Theme_00-1e927a73.jpg)](https://contao-themes.net/theme-detail/zeroone.html) | [![ODD Theme](https://contao-themes.net/assets/images/c/ODD_Exploring_Contao_Theme_05-9e3a18d8.png)](https://contao-themes.net/theme-detail/odd.html) | [![NATURE Theme](https://contao-themes.net/assets/images/6/00_00_naturetheme-605a9391.jpg)](https://contao-themes.net/theme-detail/nature.html) |
|:---:|:---:|:---:|
| **0.1 Theme**  | **ODD Theme**  | **NATURE Theme**  |
