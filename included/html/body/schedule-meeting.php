<div id='container'>
    <form id='schedule-meeting'>
        <div class='panel-butoane'>
            <label>
                <select class='year-class' id='year' name='year'>
                    <?php
                        get_meetings_year()
                    ?>
                </select>
            </label>
            <label>
                <select id='week' name='week'>
                    <?php
                        $year = date("Y"); 
                        $date_azi = strtotime(date("".$year."-m-d"));
                        $value = date("W", $date_azi);
                        $week = $value; 

                        get_all_week($year,$week)
                    ?>
                </select>
            </label>
            <div onclick='togglePlanificari()' class='buton-planificare'>
                <span id='text-plan' class="material-symbols-outlined mode-planificare">
                    calendar_today
                </span>
                <span id='edit-plan' class="material-symbols-outlined">
                    edit_calendar
                </span>
            </div>
        </div>
    </form>
    <div class='caiet-aspect'>
        <div id='caiet-view'></div>
        <div id='caiet-planificare'></div>
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