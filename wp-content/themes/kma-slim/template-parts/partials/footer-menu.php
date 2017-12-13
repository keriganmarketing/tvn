<?php
use Includes\Modules\Social\SocialSettingsPage;
?>
<div class="columns">
    <div class="column is-4">
        <p class="menu-heading">
            ABOUT US
        </p>
            <a href="/meet-dr-rifai" class="footer-submenu-item">Meet Dr. Rifai</a>
            <a href="/value-of-virtual-consults" class="footer-submenu-item mb-20">Value of Virtural Consults</a>
        <p class="menu-heading is-uppercase">
            SPECIALTIES
        </p>
            <a href="#" class="footer-submenu-item">Hypertension</a>
            <a href="#" class="footer-submenu-item">Diabetes</a>
            <a href="#" class="footer-submenu-item">Kidney Disease</a>
            <a href="#" class="footer-submenu-item">Kidney Transplant</a>
            <a href="#" class="footer-submenu-item">Dialysis</a>
            <a href="#" class="footer-submenu-item mb-20">Congestive Heart Failure (CHF)</a>
    </div>
    <div class="column is-4">
        <p class="menu-heading is-uppercase">
            PHYSICIAN RESOURCES
        </p>
            <a href="#" class="footer-submenu-item">PCP</a>
            <a href="#" class="footer-submenu-item">Nephrologists</a>
            <a href="#" class="footer-submenu-item">Cardiologists</a>
            <a href="#" class="footer-submenu-item">Vascular Surgeons</a>
            <a href="#" class="footer-submenu-item mb-20">Radiologists</a>
        <p class="menu-heading is-uppercase">
            PATIENT RESOURCES
        </p>
            <a href="#" class="footer-submenu-item">Renal Nutrition</a>
            <a href="#" class="footer-submenu-item">Videos</a>
            <a href="#" class="footer-submenu-item">Health Links</a>
            <a href="#" class="footer-submenu-item">Brochures</a>
    </div>
    <div class="column is-4">
        <a href="#" class="button white-button is-uppercase mb-20">Make a payment</a>
        <p class="menu-heading is-uppercase mb-5">
            CONTACT:
        </p>
        <p class="footer-submenu-item mb-20">
            PO Box 1750, <br>
            Lynn Haven, FL 32444 <br>
            Email: <a href"mailto:thevirtualnephrologist@gmail.com">thevirtualnephrologist@gmail.com</a>
        </p>
        <div class="social has-text-left">
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