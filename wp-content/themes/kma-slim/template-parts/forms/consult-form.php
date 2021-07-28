<div class="consult-form">
    <form method="post" >
        <input type="text" name="sec" value="" class="sec-form-code" style="position: absolute; left:-10000px; top:-10000px; height:0px; width:0px;" >
        <input type="hidden" name="user_agent" value="{{user-agent}}" >
        <input type="hidden" name="ip_address" value="{{ip-address}}" >
        <?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
        <input type="hidden" name="referrer" value="{{referrer}}" >
        <?php } ?>
        <div class="columns is-multiline">
            <div class="column is-6">
                <input type="text" name="first_name" class="input" placeholder="First Name" required>
            </div>
            <div class="column is-6">
                <input type="text" name="last_name" class="input" placeholder="Last Name" required>
            </div>
            <div class="column is-12">
                <input type="email" name="email_address" class="input email" placeholder="Email Address" required>
            </div>
            <div class="custom-control custom-checkbox" style="margin: .5em 0 .5em 1em;">
                <input id="terms-checkbox" type="checkbox" value="Terms Accepted" class="custom-control-input">
                <label for="terms-checkbox" class="tiny-text custom-control-label" >
                    By checking this box, I agree that I have read the <a href="/terms-of-service/">Terms of Service</a> and <a href="/third-party-advertisements/">Third-Party Advertisements</a> agreements and that I accept the provisions of said agreements. If unwilling to consent, call <a href="tel:9293796953" style="text-decoration: underline;">929-379-6953</a> to learn more about our services. Other terms and conditions may apply.
                </label>
            </div>
            <div class="column is-12">
                <button class="button is-primary" type="submit">submit</button>
            </div>
        </div>
    </form>
</div>