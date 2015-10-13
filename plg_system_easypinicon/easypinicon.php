<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 *
 * @author Craig Phillips
 * @copyright Copyright © 2015 Craig Phillips Pty Ltd - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE file
 *
 * This plugin is based off the CPPL Skeleton Plugin which you can find on GitHub to
 * build your own Joomla plugins. https://github.com/cppl/Skeleton-Plugin-for-Joomla
 *
 */

class plgSystemEasySafari extends JPlugin
{
    protected $weAreDoingIt;

    /**
     * @access      public
     * @param       object $subject The object to observe
     * @param       array $config An array that holds the plugin configuration
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);

        $this->weAreDoingIt = $this->areWeDoingThis();
    }

    /**
     * There is no generic method to add a tag to the head but we can do it manually.
     *
     * @return bool
     */
    function onBeforeCompileHead()
    {
        // We only work for HTML
        /* @var JDocumentHTML $doc */
        $doc = JFactory::getDocument();
        if($doc->getType() != 'html') {
            return true;
        }

        if ($this->weAreDoingIt) {
            // Build our tag...
            $link = $this->_pintab_link();

            if (!empty($link) && $link != '') {
                // Get current document and inject script
                $doc->addCustomTag($link);
            }

            // Only Do This once!
            $this->weAreDoingIt = false;
        }

        return true;
    }

    private function _pintab_link()
    {
        $logoFile = $this->params->get('pin_icon_file', false);
        $color    = $this->params->get('pin_icon_color', 'black');

        if($logoFile && $logoFile != "-1") {
            $link = <<<link
<link rel="mask-icon" href="/images/$logoFile" color="$color">
link;
        } else {
            $link = '';
        }

        return $link;
    }

    /**
     * A simple check to makes sure the plugin is installed correctly before
     * using the helper file functions (it also loads the helper file :))
     *
     * @return bool
     */
    private function areWeDoingThis() {
        // Check the installation
        $path_to_helper = JPATH_PLUGINS . '/system/easysafari/easysafarihelper.php';

        if (file_exists($path_to_helper)) {
            // Yes! Now lets get the helper
            require_once $path_to_helper;

            if (class_exists('EasySafariHelper')) {
                // Looks like the real helper, so we're good to go...
                return true;
            }
        }

        // Ok if we got here something went wrong.
        return false;
    }
}

