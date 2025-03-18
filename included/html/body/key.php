<div id='container'>
    <div class='bara-control'>Pagina principala</div>
    <div class='container-content'>
        <div id='viata-crestina' class='meniu-linkuri'>
            <div class='cap-meniu'>
                <div class='icon-intrunire'>
                    <span class="material-symbols-outlined">
                        lan
                    </span>
                </div>
                <h3>Acces date</h3>
            </div>
            <div class='linkuri-dash'>
                <?php
                    if(check_activity($_SESSION['username'], 'users', $conectareDB) == 0){
                        echo '<a href="personal-key/create-key/">Creeaza o cheie</a>';
                    }
                    else{
                        if(isset($_COOKIE['cookie-key'])){
                            echo '<a href="personal-key/cookie-key/">Conectare directa</a>';
                        }
                    }
                ?>
                <br>
                <a href="personal-key/upload-key/">Incarca o cheie</a>
            </div>
        </div>
    </div>
</div>