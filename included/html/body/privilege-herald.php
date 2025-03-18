<div id='container'>
    <div class='bara-control'>Editeaza privilegiile</div>
    <div class='content-editare'>
        <div id='search-bar'>
            <label>
                Criterii de cautare
                <select id='criterii'>
                    <option selected>Totala</option>
                    <option>Inscris doar la scoala</option>
                    <option>Vestitor nebotezat</option>
                    <option>Vestitor</option>
                    <option>Slujitor auxiliar</option>
                    <option>Batran</option>
                    <option>Masculin</option>
                    <option>Feminin</option>
                </select>
            </label>
            <label>
                <button id='select-vestitori'>Bifare</button>
            </label>
            <label>
                <button id='select-privilegii'>Debifare</button>
            </label>
            <label>
                <button id='save'>Salveaza</button>
            </label>
        </div>
        <form id='vestitori-privilegii'>

        </form>
        <form id='vestitori-aprobari'>
            <?php
                echo checkbox_privlegii();
            ?>
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