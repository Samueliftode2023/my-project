<form id='sign-up'>
    <h2>BINE AI VENIT!</h2>
        <label>
            <span class='place-h'>Nume utilizator</span>
            <input placeholder='Nume' name='username' minlength='5' maxlength='30' required>
        </label>
        <br>
        <label>
            <span class='place-h'>Parola</span>
            <input placeholder='Parola' name='password' pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' type='password' minlength='8' maxlength='40' required>
        </label>
        <br>
        <label>
            <span class='place-h'>Confirma parola</span>
            <input placeholder='Confirma parola' name='confirm-password' pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' type='password' minlength='8' maxlength='40' required>
        </label>
        <br>
        <details>
            <summary>Formatul parolei</summary>
            <div class='format-pss'>Cel putin o litera mica si una mare 
                si cel putin o cifra, iar parola trebuie sa 
                aiba mai mult de 7 caractere.
            </div>
        </details> 
        <br>
        <div class="g-recaptcha" data-sitekey="6LevXBAgAAAAAKpnReqgefdcNQYccsRP-EonRjHW"></div>
        <label id='term-label' for='terms-condition'><input id='terms-condition' type='checkbox' required>Accept <a title='terms and conditions' aria-label='terms-of-service' href="terms-of-service/">termenii si conditiile</a></label>
        <br>
        <button class='tag-default'>INREGISTRARE</button>
        <a href='../login/' class='tag-default a-class'>CONECTARE</a>
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