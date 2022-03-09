<?php

declare(strict_types=1);

/*
 * mate theme for Contao Open Source CMS
 *
 * Copyright (C) 2022 pdir / digital agentur <develop@pdir.de>
 *
 * @package    contao-themes-net/mate-theme-bundle
 * @link       https://github.com/contao-themes-net/mate-theme-bundle
 * @license    pdir contao theme licence
 * @author     Mathias Arzberger <develop@pdir.de>
 * @author     Philipp Seibt <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ContaoThemesNet\MateThemeBundle\Mate;

/**
 * Class NavbarModule.
 *
 * @author Mathias Arzberger <develop@pdir.de>
 */
class NavBarModule extends \Module
{
    /**
     * Display a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE === 'BE') {
            /** @var \BackendTemplate|object $objTemplate */
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.utf8_strtoupper($GLOBALS['TL_LANG']['CTE']['mateTheme']['navbar'][0]).' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    /**
     * Generate the module.
     */
    protected function compile(): void
    {
        if ('type2' === $this->mateNavbarType) {
            $this->Template->setName('mod_mate_navbar_left');
        } else {
            $this->Template->setName('mod_mate_navbar');
        }

        if ('' !== $this->mateNavbar_customTpl) {
            $this->Template->setName($this->mateNavbar_customTpl);
        }

        $this->Template->navbarType = $this->mateNavbarType;
        $this->Template->showLogo = $this->mateShowLogo;
        $this->Template->showSearch = $this->mateShowSearch;
        $this->Template->showMobileMenu = $this->mateShowMobileMenu;
        $this->Template->headline = $this->headline;

        /** @var \PageModel $objPage */
        global $objPage;
        // Set the trail and level
        if ($this->defineRoot && $this->rootPage > 0) {
            $trail = [$this->rootPage];
            $level = 0;
        } else {
            $trail = $objPage->trail;
            $level = $this->levelOffset > 0 ? $this->levelOffset : 0;
        }
        $lang = null;
        $host = null;

        // Overwrite the domain and language if the reference page belongs to a differnt root page (see #3765)
        if ($this->defineRoot && $this->rootPage > 0) {
            $objRootPage = \PageModel::findWithDetails($this->rootPage);
            // Set the language
            if (\Config::get('addLanguageToUrl') && $objRootPage->rootLanguage !== $objPage->rootLanguage) {
                $lang = $objRootPage->rootLanguage;
            }
            // Set the domain
            if ($objRootPage->rootId !== $objPage->rootId && '' !== $objRootPage->domain && $objRootPage->domain !== $objPage->domain) {
                $host = $objRootPage->domain;
            }
        }
        $this->Template->request = ampersand(\Environment::get('indexFreeRequest'));
        $this->Template->skipId = 'skipNavigation'.$this->id;
        $this->Template->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
        $arrItems = $this->getNavigationMenu($trail[$level], 1, $host, $lang);

        // root layout template fallback
        if ('type1' === $this->mateNavbarType) {
            $this->mateRootTpl = 'nav_mate_left';
            $this->mateMobileTpl = 'nav_mate_mobile';
            $this->mateDropdownTpl = '';
            $this->mateDropdownTplLvl3 = '';
            $this->mateDropdownTplLvl4 = '';
        }

        if ('type2' === $this->mateNavbarType) {
            $this->mateRootTpl = 'nav_mate_left';
            $this->mateMobileTpl = 'nav_mate_mobile';
            $this->mateDropdownTpl = '';
            $this->mateDropdownTplLvl3 = '';
            $this->mateDropdownTplLvl4 = '';
        }

        if ('type5' === $this->mateNavbarType || 'type3' === $this->mateNavbarType) {
            $this->mateRootTpl = 'nav_mate_root';
            $this->mateMobileTpl = 'nav_mate_mobile';
            $this->mateDropdownTpl = 'nav_mate_dropdown';
            $this->mateDropdownTplLvl3 = 'nav_mate_dropdown_lvl3';
            $this->mateDropdownTplLvl4 = 'nav_mate_dropdown_lvl4';
        }

        if (\is_array($arrItems) && \count($arrItems) > 0) {
            /** @var \FrontendTemplate|object $objTemplate */
            $objTemplate = new \FrontendTemplate($this->mateRootTpl);
            $objTemplate->items = $arrItems;
            $objTemplate->id = $this->id;
            $this->Template->rootNav = $objTemplate->parse();

            $objTemplate = new \FrontendTemplate($this->mateMobileTpl);
            $objTemplate->items = $arrItems;
            $objTemplate->id = $this->id;
            $this->Template->mobileNav = $objTemplate->parse();

            $strDropdownNav = '';
            $strDropdownNavLvl3 = '';
            $strDropdownNavLvl4 = '';

            if (\is_array($arrItems)) {
                foreach ($arrItems as $item) {
                    if (isset($item['subitems']) && \is_array($item['subitems']) && \count($item['subitems']) > 0) {
                        /** @var \FrontendTemplate|object $objTemplate */
                        $objTemplate = new \FrontendTemplate($this->mateDropdownTpl);
                        $objTemplate->id = $this->id;
                        $objTemplate->itemId = $item['id'];
                        $objTemplate->items = $item['subitems'];
                        $strDropdownNav .= $objTemplate->parse();

                        foreach ($item['subitems'] as $subitem) {
                            if (isset($subitem['subitems']) && \is_array($subitem['subitems']) && \count($subitem['subitems']) > 0) {
                                /** @var \FrontendTemplate|object $objTemplate */
                                $objTemplate = new \FrontendTemplate($this->mateDropdownTplLvl3);
                                $objTemplate->id = $this->id;
                                $objTemplate->itemId = $subitem['id'];
                                $objTemplate->items = $subitem['subitems'];
                                $strDropdownNavLvl3 .= $objTemplate->parse();

                                foreach ($subitem['subitems'] as $subitemLvl4) {
                                    if (isset($subitemLvl4['subitems']) && \is_array($subitemLvl4['subitems']) && \count($subitemLvl4['subitems']) > 0) {
                                        /** @var \FrontendTemplate|object $objTemplate */
                                        $objTemplate = new \FrontendTemplate($this->mateDropdownTplLvl4);
                                        $objTemplate->id = $this->id;
                                        $objTemplate->itemId = $subitemLvl4['id'];
                                        $objTemplate->items = $subitemLvl4['subitems'];
                                        $strDropdownNavLvl4 .= $objTemplate->parse();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $this->Template->dropdownNav = $strDropdownNav;
            $this->Template->dropdownNavLvl3 = $strDropdownNavLvl3;
            $this->Template->dropdownNavLvl4 = $strDropdownNavLvl4;
        }

        if (1 === $this->mateIncludeHeadroom) {
            $this->Template->includeHeadroom = ' includeHeadroom';
        } else {
            $this->Template->includeHeadroom = '';
        }

        if (1 === $this->mateNavbarStuck) {
            $this->Template->stuckNavbar = ' stuckNavbar';
        } else {
            $this->Template->stuckNavbar = '';
        }

        $this->Template->pid = $trail[$level];
        $this->Template->type = static::class;
        $this->Template->cssID = $this->cssID;
    }

    /**
     * Recursively compile the navigation menu and return it as array.
     *
     * @param int    $pid
     * @param int    $level
     * @param string $host
     * @param string $language
     *
     * @return string
     */
    protected function getNavigationMenu($pid, $level = 1, $host = null, $language = null)
    {
        // Get all active subpages
        $objSubpages = \PageModel::findPublishedSubpagesWithoutGuestsByPid($pid, $this->showHidden, $this instanceof \ModuleSitemap);

        if (null === $objSubpages) {
            return '';
        }
        $items = [];
        $groups = [];
        // Get all groups of the current front end user
        if (FE_USER_LOGGED_IN) {
            $this->import('FrontendUser', 'User');
            $groups = $this->User->groups;
        }
        // Layout template fallback
        if ('' === $this->navigationTpl) {
            $this->navigationTpl = 'nav_default';
        }

        /** @var \PageModel $objPage */
        global $objPage;
        // Browse subpages
        foreach ($objSubpages as $objSubpage) {
            // Skip hidden sitemap pages
            if ($this instanceof \ModuleSitemap && 'map_never' === $objSubpage->sitemap) {
                continue;
            }
            $subitems = '';
            $_groups = deserialize($objSubpage->groups);
            // Override the domain (see #3765)
            if (null !== $host) {
                $objSubpage->domain = $host;
            }
            // Do not show protected pages unless a back end or front end user is logged in
            if (!$objSubpage->protected || BE_USER_LOGGED_IN || (\is_array($_groups) && \is_array($groups) && \count(array_intersect($_groups, $groups))) || $this->showProtected || ($this instanceof \ModuleSitemap && 'map_always' === $objSubpage->sitemap)) {
                // Check whether there will be subpages
                if ($objSubpage->subpages > 0 && (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id === $objSubpage->id || \in_array($objPage->id, $this->Database->getChildRecords($objSubpage->id, 'tl_page'), true))))) {
                    $subitems = $this->getNavigationMenu($objSubpage->id, $level, $host, $language);
                }
                $href = null;
                // Get href
                switch ($objSubpage->type) {
                    case 'redirect':
                        $href = $objSubpage->url;

                        if (0 === strncasecmp($href, 'mailto:', 7)) {
                            $href = \StringUtil::encodeEmail($href);
                        }
                        break;

                    case 'forward':
                        if ($objSubpage->jumpTo) {
                            /** @var \PageModel $objNext */
                            $objNext = $objSubpage->getRelated('jumpTo');
                        } else {
                            $objNext = \PageModel::findFirstPublishedRegularByPid($objSubpage->id);
                        }
                        // Hide the link if the target page is invisible
                        if (null === $objNext || !$objNext->published || ('' !== $objNext->start && $objNext->start > time()) || ('' !== $objNext->stop && $objNext->stop < time())) {
                            continue 2;
                        }
                        $href = $objNext->getFrontendUrl();
                        break;

                    default:
                        $href = $objSubpage->getFrontendUrl();
                        break;
                }
                $row = $objSubpage->row();
                $trail = \in_array($objSubpage->id, $objPage->trail, true);
                // Active page
                if (($objPage->id === $objSubpage->id || 'forward' === $objSubpage->type && $objPage->id === $objSubpage->jumpTo) && !$this instanceof \ModuleSitemap && $href === \Environment::get('request')) {
                    // Mark active forward pages (see #4822)
                    $strClass = ('forward' === $objSubpage->type && $objPage->id === $objSubpage->jumpTo ? 'forward'.($trail ? ' trail' : '') : 'active').('' !== $subitems ? ' submenu' : '').($objSubpage->protected ? ' protected' : '').('' !== $objSubpage->cssClass ? ' '.$objSubpage->cssClass : '');
                    $row['isActive'] = true;
                    $row['isTrail'] = false;
                } // Regular page
                else {
                    $strClass = ('' !== $subitems ? 'submenu' : '').($objSubpage->protected ? ' protected' : '').($trail ? ' trail' : '').('' !== $objSubpage->cssClass ? ' '.$objSubpage->cssClass : '');
                    // Mark pages on the same level (see #2419)
                    if ($objSubpage->pid === $objPage->pid) {
                        $strClass .= ' sibling';
                    }
                    $row['isActive'] = false;
                    $row['isTrail'] = $trail;
                }
                $row['subitems'] = $subitems;
                $row['class'] = trim($strClass);
                $row['title'] = specialchars($objSubpage->title, true);
                $row['pageTitle'] = specialchars($objSubpage->pageTitle, true);
                $row['link'] = $objSubpage->title;
                $row['href'] = $href;
                $row['nofollow'] = 0 === strncmp($objSubpage->robots, 'noindex,nofollow', 16);
                $row['target'] = '';
                $row['description'] = str_replace(["\n", "\r"], [' ', ''], $objSubpage->description);
                // Override the link target
                if ('redirect' === $objSubpage->type && $objSubpage->target) {
                    $row['target'] = 'xhtml' === $objPage->outputFormat ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
                }
                $items[] = $row;
            }
        }
        // Add classes first and last
        if (!empty($items)) {
            $last = \count($items) - 1;
            $items[0]['class'] = trim($items[0]['class'].' first');
            $items[$last]['class'] = trim($items[$last]['class'].' last');
        }

        return $items;
    }
}
