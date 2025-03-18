<div id='container'>
    <form id='print-teme'>
        <label>
            <select id='year' name='year'>
                <?php
                    get_meetings_year();
                ?>
            </select>
        </label>   
        <label>
            <select id='month' name='month'>
                <?php
                    get_meetings_months();
                ?>
            </select>
        </label>
    </form>
    <div class='content'>
        <div id='pagina' class='pagina-teme'>
        </div>
    </div>
        <div id='print' onclick='printPage()'>
            PDF
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