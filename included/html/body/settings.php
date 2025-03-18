<div id='container'>
    <div class='bara-control'></div>
    <div class='lista-stari'>
        <!--<a href='#'>Adauga vorbitori externi</a>-->
        <!--<a href='#'>Sincornizare cu google calendar</a>-->
        <!--<a href='#'>Salveaza cheia pe dispozitiv</a>-->
        <a href='custom-table/'>
            <div>Creeaza un tabel</div>
            <span class="material-symbols-outlined">
                arrow_forward_ios
            </span>
        </a>
        <a href=<?php echo "'".$root."main/meetings/download-meeting/'";?>>
            <div>Descarca intrunirile</div>
            <span class="material-symbols-outlined">
            arrow_forward_ios
            </span>
        </a>
        <a href='change-data-connect/'>
            <div>Schimba datele de conectare</div>
            <span class="material-symbols-outlined">
                arrow_forward_ios
            </span>
        </a>
        <a href='#'>
            <div>Salveaza cheia pe dispozitiv</div>
            <span class="material-symbols-outlined">
                arrow_forward_ios
            </span>
        </a>
        <a id=<?php echo "'logout||".$root."'";?> onclick='logOut(this)' href='#'>
            Deconectare
            <span class="material-symbols-outlined">
            logout
            </span>
        </a>
        <!--<a href='#'>Partajari</a>-->
    </div>
</div>
