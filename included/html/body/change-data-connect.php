<div id='container'>
    <div class='bara-control'></div>
    <div class='optiuni-date'>
        <form id='username-form' class='schimba-username'>
            <div id='nume-curent'>
                Nume curent: 
                <span id='nume-curent-display'>
                    <?php echo $_SESSION['username']?>
                </span>
            </div>
            <label>
                <input name='username' minlength='5' maxlength='30' required>
            </label>
            <br>
            <button>Save</button>
        </form>
        <form id='password-form' class='schimba-parola'>
            <label>
                Introdu parola actuala
                <input name='current-password' pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' type='password' minlength='8' maxlength='40' required>
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
            <label>
                Noua parola
                <input name='password' pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' type='password' minlength='8' maxlength='40' required>
            </label>
            <br>
            <label>
                Confirma parola
                <input name='confirm-password' pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' type='password' minlength='8' maxlength='40' required>
            </label>
            <br>
            <button>Save</button>
        </form>
        <form id='cheie-form' class='schimba-numele-cheii'>
            <div id='cheie-curenta'>
                Cheia: 
                <span id='nume-cheie-curent-display'>
                    <?php 
                        $sql_search = 'SELECT * FROM users WHERE username = "'.$_SESSION['username'].'"';
                        $execute_search = mysqli_query($conectareDB, $sql_search);
                        $row_user =  mysqli_fetch_assoc($execute_search);
                        $filter_key = explode('.json',$row_user['key_user']);
                        echo $filter_key[0];
                    ?>
                </span>
            </div>
            <label>
                <input name='new-keys-name' required>
            </label>
            <br>
            <button>Save</button>
        </form>
    </div>
    <div id='loading-id' class='loading dis-none'>
        <div class='centrare-loading'>            
            <div class="loadingio-spinner-rolling-5owm4mbayhw"><div class="ldio-jl4i6909sug">
            <div></div>
            </div>
            </div>
        </div>
    </div>
</div>