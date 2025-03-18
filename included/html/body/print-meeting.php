<div id='container'>
    <form id='print-meeting' method='post'>
        <label>
            <select id='year' name='meeting-year'>
                <?php
                    get_meetings_year();
                ?>
            </select>
        </label>   
        <label>
            <select id='month' name='meeting-month'>
                <?php
                    get_meetings_months();
                ?>
            </select>
        </label>
        <div id='filtru-saptamani' onclick='activeWeekTable()'>
            <span class="material-symbols-outlined">
                tune
            </span>
        </div>
        <div id='container-editor'>
            <div onclick='activeEditor()' id='switch-edit'>
                <div id='switcher'>
                    <span class="material-symbols-outlined">
                    edit
                    </span>
                </div>
            </div>
            <div id='print' onclick='printPage()'>
                PDF
            </div>
        </div>
    </form>
    <div id='meetings' contenteditable = 'false'>
    </div>
    <div id='blur-back-id' class='dis-none'>
        <div id='saptamani-caiet'>
            <div class='meniu-saptamani'>
                <select onchange='getWeekAfter()' id='year-filter' name='year-filter'>
                    <?php
                        get_meetings_year();
                    ?>
                </select>
                <div class='arrow-aranjament'>
                    <span onclick='upWeek()' class="material-symbols-outlined">
                        arrow_upward
                    </span>
                    <span onclick='downWeek()' class="material-symbols-outlined">
                        arrow_downward
                    </span>
                </div>
                <div onclick='closeWeek()' class='buton-anulare'>
                    Anuleaza
                </div>
            </div>
            <div class='select-saptamani'>
                <?php
                    redaToateSaptamanile(2024);
                    redaToateSaptamanile(2025);
                    redaToateSaptamanile(2026);
                ?>
            </div>
            <div class='comenzi-saptamani'>
                <button onclick='aplicaFiltrarea()' class='centrare'>Aplica</button>
            </div>
        </div>
    </div>
</div>
