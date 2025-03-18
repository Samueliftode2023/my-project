<div id='navigation'>
    <div id='meniu-button'>
        <span class="material-symbols-outlined">
        menu
        </span>
    </div>
    <div class='linkuri'>
        <a class='mark-dash' href=<?php echo "'".$root."main/key/'";?>>    
            <span class="material-symbols-outlined">
                dashboard
            </span>
        </a>
        <a href=<?php echo "'".$root."main/key/personal-key/upload-key/'";?>>
            <span class="material-symbols-outlined">
                upload
            </span>
        </a>
    </div>
    <div class='setari'>
        <a></a>
        <a id=<?php echo "'logout||".$root."'";?> onclick='logOut(this)' href='#'>
            <span class="material-symbols-outlined">
            logout
            </span>
        </a>
    </div>
</div>
<div id='navigation-mobile'>
    <a href=<?php echo "'".$root."main/key/personal-key/upload-key/'";?>>
        <span class="material-symbols-outlined">
            upload
        </span>
    </a>
    <a href=<?php echo "'".$root."main/meetings/print-meeting/'";?>>
        <span class="material-symbols-outlined">
            people
        </span>
    </a>
    <a class='mark-dash' href=<?php echo "'".$root."main/key/'";?>>
        <span class="material-symbols-outlined">
            dashboard
        </span>
    </a>
    <a href=<?php echo "'".$root."main/meetings/print-teme/'";?>>
        <span class="material-symbols-outlined">
            info
        </span>
    </a>
    <a id=<?php echo "'logout||".$root."'";?> onclick='logOut(this)' href='#'>
        <span class="material-symbols-outlined">
            logout
        </span>
    </a>
</div>
