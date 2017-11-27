<?php
/**
 * defaultHome content for HOME part
 *
 * content file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Home\Content
 * @namespace Home
 * @filesource
 */

$data_project_list = get_project_list($bdd);

$data_users_list = get_user_list($bdd);

?>

<div class="container content">
    <div class="row" style="margin-top: 8%;margin-bottom: 10px;">
        <!-- clouds -->
        <img id="cloud_1" class="cloud" src="/img/clouds.png" style="z-index: 2;">
        <img id="cloud_2" class="cloud" src="/img/clouds.png" style="Z-index: 2;">
        <!-- Text -->
        <img id="hammock-helicopter" class="title_txt" src="/img/hammock-helicopter.png" style="z-index: 1;"/>
        <img id="hammock" class="title_txt" src="/img/hammock.png" style="z-index: 1;"/>
        <img id="helicopter" class="title_txt" src="/img/helicopter.png" style="z-index: 1;"/>
        <!-- Logo -->
        <img id="planet" class="title_img" src="/img/planet-christmas.png" style="z-index: 1;"/>
        <img id="helico" class="title_img" src="/img/helico.png" style="z-index: 3;"/>

        <style type="text/css">

            @keyframes helicopter-arrival {
                from {
                    left: 100%;
                    -ms-transform: rotate(-40deg); /* IE 9 */
                    -webkit-transform: rotate(-40deg); /* Chrome, Safari, Opera */
                    transform: rotate(-40deg);
                }

                60% {
                    -ms-transform: rotate(20deg); /* IE 9 */
                    -webkit-transform: rotate(20deg); /* Chrome, Safari, Opera */
                    transform: rotate(20deg);
                }
            }

            @keyframes text-apparition {
                from { opacity: 0; }

                10% { opacity: 0; }

                to { opacity: 1; }
            }

            /* LARGE SCREEN */
            @media screen and (min-width: 1200px) {

                .title_img, .title_txt, .cloud {
                    position: absolute;
                }

                #hammock ,#helicopter {
                    display: none;
                }

                #hammock-helicopter {
                    left: 30%;
                    animation-duration: 8s;
                    animation-name: text-apparition;
                }

                .title_txt{
                    margin-top: 60px;
                    max-height: 60px;
                }

                #cloud_1 {
                    left: 27%;
                    animation-duration: 6s;
                    animation-name: cloud-left;
                }

                #cloud_2 {
                    left: 47%;
                    transform: scaleX(-1);
                    animation-duration: 6s;
                    animation-name: cloud-right;
                }

                .cloud {
                    opacity: 0;
                    margin-top: 10px;
                    min-height: 30px;
                    max-height: 140px;
                }

                #helico, #planet {
                    max-width: 350px;
                    left: 3%
                }

                #helico {
                    animation-duration: 6s;
                    animation-name: helicopter-arrival;
                }

                @keyframes cloud-right {
                    from {
                        left: 50%;
                        margin-top: 10px;
                        opacity: 1;
                        max-height: 140px;
                    }

                    to {
                        left: 60%;
                        margin-top: 20px;
                        opacity: 0;
                        max-height: 100px;
                    }
                }

                @keyframes cloud-left {
                    from {
                        left: 33%;
                        margin-top: 40px;
                        opacity: 1;
                        max-height: 140px;
                    }

                    to {
                        left: 23%;
                        margin-top: 40px;
                        opacity: 0;
                        max-height: 100px;
                    }
                }
            }

            @media screen and (min-width: 600px) and (max-width: 1200px) {

                #home-options {
                    margin-top: 15% !important;
                }

                .title_img, .title_txt, .cloud {
                    position: absolute;
                    margin-top: 50px;
                }

                #hammock ,#helicopter {
                    display: none;
                }

                #hammock-helicopter {
                    left: 27%;
                    animation-duration: 8s;
                    animation-name: text-apparition;
                }

                .title_txt{
                    margin-top: 60px;
                    max-height: 30px;
                }

                #cloud_1 {
                    left: 23%;
                    animation-duration: 6s;
                    animation-name: cloud-left;
                }

                #cloud_2 {
                    left: 30%;
                    transform: scaleX(-1);
                    animation-duration: 6s;
                    animation-name: cloud-right;
                }

                .cloud {
                    opacity: 0;
                    margin-top: 10px;
                    min-height: 60px;
                    max-height: 60px;
                }

                #helico, #planet {
                    max-width: 150px;
                    left: 3%
                }

                #helico {
                    animation-duration: 6s;
                    animation-name: helicopter-arrival;
                }

                @keyframes cloud-right {
                    from {
                        left: 45%;
                        margin-top: 35px;
                        opacity: 1;
                        max-height: 60px;
                    }

                    to {
                        left: 55%;
                        margin-top: 40px;
                        opacity: 0;
                        max-height: 20px;
                    }
                }

                @keyframes cloud-left {
                    from {
                        left: 25%;
                        margin-top: 45px;
                        opacity: 1;
                        max-height: 60px;
                    }

                    to {
                        left: 17%;
                        margin-top: 50px;
                        opacity: 0;
                        max-height: 20px;
                    }
                }
            }

            @media screen and (max-width: 600px) {

                #home-options {
                    margin-top: 150px !important;
                }

                .title_img, .title_txt, .cloud {
                    position: absolute;
                    margin-top: 30px;
                }

                #hammock-helicopter {
                    display: none;
                }

                #hammock ,#helicopter {
                    animation-duration: 8s;
                    animation-name: text-apparition;
                }

                #hammock {
                    left: 40%;
                    margin-top: 37px;
                }

                #helicopter {
                    left: 45%;
                    margin-top: 65px;
                }

                .title_txt {
                    max-height: 20px;
                }

                #cloud_1 {
                    left: 23%;
                    animation-duration: 6s;
                    animation-name: cloud-left;
                }

                #cloud_2 {
                    left: 30%;
                    transform: scaleX(-1);
                    animation-duration: 6s;
                    animation-name: cloud-right;
                }

                .cloud {
                    opacity: 0;
                    margin-top: 10px;
                    min-height: 20px;
                    max-height: 30px;
                }

                #helico, #planet {
                    max-width: 100px;
                    left: 3%
                }

                #helico {
                    animation-duration: 6s;
                    animation-name: helicopter-arrival;
                }

                @keyframes cloud-right {
                    from {
                        left: 45%;
                        margin-top: 63px;
                        opacity: 1;
                        max-height: 35px;
                    }

                    to {
                        left: 55%;
                        margin-top: 75px;
                        opacity: 0;
                        max-height: 20px;
                    }
                }

                @keyframes cloud-left {
                    from {
                        left: 40%;
                        margin-top: 35px;
                        opacity: 1;
                        max-height: 35px;
                    }

                    to {
                        left: 30%;
                        margin-top: 45px;
                        opacity: 0;
                        max-height: 20px;
                    }
                }
            }
        </style>

        <script>
            function goto_calendar(id_select) {
                var select = document.getElementById(id_select);
                var value = select.value;
                var calendar = select.options[select.selectedIndex].parentNode.label;

                if(calendar === 'users')
                    window.location.href = window.location.origin + '/management/?page=workUserCalendar&id=' + value;
                else if (calendar === 'projects')
                    window.location.href = window.location.origin + '/management/?page=workProjectCalendar&id=' + value;
            }
        </script>
    </div>

    <!-- Modal PN Cross References -->
    <?php include $_SERVER['DOCUMENT_ROOT'].'/ui/modal_PNRef.php'; ?>

    <div class="row center" id="home-options" style="margin-top: 300px;">
        <div class="col-lg-offset-3 col-lg-2">
            <div class="dropdown">
                <button onclick="dropdown('ACCOUNT')" class="dropbtn button">Account</button>
                <div id="myDropdown-ACCOUNT" class="dropdown-content">
                    <?php echo ($lvl_access > 3)? '<a href="/home.php?page=accountsAdmin">Account Admin</a>':'';?>
                    <a href="/home.php?page=changepassword">Change Password</a>
                    <a href="/account/logout.php">Logout</a>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <button class="button" id="PN-SEARCH" type="button" onclick="this.disabled = true;$('#pn_modal').toggle();searchPNRef(this);">PN References</button>
            <input type="text" id="PartFilterInput" class="form_input" style="margin-top: 5px;"/>
        </div>
        <div class="col-lg-2">
            <?php if($lvl_access > 2) { ?>
                <button class="button" type="button" onclick="goto_calendar('select_calendar');">Calendar</button>
                <span class="custom-dropdown custom-dropdown--white" style="margin-top: 5px;">
                    <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" id="select_calendar">
                        <optgroup label="projects">
                            <option value="0">Daily</option>
                            <?php foreach ($data_project_list as $project) { ?>
                                <option value="<?php echo $project['id'];?>"><?php echo $project['p_name'];?></option>
                            <?php } ?>
                        </optgroup>
                        <optgroup label="users">
                            <?php foreach ($data_users_list as $user) { ?>
                                <option value="<?php echo $user['U_ID'];?>"><?php echo $user['U_NAME'];?></option>
                            <?php } ?>
                        </optgroup>
                    </select>
                </span>
            <?php } else { ?>
                <a href="/management/?page=workUserCalendar&id=<?php echo $_SESSION['user_id'];?>"><button class="button" type="button">Calendar</button></a>
            <?php } ?>
        </div>
    </div>
    <form id="personal_work" class="row" style="margin-top: 50px;" onsubmit="xmlhttpPost(document.location.href, 'personal_work', 'personal_work', '');return false;" method="post">
        <?php
        $data_current_work = get_current_user_work_for_user($bdd, $_SESSION['user_id']);
        if($data_current_work) { ?>
            <input type="hidden" value="<?php echo $data_current_work['UW_ID'];?>" name="f_uw_id" />
            <div class="col-lg-10 col-lg-offset-1 edit" style="background-color: #77b5fe;color: white;padding: 8px;text-align: center;font-size: 16px;box-shadow: 2px 2px #999;">
                <h2>Your current Job</h2>
                <div class="col-lg-2" style="border: solid 1px;">
                    <h3>Project</h3>
                    <p><?php echo (($data_current_work['RW_ID_ST']!=NULL)? $data_current_work['P_NAME'] : 'DAILY');?></p>
                </div>
                <div class="col-lg-2" style="border: solid 1px;">
                    <h3>SubTask Number</h3>
                    <p><?php echo (($data_current_work['RW_ID_ST']!=NULL)? $data_current_work['GST_NUMBER'] : 'DAILY');?></p>
                </div>
                <div class="col-lg-3" style="border: solid 1px;">
                    <h3>Description</h3>
                    <p><?php echo (($data_current_work['RW_ID_ST']!=NULL)? $data_current_work['GST_DESCRIPTION'] : $data_current_work['RW_DESCRIPTION']);?></p>
                </div>
                <div class="col-lg-3" style="border: solid 1px;">
                    <h3>Begin Time</h3>
                    <p><?php echo $data_current_work['UW_DATETIME_BEGIN'];?></p>
                </div>
                <div class="col-lg-2" style="height: 100%;">
                    <button class="button danger" style="font-size: 20px;" type="submit">Close Work</button>
                </div>
            </div>
        <?php } ?>
    </form>
    <?php if($lvl_access > 2) {
        echo '<h2 style="margin-top: 50px;margin-left: 5%;">Work In Progress list</h2>';
        $data_current_work_list = get_current_user_work_list($bdd);
        foreach ($data_current_work_list as $current_work) {
            if ($current_work['U_ID'] != $_SESSION['user_id']) { ?>
                <form class="row" id="current-<?php echo $current_work['UW_ID'];?>" style="margin-top: 50px;" onsubmit="xmlhttpPost(document.location.href, 'current-<?php echo $current_work['UW_ID'];?>', 'current-<?php echo $current_work['UW_ID'];?>', '');return false;">
                    <input type="hidden" value="<?php echo $current_work['UW_ID'];?>" name="f_uw_id" />
                    <div class="col-lg-10 col-lg-offset-1" style="background-color: #77b5fe;color: white;padding: 8px;text-align: center;font-size: 16px;box-shadow: 2px 2px #999;">
                        <h2><?php echo $current_work['U_NAME']; ?></h2>
                        <div class="col-lg-2" style="border: solid 1px;">
                            <h3>Project</h3>
                            <p><?php echo(($current_work['RW_ID_ST'] != NULL) ? $current_work['P_NAME'] : 'DAILY'); ?></p>
                        </div>
                        <div class="col-lg-2" style="border: solid 1px;">
                            <h3>SubTask Number</h3>
                            <p><?php echo(($current_work['RW_ID_ST'] != NULL) ? $current_work['GST_NUMBER'] : 'DAILY'); ?></p>
                        </div>
                        <div class="col-lg-3" style="border: solid 1px;">
                            <h3>Description</h3>
                            <p><?php echo(($current_work['RW_ID_ST'] != NULL) ? $current_work['GST_DESCRIPTION'] : $current_work['RW_DESCRIPTION']); ?></p>
                        </div>
                        <div class="col-lg-3" style="border: solid 1px;">
                            <h3>Begin Time</h3>
                            <p><?php echo $current_work['UW_DATETIME_BEGIN']; ?></p>
                        </div>
                        <div class="col-lg-2" style="height: 100%;">
                            <button class="button danger" style="font-size: 20px;" type="submit">Close Work</button>
                        </div>
                    </div>
                </form>
            <?php }
        }
    } ?>
</div>