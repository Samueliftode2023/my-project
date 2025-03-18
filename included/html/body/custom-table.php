<div id='container'>
    <div class='bara-control'>
        Creeaza o planificare personalizata
    </div>
    <div id="start-panel" class='content-container'>
        <div id='table-panel'>
            <h3>Tabele existente</h3>
            <div id='table-list'>
                <?php
                $name_table_sql = $_SESSION['username'].'_'.'custom_table';
                $sql = "SHOW TABLES LIKE '".$name_table_sql."'";
                $result = mysqli_query($conectareDB, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $sql = 'SELECT * FROM ' .$name_table_sql .'';
                    $result = mysqli_query($conectareDB, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)){
                            echo '<div id="'.$row['nume_tabel'].'" 
                            onclick="editTable(this)" class="buton-enter-edit">'.$row['nume_tabel'].'</div>';
                        }
                    }                
                    else{
                    echo "<div class='message-tabel'>Gol</div>";
                }
                }
                else{
                    echo "<div class='message-tabel'>Gol</div>";
                }
                ?>
            </div>
            <div onclick='openPanelCreate()' class='style-buton'>Creeaza</div>
        </div>
        <div id='back-panel' class='background-panel dis-none'>
            <form id='create-table-id' class='panel-create-table'>
                <label>
                    Sabileste un nume tabelului
                    <input type='text' name='name-table'>
                </label>
                <br>
                <button>Salveaza</button>
            </form>
            <div onclick='openPanelCreate()' class='close-panel'>INCHIDE</div>
        </div>
    </div>
    <div id='tools-content-json' class='dis-none'>
        <div id='tools'>
            <div class='butoane-tools'>
                <div>Aspect</div>
                <div>Elemente</div>
            </div>
            <div id='aspect-pagina' class='dis-none'>
                <div onclick='customPadding()' class='buton-margini'>
                    <span class="material-symbols-outlined">
                        pivot_table_chart
                    </span>
                    <div>Margini</div>
                </div>
                <div onclick='customGrid()' class='buton-grid'>
                    <span  class="material-symbols-outlined">
                        grid_3x3
                    </span>
                    <div>Grid</div>
                </div>
            </div>
            <div id='elemente-pagina'>
                <div onclick='addTable()' class='buton-margini'>
                    <span class="material-symbols-outlined">
                        table
                    </span>
                    <div>Tabel</div>
                </div>
                <div onclick='editCell()' id='edit-cell-buton' style='border-radius:0px;' class='buton-margini disbaled-class'>
                    <span class="material-symbols-outlined">
                        table_edit
                    </span>
                    <div>Editare</div>
                </div>
            </div>
        </div>
        <div id='content-json'>
            <!--PANOURI CU FUNCTII-->   
                <!--CUSTOM PADDING-->   
                    <div id='custom-padding' class='dis-none'>
                        <div class='mutare-padding-panel'></div>
                        <label>
                            Margine stanga
                            <input id='custom-width-left' type='number' min="0">
                        </label>
                        <br>
                        <label>
                            Margine dreapta
                            <input id='custom-width-right' type='number' min="0">
                        </label>
                        <br>
                        <label>
                            Margine sus
                            <input id='custom-height-top' type='number' min="0">
                        </label>
                        <br>
                        <label>
                            Margine jos
                            <input id='custom-height-bottom' type='number' min="0">
                        </label>
                    </div>    
                <!----> 
                <!--CUSTOM GRID--> 
                    <div id='custom-grid' class='dis-none'>
                        <div class='mutare-padding-panel'></div>
                        <label>
                            Coloane
                            <input id='custom-grid-columns' type='number' min="0" max='30'>
                        </label>
                        <br>
                        <label>
                            Randuri
                            <input id='custom-grid-rows' type='number' min="0" max='30'>
                        </label>
                    </div>
                <!----> 
            <!---->    
        </div>
        <div id='manage-elements'>
            <div onclick='selectThisElement(this)' id='element-pagina-planificare' class='element-style select-element'>Pagina</div>
        </div>
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