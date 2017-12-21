<?php
use Includes\Modules\Social\SocialSettingsPage;
?>
<div class="columns">
    <div class="column is-4">
        <div class="footer-menu-section">
            <p class="menu-heading">
                ABOUT US
            </p>
            <?php foreach(getPageChildren('About Us') as $child){ ?>
                <a href="<?= get_permalink($child->ID); ?>" class="footer-submenu-item"><?= $child->post_title; ?></a>
            <?php } ?>
        </div>

        <div class="footer-menu-section">
            <p class="menu-heading is-uppercase">
                SPECIALTIES
            </p>
            <?php foreach(getPageChildren('Conditions') as $child){ ?>
                <a href="<?= get_permalink($child->ID); ?>" class="footer-submenu-item"><?= $child->post_title; ?></a>
            <?php } ?>
        </div>

    </div>
    <div class="column is-4">
        <div class="footer-menu-section">
            <p class="menu-heading is-uppercase">
                PHYSICIAN RESOURCES
            </p>
            <?php foreach(getPageChildren('Physician Resources') as $child){ ?>
                <a href="<?= get_permalink($child->ID); ?>" class="footer-submenu-item"><?= $child->post_title; ?></a>
            <?php } ?>
        </div>

        <div class="footer-menu-section">
            <p class="menu-heading is-uppercase">
                PATIENT RESOURCES
            </p>
            <?php foreach(getPageChildren('Patient Resources') as $child){ ?>
                <a href="<?= get_permalink($child->ID); ?>" class="footer-submenu-item"><?= $child->post_title; ?></a>
            <?php } ?>
        </div>
    </div>
    <div class="column is-4">
        <a href="/make-a-payment/" class="button white-button is-uppercase mb-20">Make a payment</a>
        <p class="menu-heading is-uppercase mb-5">
            CONTACT:
        </p>
        <p class="footer-submenu-item mb-20">
            PO Box 1750, <br>
            Lynn Haven, FL 32444 <br>
            Email: <a href="mailto:thevirtualnephrologist@gmail.com">thevirtualnephrologist@gmail.com</a>
        </p>
        <div class="social">
            <p class="menu-heading mb-5">CONNECT:</p>
            <?php
            $socialLinks = new SocialSettingsPage();
            $socialIcons = $socialLinks->getSocialLinks('svg', 'circle');
            if (is_array($socialIcons)) {
                foreach ($socialIcons as $socialId => $socialLink) {
                    echo '<a class="' . $socialId . '" href="' . $socialLink[0] . '" target="_blank" >' . $socialLink[1] . '</a>';
                }
            }
            ?>
        </div>
    </div>
</div>