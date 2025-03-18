<div id='container'>
    <div class='bara-control'></div>
    <form class='dis-none' id='download-meeting' method='post'>
        <label>
            Anul:
            <select name='meeting-year'>
                <?php
                    get_meetings_year();
                ?>
            </select>
        </label>   
        <label>
            Luna:
            <select name='meeting-month'>
                <?php
                    get_meetings_months();
                ?>
            </select>
        </label>
        <button>Descarca</button>
    </form>
    <div id='fisiere-download'>
        <?php
            readDirector();
        ?>
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
<div id='display-message'>

</div>
<div id='filtrare' class='dis-none'>

</div>