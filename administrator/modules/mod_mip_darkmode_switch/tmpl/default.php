<?php

/**
 * @package     MIP_DarkmodeSwitch.Administrator
 * @subpackage  mod_mip_darkmode_switch
 *
 * @copyright   (C) 2024 Mahmudul Islam Prakash <prakash.a7x@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * Reference link https://it-networks.de/dev-blog/tutorial-joomla-5-dark-mode-switcher-modul-erstellen-backend
 */

defined('_JEXEC') or die;
?>
<!-- Dark Mode Toggle Button -->
<button type="button" class="header-item-content dark-mode-toggle-button" style="border: none;">
    <span class="header-item-icon">
        <span style="margin: 3px; font-size: 1.2rem; transition: all .6s ease;"><i class="fa fa-sun"></i></span>
    </span>
    <span class="header-item-text"></span>
</button>

<!-- Dark Mode Toggle Script -->
<script>
    Joomla = window.Joomla || {};

    ((Joomla, document) => {
        'use strict';

        // Run script only once
        if (typeof window.joomlaDarkMode !== "undefined") return;

        // Initial settings
        let isDarkMode = window.joomlaDarkMode = (getDarkModeLocalStorage() === "true");

        const jVersion = "<?php echo JVERSION; ?>";
        const isNewerVersion = isVersionGreaterThan(jVersion);

        // Set initial state in local storage
        setDarkModeLocalStorage(isDarkMode);

        // Update the first visible button to avoid flickering
        updateDarkModeToggleButton(document.querySelector("button.dark-mode-toggle-button"), isDarkMode);

        // Apply dark mode settings
        applyDarkMode(isDarkMode);

        // Function to update the dark mode toggle button
        function updateDarkModeToggleButton(button, isDarkMode) {
            const icon = button.querySelector(".header-item-icon > span");
            const text = button.querySelector(".header-item-text");

            if (isDarkMode) {
                setDarkModeIconAndText(icon, text, "fa-moon", "rgb(31, 48, 71)", " Dark Mode");
            } else {
                setDarkModeIconAndText(icon, text, "fa-sun", "", "Light Mode");
            }
        }

        // Function to set dark mode icon and text
        function setDarkModeIconAndText(icon, text, iconName, backgroundColor, buttonText) {
            icon.innerHTML = `<i class="fa ${iconName}"></i>`;
            icon.style.backgroundColor = backgroundColor;
            text.innerHTML = buttonText;
        }

        // Function to apply dark mode settings
        function applyDarkMode(isDarkMode) {
            if (isNewerVersion) {
                if (isDarkMode) {
                    updateMediaRuleForDarkMode();
                } else {
                    updateMediaRuleForLightMode();
                }
            } else {
                for (const styleSheet of document.styleSheets) {
                    for (let i = styleSheet.cssRules.length - 1; i >= 0; i--) {
                        let rule = styleSheet.cssRules[i].media;
    
                        if (typeof rule !== "undefined" && rule.mediaText.includes("prefers-color-scheme")) {
                            if (isDarkMode) {
                                updateMediaRuleForDarkMode(rule);
                            } else {
                                updateMediaRuleForLightMode(rule);
                            }
                        }
                    }
                }
            }
        }

        // Function to update media rule for dark mode
        function updateMediaRuleForDarkMode(rule) {
            if (isNewerVersion) {
                $('html').attr('data-bs-theme', 'dark');
                $('html').attr('data-color-scheme', 'dark');
            } else {
                if (!rule.mediaText.includes("(prefers-color-scheme: light)")) rule.appendMedium("(prefers-color-scheme: light)");
                if (!rule.mediaText.includes("(prefers-color-scheme: dark)")) rule.appendMedium("(prefers-color-scheme: dark)");
                if (rule.mediaText.includes("original")) rule.deleteMedium("original-prefers-color-scheme");
            }
        }

        // Function to update media rule for light mode
        function updateMediaRuleForLightMode(rule) {
            if (isNewerVersion) {
                $('html').attr('data-bs-theme', 'light');
                $('html').attr('data-color-scheme', 'light');
            } else {
                rule.appendMedium("original-prefers-color-scheme");
                if (rule.mediaText.includes("(prefers-color-scheme: light)")) rule.deleteMedium("(prefers-color-scheme: light)");
                if (rule.mediaText.includes("(prefers-color-scheme: dark)")) rule.deleteMedium("(prefers-color-scheme: dark)");
            }
        }

        // Set local storage state
        function setDarkModeLocalStorage(state) {
            localStorage.setItem("joomlaDarkMode", state);
        }

        // Get local storage state
        function getDarkModeLocalStorage() {
            return localStorage.getItem("joomlaDarkMode");
        }

        // Compare Joomla version
        function isVersionGreaterThan(version, compareTo = '5.0.3') {
            const versionParts = version.split('.').map(Number);
            const compareToParts = compareTo.split('.').map(Number);

            for (let i = 0; i < versionParts.length; i++) {
                if (versionParts[i] > compareToParts[i]) {
                    return true;
                } else if (versionParts[i] < compareToParts[i]) {
                    return false;
                }
            }

            return false; // If versions are equal
        }

        // Update all "Dark Mode Toggle" buttons after DOMContentLoaded
        document.addEventListener('DOMContentLoaded', () => {
            const darkModeToggleButtons = document.querySelectorAll("button.dark-mode-toggle-button");

            darkModeToggleButtons.forEach((button) => {
                // Update button state
                updateDarkModeToggleButton(button, isDarkMode);

                // Set event listeners for all "dark-mode" toggle buttons on click and update local storage
                button.addEventListener("click", () => {
                    isDarkMode = window.joomlaDarkMode = !isDarkMode;
                    setDarkModeLocalStorage(isDarkMode);

                    // Update all dark mode toggle buttons
                    darkModeToggleButtons.forEach((btn) => updateDarkModeToggleButton(btn, isDarkMode));

                    // Update the CSS styles based on dark mode preference
                    applyDarkMode(isDarkMode);
                });
            });
        });

        $(document).ready(function() {
            applyDarkMode(isDarkMode);
        });
    })(Joomla, document);
</script>
