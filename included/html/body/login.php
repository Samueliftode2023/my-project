<form id='login'>
    <h2>BINE AI REVENIT!</h2>
    <label>
        <span class='place-h'>Nume utilizator</span>
        <input placeholder='Nume' name='username' value='<?php echo $rem_user;?>' minlength='5' maxlength='30' required>
    </label>
    <br>
    <label>
        <span class='place-h'>Parola</span>
        <input placeholder='Parola' name='password' value='<?php echo $rem_pass;?>' pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' type='password' minlength='8' maxlength='40' required>
    </label>
    <br>
    <div class="g-recaptcha" data-sitekey="6LevXBAgAAAAAKpnReqgefdcNQYccsRP-EonRjHW"></div>
    <label id='term-label' for='terms-condition'><input id='terms-condition' type='checkbox' name='reminde' <?php echo $checked;?>>Tine-ma minte</label>
    <span class='br-class'></span>
    <button class='tag-default'>CONECTARE</button>
    <a href='../sign-up/' class='tag-default a-class'>INREGISTRARE</a>
</form>
    <div id='loading-id' class='loading dis-none'>
        <div class='centrare-loading'>            
            <div class="loadingio-spinner-rolling-5owm4mbayhw"><div class="ldio-jl4i6909sug">
            <div></div>
            </div>
        </div>
        </div>
    </div>
<div id='display-message'>

</div>