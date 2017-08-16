<?php
/**
 * Sidebar for html pages of website. Only small screen
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category modules
 * @package UI
 * @filesource
 */

$current_choice = (isset($current_choice))? $current_choice: '';?>

<span id="menu-toggle" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; </span>
    <div id="mySidenav" class="sidenav">
        <!-- Close button -->
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <!-- Logo -->
        <a href="#top" onclick=$("#menu-close").click();><img id="logo_in_nav" src="../img/Logo.svg"></a>
        <!-- Home -->
        <a class="choice <?php echo ($current_choice == 'home')? 'choice-current': '';?>" href="/home.php" onclick=$("#menu-close").click();>Home</a>
        <!-- About -->
        <a class="choice" href="#about" onclick=$("#menu-close").click();>About</a>

        <!-------Next part of sidebar is available online if you are connected------->
        <?php if($UserConnected) { ?>
            <!-- Project -->
            <a class="choice <?php echo ($current_choice == 'project')? 'choice-current': '';?>" href="/project">Project</a>
            <!-- Management -->
            <a class="choice <?php echo ($current_choice == 'management')? 'choice-current': '';?>" href="/management">Management</a>
            <!-- Documentation -->
            <a class="choice <?php echo ($current_choice == 'documentation')? 'choice-current': '';?>" href="/documentation">Documentation</a>
            <?php if($_SESSION['access'] > 2) { ?>
                <!-- Stock -->
                <a class="choice <?php echo ($current_choice == 'stock')? 'choice-current': '';?>" href="/stock">Stock</a>
                <?php if($_SESSION['access'] > 3) { ?>
                    <!-- Account Admin -->
                    <a class="choice <?php echo ($current_choice == 'admin')? 'choice-current': '';?>" href="/home.php?page=accountsAdmin">Account Admin</a>
                <?php }
            } else { ?>
                <a class="choice <?php echo ($current_choice == 'stock')? 'choice-current': '';?>" href="/stock?page=available">Stock</a>
            <?php } ?>
            <!-- Change password -->
            <a class="choice <?php echo ($current_choice == 'changePWD')? 'choice-current': '';?>" href="/home.php?page=changepassword">Change Password</a>
            <!-- Logout -->
            <a class="choice" href="/account/logout.php">Logout</a>
        <?php } ?>
    </div>