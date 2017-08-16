<?php
/**
 * Top menu for html pages of website. Medium and Large screen
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category modules
 * @package UI
 * @filesource
 */
?>

<div id="top-menu">
    <ul>
        <!-- Home -->
        <li class="top-menu-choice">
            <a href="/home.php"><h4>HOME </h4></a>
        </li>

        <!-- Management -->
        <li class="top-menu-choice">
            <a href="/management"><h4>MANAGEMENT</h4></a>
        </li>

        <!-- Project -->
        <li class="top-menu-choice">
            <a href="/project"><h4>PROJECT </h4></a>
        </li>
        <?php if($_SESSION['access'] > 2) { ?>
        <!-- Stock -->
        <li class="top-menu-choice">
            <a href="/stock"><h4>STOCK</h4></a>
        </li>

        <?php } else { ?>
        <!-- Stock -->
        <li class="top-menu-choice">
            <a href="/stock?page=available"><h4>STOCK</h4></a>
        </li>
        <?php } ?>
        <!-- Documentation -->
        <li class="top-menu-choice">
            <a href="/documentation"><h4>DOCUMENTATION</h4></a>
        </li>
    </ul>
</div>