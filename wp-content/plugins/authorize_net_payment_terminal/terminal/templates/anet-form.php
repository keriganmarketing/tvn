<?php

$customJS = [];
$resArray = []; # will hold transactions details where does not exist.

//REQUEST VARIABLES
$item_description = ( ! empty($_REQUEST["item_description"])) ? strip_tags(str_replace("'", "`", $_REQUEST["item_description"])) : '';
$amount           = ( ! empty($_REQUEST["amount"])) ? strip_tags(str_replace("'", "`", $_REQUEST["amount"])) : '';
$invoicenum       = ( ! empty($_REQUEST["invoicenum"])) ? strip_tags(str_replace("'", "`", $_REQUEST["invoicenum"])) : '';
$fname            = ( ! empty($_REQUEST["fname"])) ? strip_tags(str_replace("'", "`", $_REQUEST["fname"])) : '';
$lname            = ( ! empty($_REQUEST["lname"])) ? strip_tags(str_replace("'", "`", $_REQUEST["lname"])) : '';
$email            = ( ! empty($_REQUEST["email"])) ? strip_tags(str_replace("'", "`", $_REQUEST["email"])) : '';
$address          = ( ! empty($_REQUEST["address"])) ? strip_tags(str_replace("'", "`", $_REQUEST["address"])) : '';
$city             = ( ! empty($_REQUEST["city"])) ? strip_tags(str_replace("'", "`", $_REQUEST["city"])) : '';
$country          = ( ! empty($_REQUEST["country"])) ? strip_tags(str_replace("'", "`", $_REQUEST["country"])) : 'US';
$state            = ( ! empty($_REQUEST["state"])) ? strip_tags(str_replace("'", "`", $_REQUEST["state"])) : '';
$zip              = ( ! empty($_REQUEST["zip"])) ? strip_tags(str_replace("'", "`", $_REQUEST["zip"])) : '';
$serviceID        = ( ! empty($_REQUEST['serviceID'])) ? strip_tags(str_replace("'", "`", strip_tags($_REQUEST['serviceID']))) : '';
$service          = ( ! empty($_REQUEST['service'])) ? strip_tags(str_replace("'", "`", strip_tags($_REQUEST['service']))) : $serviceID;

?>
<form id="ff1" name="ff1" method="post" action="" enctype="multipart/form-data" class="anpt_form">
    <h2 class="current">Payment Information</h2>
    <div class="pane">
        <?php if ($form->show_services == 1) {
            $services = $form->getServices();
            if (count($services) > 0) {
                if (empty($form->serviceID)) {
                    $anpt_wform = '<label>Service:</label><select style="width:250px;" name="service" class="long-field" id="service">';
                    $anpt_wform .= '<option value="">Please Select</option>';
                    foreach ($services as $k => $v) {
                        $anpt_wform .= '<option value="' . $v->anpt_services_id . '" ' . ($service == $v->anpt_services_id ? "selected='selected'" : "") . '>' . stripslashes($v->anpt_services_title) . ' - ' . number_format($v->anpt_services_price,
                                2) . " " . anpt_CURRENCY_CODE . '</option>';
                    }
                    $anpt_wform .= '</select><div class="clr"></div>';
                    if (isset($form->anpt_show_amount_text) && $form->anpt_show_amount_text != "1") {
                        $anpt_wform .= '<input type="hidden" value="service" name="anpt_ptype" />';
                    }
                } else {
                    $row        = $services[0];
                    $anpt_wform = '<label>Service:</label>';
                    $anpt_wform .= "<div class='service'>" . stripslashes($row->anpt_services_title) . "-" . number_format($row->anpt_services_price,
                            2) . " " . anpt_CURRENCY_CODE . "</div>";
                    $anpt_wform .= "<input type='hidden' value='" . $row->anpt_services_id . "' name='service' />";
                    $anpt_wform .= "<input type='hidden' value='" . $serviceID . "' name='serviceID' />";
                }
            }
            echo $anpt_wform;
        } ?>
        <input type="hidden" value="<?php echo $form->show_services; ?>" name="show_services"/>
        <?php if (isset($form->anpt_show_amount_text) && $form->anpt_show_amount_text == "1") { ?>
            <div class="columns is-multiline">
                <div class="column is-4" >
                    <label class="label">Patient Account Number:</label>
                    <div class="field">
                        <p class="control">
                            <input name="invoicenum" id="invoicenum" type="text" class="input small-field" value="<?php echo $invoicenum; ?>"
                                   onkeyup="checkFieldBack(this);noAlpha(this);" onkeypress="noAlpha(this);"/>
                        </p>
                    </div>
                </div>
                <div class="column is-4" >
                    <label class="label">Amount:</label>
                    <div class="field has-addons">
                        <p class="control">
                            <a class="button is-static">
                                $
                            </a>
                        </p>
                        <p class="control">
                            <input name="amount" id="amount" type="text" class="input small-field" value="<?php echo $amount; ?>"
                                   onkeyup="checkFieldBack(this);noAlpha(this);" onkeypress="noAlpha(this);"/>
                        </p>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($form->anpt_display_comment) && $form->anpt_display_comment == 1) { ?>
            <label class="label">Description:</label>
            <textarea name="item_description" id="item_description" type="text" class="textarea long-field" style="height:50px"
                      onkeyup="checkFieldBack(this);"><?php echo $item_description; ?></textarea>
        <?php } ?>
    </div>

    <h2>Credit Card Information</h2>
    <div class="pane">
        <div class="columns is-multiline">
            <div class="column is-12">
                <label class="label"> I have:</label>
                <div class="control">
                    <input name="cctype" type="radio" value="V" class="lft-field"/>
                    <img src="<?= $imageDir; ?>/ico_visa.jpg" align="absmiddle" class="lft-field cardhide V"/>
                    <input name="cctype" type="radio" value="M" class="lft-field"/>
                    <img src="<?= $imageDir; ?>/ico_mc.jpg" align="absmiddle" class="lft-field cardhide M"/>
                    <input name="cctype" type="radio" value="A" class="lft-field"/>
                    <img src="<?= $imageDir; ?>/ico_amex.jpg" align="absmiddle" class="lft-field cardhide A"/>
                    <input name="cctype" type="radio" value="D" class="lft-field"/>
                    <img src="<?= $imageDir; ?>/ico_disc.jpg" align="absmiddle" class="lft-field cardhide D"/>
                </div>
            </div>
            <div class="column is-5">

                <label class="label">Card Number:</label>
                <div class="control">
                    <input name="ccn" id="ccn" type="text" class="input long-field"
                           onkeyup="checkNumHighlight(this.value);checkFieldBack(this);noAlpha(this);" value=""
                           onkeypress="checkNumHighlight(this.value);noAlpha(this);" onblur="checkNumHighlight(this.value);"
                           onchange="checkNumHighlight(this.value);" maxlength="16"/>
                    <span class="ccresult"></span>
                </div>

            </div>
            <div class="column is-narrow">
                <label class="label">Expiration Date:</label>
                <div class="control">
                    <div class="select">
                    <select name="exp1" id="exp1" class="small-field" onchange="checkFieldBack(this);">
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                    </div>
                    <div class="select">
                    <select name="exp2" id="exp2" class="small-field" onchange="checkFieldBack(this);">
                        <?php for($i=date("Y");$i<date("Y", strtotime(date("Y")." +10 years"));$i++){
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        } ?>
                    </select>
                    </div>
                </div>
            </div>
            <div class="column is-narrow">
                <label class="label">CVV:</label>
                <div class="field has-addons">
                    <div class="control">
                        <input name="cvv" id="cvv" type="text" maxlength="5" class="input small-field" onkeyup="checkFieldBack(this);noAlpha(this);"/>
                    </div>
                    <div class="control">
                        <a href="hint.php" rel="hint" class="button noscriptCase"><img src="<?= $imageDir; ?>/ico_question.jpg" align="absmiddle" border="0"/></a>
                    </div>
                </div>
                <noscript>
                    <a href="hint.php" target="_blank"><img src="images/ico_question.jpg" align="absmiddle" border="0"/></a>
                </noscript>
            </div>
            <div class="column is-5">
                <label class="label">Name on Card:</label>
                <div class="control">
                    <input name="ccname" id="ccname" type="text" class="input long-field" onkeyup="checkFieldBack(this);"/>
                </div>
                <p>&nbsp;</p>
            </div>

        </div>

    </div>
    <h2>Billing Information</h2>
    <div class="pane">
        <div class="columns is-multiline">
            <div class="column is-4">
                <label class="label">First Name:</label>
                <input name="fname" id="fname" type="text" class="input long-field" value="<?php echo $fname; ?>"
                       onkeyup="checkFieldBack(this);"/>
            </div>
            <div class="column is-4">
                <label class="label">Last Name:</label>
                <input name="lname" id="lname" type="text" class="input long-field" value="<?php echo $lname; ?>"
                       onkeyup="checkFieldBack(this);"/>
            </div>
            <div class="column is-4">
                <label class="label">E-mail:</label>
                <input name="email" id="email" type="text" class="input long-field" value="<?php echo $email; ?>"
                       onkeyup="checkFieldBack(this);"/>
            </div>

            <div class="column is-8">
                <label class="label">Address:</label>
                <input name="address" id="address" type="text" class="input long-field" value="<?php echo $address; ?>"
                       onkeyup="checkFieldBack(this);"/>
            </div>
            <div class="column is-4">
            </div>

            <div class="column is-narrow">
                <label class="label">City:</label>
                <input name="city" id="city" type="text" class="input long-field" value="<?php echo $city; ?>"
                       onkeyup="checkFieldBack(this);"/>
            </div>
            <div class="column is-narrow">
                <label class="label">State/Province:</label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select style="width:250px;" name="state" id="state" class="long-field" onchange="checkFieldBack(this);">
                            <?php include('inc/state-select.php'); ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="column is-narrow">
                <label class="label">ZIP/Postal Code:</label>
                <input name="zip" id="zip" type="text" class="input small-field" value="<?php echo $zip; ?>"
                       onkeyup="checkFieldBack(this);"/>
            </div>
            <div class="column is-narrow">
                <label class="label">Country:</label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select style="width:250px;" name="country" id="country" class="long-field" onchange="checkFieldBack(this);">
                            <?php include('inc/country-select.php'); ?>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <div class="column is-12">
            <?php if ($form->anpt_enable_captcha) { ?>
                <div class="g-recaptcha" data-sitekey="<?php echo($form->anpt_captcha_site) ?>" data-callback="checkCaptcha"></div>
            <?php } ?>
        </div>
        <div class="columns is-multiline">
            <div class="column is-12">
                <input type="hidden" name="process" value="yes" />
                <div class="submit-btn">
                    <button type="submit" name="submit" class="button is-primary" <?php if ($form->anpt_enable_captcha){ ?>disabled<?php } ?> >Submit Payment</button>
                </div>
            </div>
        </div>
    </div>

</form>
